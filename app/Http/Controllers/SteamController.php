<?php

namespace App\Http\Controllers;

use App\Models\Steam;
use App\Models\Document;
use App\Models\NestType;
use Illuminate\View\View;
use App\Models\SteamOfficer;
use Illuminate\Http\Request;
use App\Models\FinishedProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class SteamController extends Controller
{
    public string $obj = 'Steam';
    public function index(): View
    {
        $steams = Steam::with('officer', 'nest', 'fproduct')->latest()->get();
        $officers = SteamOfficer::where('status', 1)->get();
        $nests = NestType::all();
        $fproducts = FinishedProduct::all();

        return view('production.steam', compact('steams', 'officers', 'nests', 'fproducts'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'penambahan' => 'required|boolean',
            'sumber_panas' => 'required|boolean',
            'tgl_pemanasan' => 'required|date',
            'standar' => 'required|boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'suhu' => 'required|numeric|max:99999.99',
            'waktu' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'waktu_total' => 'required|date_format:H:i',
            'jml_tray' => 'required|integer|min:0|max:6',
            'keterangan' => 'nullable'
        ]);

        $fproduct = FinishedProduct::with(
            'product.history.identifier.rawMaterial.arrival.dcertificate.wbhouse'
        )->findOrFail($validated['fproducts_id']);

        $kd_product = $fproduct->product->kode;

        $noreg = $fproduct->product
            ->history
            ->identifier
            ->rawMaterial
            ->arrival
            ->dcertificate
            ->wbhouse
            ->kode;

        $tgl = date('dmy', strtotime($validated['tgl_pemanasan']));

        $validated['kode'] = $kd_product . $noreg . '-' . $tgl;

        $steam = Steam::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $steam,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $steam = Steam::with('officer', 'nest', 'fproduct')->findOrFail($id);
        return response()->json($steam);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'penambahan' => 'required|boolean',
            'sumber_panas' => 'required|boolean',
            'tgl_pemanasan' => 'required|date',
            'standar' => 'required|boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'suhu' => 'required|numeric|max:99999.99',
            'waktu' => 'required|date_format:H:i',
            'suhu_total' => 'required|numeric|max:999.99',
            'waktu_total' => 'required|date_format:H:i',
            'jml_tray' => 'required|integer|min:0|max:6',
            'keterangan' => 'nullable'
        ]);

        $kd_product = FinishedProduct::with('product')->find($validated['fproducts_id']);
        $kd_product = $kd_product->product->kode;

        $tgl = $validated['tgl_pemanasan'];
        $noreg = $kd_product->product->history->identifier->rawMaterial->arrival->dcertificate->wbhouse->kode;
        $tgl = date('dmy', strtotime($tgl));
        $validated['kode'] = $kd_product . $noreg . "-" . $tgl;

        $steam = Steam::findOrFail($id);

        $steam->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $steam,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $steam = Steam::findOrFail($id);
        $steam->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',

            'items.*.fproducts_id'   => 'required|exists:finished_products,id',
            'items.*.officers_id'    => 'required|exists:steam_officers,id',
            'items.*.nests_id'       => 'required|exists:nest_types,id',

            'items.*.penambahan'     => 'required|boolean',
            'items.*.sumber_panas'   => 'required|boolean',
            'items.*.tgl_pemanasan'  => 'required|date',
            'items.*.standar'        => 'required|boolean',

            'items.*.suhu_awal'      => 'required|numeric|max:999.99',
            'items.*.biji'           => 'required|integer|min:0',
            'items.*.berat'          => 'required|numeric|min:0|max:99999.99',

            'items.*.suhu'           => 'required|numeric|max:99999.99',
            'items.*.waktu'          => 'required|date_format:H:i',
            'items.*.suhu_total'     => 'required|numeric|max:999.99',
            'items.*.waktu_total'    => 'required|date_format:H:i',

            'items.*.jml_tray'       => 'required|integer|min:0|max:6',
            'items.*.keterangan'     => 'nullable|string',
        ]);

        $items = $validated['items'];
        $now = now();

        foreach ($items as &$item) {

            $fproduct = FinishedProduct::with(
                'product.history.identifier.rawMaterial.arrival.dcertificate.wbhouse'
            )->findOrFail($item['fproducts_id']);

            $kd_product = $fproduct->product->kode;

            $noreg = $fproduct->product
                ->history
                ->identifier
                ->rawMaterial
                ->arrival
                ->dcertificate
                ->wbhouse
                ->kode;

            $tgl = date('dmy', strtotime($item['tgl_pemanasan']));

            $item['kode'] = $kd_product . $noreg . '-' . $tgl;
            $item['created_at'] = $now;
            $item['updated_at'] = $now;
        }

        Steam::insert($items);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function export($id)
    {
        $steams = Steam::findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR13ST')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.steam-form', compact('steams', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Steam.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Steam::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
