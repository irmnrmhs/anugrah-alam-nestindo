<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\FpResult;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\FinishedProduct;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class FpResultController extends Controller
{
    public string $obj = 'Hasil Uji Air dan Nitrit Produk Jadi';
    public function index(): View
    {
        $results = FpResult::latest()->get();
        $products = FinishedProduct::all();

        return view('quality-control.fpResult', compact('results', 'products'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'tgl' => 'required|date',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
        ]);

        if($validated['kadar_air']>15)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Air'
            ], 422);
        }elseif($validated['kadar_nitrit'] > 30)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Nitrit'
            ]);
        }

        $result = FpResult::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = FpResult::findOrFail($id);
        // $result = FpResult::with('rawMaterial')->findOrFail($id);
        return response()->json($result);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'products_id' => 'required|exists:finished_products,id',
            'tgl' => 'required|date',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
        ]);

        if($validated['kadar_air']>15)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Air'
            ], 422);
        }elseif($validated['kadar_nitrit'] > 30)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Tidak memenuhi standar Kadar Nitrit'
            ]);
        }

        $result = FpResult::findOrFail($id);
        
        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = FpResult::findOrFail($id);
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
            'items.*.products_id'   => 'required|exists:finished_products,id',
            'items.*.tgl' => 'required|date',
            'items.*.kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'items.*.kadar_nitrit' => 'nullable|numeric|min:0|max:999.9',
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            FpResult::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function water($id)
    {
        $fps = FpResult::with([
            'product'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCPJA')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.fpw-form', compact('fps', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Air Produk Jadi.pdf';

        return $pdf->stream($filename);
    }

    public function nitrite($id)
    {
        $fps = FpResult::with([
            'product'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCPJN')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.fpn-form', compact('fps', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Nitrit Produk Jadi.pdf';

        return $pdf->stream($filename);
    }
    
    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FpResult::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
