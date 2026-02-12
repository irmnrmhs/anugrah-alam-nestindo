<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;
use App\Models\RawMaterial;
use App\Models\GradeFeather;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class GradeFeatherController extends Controller
{
    public string $obj = 'Grading Bulu';
    public function index(): View
    {
        $feathers = GradeFeather::with('rawMaterial', 'employee')->latest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::where('status', 1)->get();

        return view('raw-material.grade-feather', compact('feathers', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id'        => 'required|exists:raw_materials,id',
            'employees_id'  => 'required|exists:employees,id',
            'tanggal'       => 'required|date',
            'mk'            => 'nullable|integer|min:0',
            'ovl'           => 'nullable|integer|min:0',
            'sdt'           => 'nullable|integer|min:0',
            'pth'           => 'nullable|integer|min:0',
            'hcr'           => 'nullable|integer|min:0',
            'bj_brp'        => 'nullable|integer|min:0',
            'br_brp'        => 'nullable|integer|min:0',
            'bj_bs'         => 'nullable|integer|min:0',
            'br_bs'         => 'nullable|integer|min:0',
            'bj_bb'         => 'nullable|integer|min:0',
            'br_bb'         => 'nullable|integer|min:0',
        ]);

        $rm = RawMaterial::find($validated['rms_id']);

        if (
            $validated['mk'] + $validated['ovl'] + $validated['sdt'] + $validated['pth'] + $validated['hcr'] > $rm->biji_sisa ||
            $validated['bj_brp'] + $validated['bj_bs'] + $validated['bj_bb'] > $rm->biji_sisa ||
            $validated['br_brp'] + $validated['br_bs'] + $validated['br_bb'] > $rm->berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        RmStock::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan'
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $stock = RmStock::with('rawMaterial', 'employee')->findOrFail($id);
        return response()->json($stock);
    }

    public function materialInfo($id)
    {
        $raw = RawMaterial::findOrFail($id);
        $lastOut = RmStock::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $raw->biji_sisa,
            'berat_sisa' => $raw->berat_sisa,
            'last_date' => $lastOut?->tgl_keluar,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tgl_keluar'   => 'nullable|date',
            'biji_keluar' => 'required|integer|min:0',
            'berat_keluar' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $stock = RmStock::findOrFail($id);
        $raw = RawMaterial::find($validated['rms_id']);

        $biji_sisa = $raw->biji_sisa + $stock->biji_keluar;
        $berat_sisa = $raw->berat_sisa + $stock->berat_keluar;

        if (
            $validated['biji_keluar'] > $biji_sisa ||
            $validated['berat_keluar'] > $berat_sisa
        ) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $stock->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $stock = RmStock::findOrFail($id);
        $stock->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.rms_id'          => 'required|exists:raw_materials,id',
            'items.*.employees_id'    => 'required|exists:employees,id',
            'items.*.tgl_keluar'     => 'nullable|date',
            'items.*.biji_keluar'     => 'required|integer|min:0',
            'items.*.berat_keluar'    => 'required|numeric|min:0|max:99999.99',
            'items.*.keterangan'      => 'nullable',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                $raw = RawMaterial::find($item['rms_id']);

                if (
                    $item['biji_keluar'] > $raw->biji_sisa ||
                    $item['berat_keluar'] > $raw->berat_sisa
                ) {
                    throw new \Exception('Melebihi stok sisa');
                }

                RmStock::create($item);
            }
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Semua Stok berhasil ditambahkan.',
        ]);
    }

    public function export(Request $request, $id)
    {
        $type = $request->get('type', 'pdf');

        $rm = RawMaterial::with([
            'arrivals.dcertificate.wbhouse',
            'stocks.employee'
        ])->findOrFail($id);

        $stocks = $rm->stocks()->with('employee')->get();

        if ($stocks->isEmpty()) {
            abort(404, 'Data stok keluar belum tersedia untuk kode ini');
        }

        if ($type === 'excel') {

            $filename = 'Stok Bahan Baku - ' .
                str_replace(['/', '\\'], '-', $rm->kode) . '.xlsx';

            return Excel::download(
                new RmStockExport($stocks),
                $filename
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'SBB058')
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.rm-stock-form',
            compact('rm', 'stocks', 'document')
        )->setPaper('A4', 'landscape');

        $filename = 'Stok_Bahan_Baku_' .
            str_replace(['/', '\\'], '-', $rm->kode) . '.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        RmStock::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
