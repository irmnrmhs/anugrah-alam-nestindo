<?php

namespace App\Http\Controllers;

use App\Models\RawMaterial;
use App\Models\RmResult;
use App\Models\TestType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class RmResultController extends Controller
{
    public string $obj = 'Hasil Uji';
    public function index(): View
    {
        $results = RmResult::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.rmResult', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9',
            'ccp1' => 'required|numeric|min:0|max:999.9',
        ]);

        $ujiMap = [
            'kadar_air'        => 'kadar_air',
            'kadar_nitrit'     => 'kadar_nitrit',
            'kadar_aluminium'  => 'kadar_aluminium',
            'ccp1'             => 'ccp1',
        ];

        $standars = TestType::where('categories_id', 1)
            ->whereIn('nama_uji', array_values($ujiMap))
            ->get()
            ->keyBy('nama_uji');

        foreach ($ujiMap as $field => $namaUji) {

            if (!isset($validated[$field])) continue;

            if (!isset($standars[$namaUji])) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Standar uji {$namaUji} belum tersedia"
                ], 422);
            }

            $nilai = $validated[$field];
            $min   = $standars[$namaUji]->standar_minimal;
            $max   = $standars[$namaUji]->standar_maksimal;

            if ($nilai < $min || $nilai > $max) {
                return response()->json([
                    'status' => 'error',
                    'message' => "Nilai {$namaUji} harus antara {$min} dan {$max}"
                ], 422);
            }
        }

        $result = RmResult::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = RmResult::with('rawMaterial')->findOrFail($id);
        return response()->json($result);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
            'kadar_aluminium' => 'required|numeric|min:0|max:999.9',
            'ccp1' => 'required|numeric|min:0|max:999.9'
        ]);

        $result = RmResult::findOrFail($id);

        // $ujiMap = [
        //     'kadar_air'        => 'kadar_air',
        //     'kadar_nitrit'     => 'kadar_nitrit',
        //     'kadar_aluminium'  => 'kadar_aluminium',
        //     'ccp1'             => 'ccp1',
        // ];

        // $standars = TestType::where('categories_id', 1)
        //     ->whereIn('nama_uji', array_values($ujiMap))
        //     ->get()
        //     ->keyBy('nama_uji');

        // foreach ($ujiMap as $field => $namaUji) {

        //     if (!isset($validated[$field])) continue;

        //     if (!isset($standars[$namaUji])) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => "Standar uji {$namaUji} belum tersedia"
        //         ], 422);
        //     }

        //     $nilai = $validated[$field];
        //     $min   = $standars[$namaUji]->standar_minimal;
        //     $max   = $standars[$namaUji]->standar_maksimal;

        //     if ($nilai < $min || $nilai > $max) {
        //         return response()->json([
        //             'status' => 'error',
        //             'message' => "Nilai {$namaUji} harus antara {$min} dan {$max}"
        //         ], 422);
        //     }
        // }

        $result->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $result,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $result = RmResult::findOrFail($id);
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
            'items.*.rms_id'   => 'required|exists:raw_materials,id',
            'items.*.kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'items.*.kadar_nitrit' => 'nullable|numeric|min:0|max:999.9',
            'items.*.kadar_aluminium' => 'nullable|numeric|min:0|max:999.9',
            'items.*.ccp1' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            RmResult::create($item);
        }

        // $ujiMap = [
        //     'kadar_air'        => 'kadar_air',
        //     'kadar_nitrit'     => 'kadar_nitrit',
        //     'kadar_aluminium'  => 'kadar_aluminium',
        //     'ccp1'             => 'ccp1',
        // ];

        // $standars = TestType::where('categories_id', 1)
        //     ->whereIn('nama_uji', array_values($ujiMap))
        //     ->get()
        //     ->keyBy('nama_uji');

        // foreach ($items as $item) {
        //     foreach ($ujiMap as $field => $namaUji) {

        //         if (!isset($item[$field])) continue;

        //         $nilai = $item[$field];
        //         $min   = $standars[$namaUji]->standar_minimal;
        //         $max   = $standars[$namaUji]->standar_maksimal;

        //         if ($nilai < $min || $nilai > $max) {
        //             return response()->json([
        //                 'status' => 'error',
        //                 'message' => "Nilai {$namaUji} harus antara {$min} dan {$max}"
        //             ], 422);
        //         }
        //     }

        //     RmResult::create($item);
        // }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        RmResult::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
