<?php

namespace App\Http\Controllers;

use App\Models\Analysis;
use App\Models\AnalysisCertficate;
use App\Models\Export;
use App\Models\FinishedProduct;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class AnalysisCertficateController extends Controller
{
    public string $obj = 'Sertifikat Analisis';
    public function index(): View
    {
        $certificates = AnalysisCertficate::with('fproduct', 'export', 'analysis')->latest()->get();
        $fproducts = FinishedProduct::all();
        $exports = Export::all();
        $analysis = Analysis::all();

        return view('quality-control.analysis-certificate', compact('certificates', 'fproducts', 'exports', 'analysis'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'exports_id' => 'required|exists:exports,id',
            'analysis_id' => 'required|exists:analysis,id',
            'tanggal' => 'required|date',
            'hasil' => 'required|boolean',
        ]);

        AnalysisCertficate::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $certificate = AnalysisCertficate::with('fproduct', 'export', 'analysis')->findOrFail($id);
        return response()->json($certificate);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'fproducts_id' => 'required|exists:finished_products,id',
            'exports_id' => 'required|exists:exports,id',
            'analysis_id' => 'required|exists:analysis,id',
            'tanggal' => 'required|date',
            'hasil' => 'required|boolean',
        ]);

        $certificate = AnalysisCertficate::findOrFail($id);

        $certificate->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $certificate,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $certificate = AnalysisCertficate::findOrFail($id);
        $certificate->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        AnalysisCertficate::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
