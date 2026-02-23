<?php

namespace App\Http\Controllers;

use App\Models\RmStock;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\RmStockExport;

class RmStockController extends Controller
{
    public string $obj = 'Stok keluar';
    public function index(): View
    {
        $stocks = RmStock::with('rawMaterial.arrivals', 'employee')->latest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::where('status', 1)->get();

        return view('raw-material.rmStock', compact('stocks', 'rms', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        try {

            $validated = $request->validate([
                'rms_id' => 'required|exists:raw_materials,id',
                'employees_id' => 'required|exists:employees,id',
                'tanggal'   => 'nullable|date',
                'biji' => 'required|integer|min:0',
                'berat' => 'required|numeric|min:0|max:99999.99',
                'keterangan' => 'nullable'
            ]);

            DB::transaction(function () use ($validated) {

                $rm = RawMaterial::lockForUpdate()->find($validated['rms_id']);

                if (
                    $validated['biji'] > $rm->biji_sisa ||
                    $validated['berat'] > $rm->berat_sisa
                ) {
                    throw new \Exception('Melebihi stok sisa');
                }

                RmStock::create($validated);

                $rm->decrement('biji_sisa', $validated['biji']);
                $rm->decrement('berat_sisa', $validated['berat']);
            });

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil ditambahkan'
            ]);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        $stock = RmStock::with('rawMaterial', 'employee')->findOrFail($id);
        return response()->json($stock);
    }

    public function materialInfo($id)
    {
        $rm = RawMaterial::findOrFail($id);
        $lastOut = RmStock::where('rms_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $rm->biji_sisa,
            'berat_sisa' => $rm->berat_sisa,
            'last_date' => $lastOut?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal'   => 'nullable|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        DB::transaction(function () use ($validated, $id) {

            $stock = RmStock::lockForUpdate()->findOrFail($id);

            $rm = RawMaterial::lockForUpdate()->find($validated['rms_id']);

            $biji_sisa  = $rm->biji_sisa + $stock->biji;
            $berat_sisa = $rm->berat_sisa + $stock->berat;

            if (
                $validated['biji'] > $biji_sisa ||
                $validated['berat'] > $berat_sisa
            ) {
                throw new \Exception('Melebihi stok sisa');
            }

            $stock->update($validated);
        });

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
            'items.*.tanggal'     => 'nullable|date',
            'items.*.biji'     => 'required|integer|min:0',
            'items.*.berat'    => 'required|numeric|min:0|max:99999.99',
            'items.*.keterangan'      => 'nullable',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                $raw = RawMaterial::find($item['rms_id']);

                if (
                    $item['biji'] > $raw->biji_sisa ||
                    $item['berat'] > $raw->berat_sisa
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
            'exports.forms.rm-stock-form',
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
