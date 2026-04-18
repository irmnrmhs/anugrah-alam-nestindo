<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\RmResult;
use Illuminate\View\View;
use App\Models\RawMaterial;
use App\Models\TestType;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

class RmResultController extends Controller
{
    public string $obj = 'Hasil Uji Air dan Nitrit Bahan Baku';
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
            'tgl' => 'required|date',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
        ]);

        $result = RmResult::create($validated);
        $mois = TestType::where('kode', 'QCBBA')->first();
        $nitrite = TestType::where('kode', 'QCBBN')->first();

        $isValid =
            $validated['kadar_air'] > $mois->standar_minimal && $validated['kadar_air'] < $mois->standar_maksimal &&
            $validated['kadar_nitrit'] > $nitrite->standar_minimal && $validated['kadar_nitrit'] < $nitrite->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;

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
            'tgl' => 'required|date',
            'kadar_air' => 'required|numeric|min:0|max:999.99',
            'kadar_nitrit' => 'required|numeric|min:0|max:999.9',
        ]);

        $result = RmResult::findOrFail($id);

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
            'items.*.tgl' => 'required|date',
            'items.*.kadar_air' => 'nullable|numeric|min:0|max:999.99',
            'items.*.kadar_nitrit' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            RmResult::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function water($id)
    {
        $bbs = RmResult::with([
            'rawMaterial'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCBBA')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.rmw-form', compact('bbs', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Air Bahan Baku.pdf';

        return $pdf->stream($filename);
    }

    public function nitrite($id)
    {
        $bbs = RmResult::with([
            'rawMaterial'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCBBN')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.rmn-form', compact('bbs', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Nitrit Bahan Baku.pdf';

        return $pdf->stream($filename);
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
