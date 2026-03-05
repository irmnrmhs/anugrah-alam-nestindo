<?php

namespace App\Http\Controllers;

use App\Models\Steam;
use App\Models\Export;
use App\Models\NestType;
use App\Models\Order;
use Illuminate\View\View;
use App\Models\SteamOfficer;
use Illuminate\Http\Request;
use App\Models\Product;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class SteamController extends Controller
{
    public string $obj = 'Steam';
    public function index(): View
    {
        $steams = Steam::with('nest', 'product')->latest()->get();
        $officers = SteamOfficer::where('status', 1)->get();
        $nests = NestType::all();
        $products = Product::all();

        return view('production.steam', compact('steams', 'officers', 'nests', 'products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
            // 'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'petugas' => 'required',
            'penambahan' => 'required|boolean',
            'sumber_panas' => 'required|boolean',
            'tgl_pemanasan' => 'required|date',
            'lv_air' => 'required|boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
        ]);

        $product = Product::with('history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse')->findOrFail($validated['products_id']);
        $tgl = date('dmy', strtotime($validated['tgl_pemanasan']));

        $validated['batch'] = $product->history->gcolor->rawMaterial->arrivals->first()->dcertificate->wbhouse->kode . '-' . $tgl;
        
        Steam::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $steam = Steam::with('officer', 'nest', 'product')->findOrFail($id);
        return response()->json($steam);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
            // 'officers_id' => 'required|exists:steam_officers,id',
            'nests_id' => 'required|exists:nest_types,id',
            'petugas' => 'required',
            'penambahan' => 'required|boolean',
            'sumber_panas' => 'required|boolean',
            'tgl_pemanasan' => 'required|date',
            'lv_air' => 'required|boolean',
            'suhu_awal' => 'required|numeric|max:999.99',
        ]);

        $product = Product::with('history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse')->findOrFail($validated['products_id']);
        $tgl = date('dmy', strtotime($validated['tgl_pemanasan']));

        $validated['batch'] = $product->history->gcolor->rawMaterial->arrivals->first()->dcertificate->wbhouse->kode . '-' . $tgl;

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

    // public function bulk(Request $request): JsonResponse
    // {
    //     $validated = $request->validate([
    //         'items' => 'required|array|min:1',

    //         'items.*.products_id'    => 'required|exists:products,id',
    //         'items.*.officers_id'    => 'required|exists:steam_officers,id',
    //         'items.*.nests_id'       => 'required|exists:nest_types,id',
    //         'items.*.orders_id'     => 'required|exists:orders,id',

    //         'items.*.penambahan'     => 'required|boolean',
    //         'items.*.sumber_panas'   => 'required|boolean',
    //         'items.*.tgl_pemanasan'  => 'required|date',
    //         'items.*.lv_air'         => 'required|boolean',
    //         'items.*.suhu_awal'      => 'required|numeric|max:999.99',

    //         'items.*.suhu_preheating'           => 'required|numeric|max:99999.99',
    //         'items.*.waktu_preheating'          => 'required|date_format:H:i',
    //         'items.*.suhu_total'     => 'required|numeric|max:999.99',
    //         'items.*.waktu_total'    => 'required|date_format:H:i',
    //     ]);

    //     $items = $validated['items'];
    //     $now = now();

    //     foreach ($items as &$item) {
    //         $product = Product::with('history.gcolor.rawMaterial.arrivals.dcertificate.wbhouse')->findOrFail($item['products_id']);
    //         $tgl = date('dmy', strtotime($item['tgl_pemanasan']));

    //         $validated['batch'] = $product->history->gcolor->rawMaterial->arrivals->first()->dcertificate->wbhouse->kode . '-' . $tgl;
    //     }

    //     Steam::insert($items);

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => $this->obj . ' berhasil ditambahkan.',
    //     ]);
    // }

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
