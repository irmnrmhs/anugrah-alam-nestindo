<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\FpAlum;
use App\Models\Product;
use App\Models\TestType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FpAlumController extends Controller
{
    public string $obj = 'Hasil Uji Aluminium Produk Jadi';
    public function index(): View
    {
        $results = FpAlum::with('product')->latest()->get();
        $products = Product::all();

        return view('quality-control.fp-alum', compact('results', 'products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
            'tgl' => 'required|date',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        $alum = TestType::where('kode', 'QCPJL')->first();

        $isValid = 
            $validated['kadar_aluminium'] > $alum->standar_minimal && $validated['kadar_aluminium'] < $alum->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;

        $result = FpAlum::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = FpAlum::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:products,id',
            'tgl' => 'required|date',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);
        
        $result = FpAlum::findOrFail($id);
        $alum = TestType::where('kode', 'QCPJL')->first();

        $isValid = 
            $validated['kadar_aluminium'] > $alum->standar_minimal && $validated['kadar_aluminium'] < $alum->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;
        
        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = FpAlum::findOrFail($id);
        $result->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.products_id'   => 'required|exists:products,id',
            'items.*.tgl' => 'required|date',
            'items.*.kadar_aluminium' => 'required|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];
        $alum = TestType::where('kode', 'QCPJL')->first();

        foreach ($items as $item) {
            $isValid = 
                $item['kadar_aluminium'] > $alum->standar_minimal && $item['kadar_aluminium'] < $alum->standar_maksimal;

            $item['hasil'] = $isValid ? 1 : 0;

            FpAlum::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function export($id)
    {
        $fpls = FpAlum::with([
            'product'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCPJL')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.fpl-form', compact('fpls', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Aluminium Produk Jadi.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FpAlum::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }    
}
