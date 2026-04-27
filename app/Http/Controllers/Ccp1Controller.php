<?php

namespace App\Http\Controllers;

use App\Models\Ccp1;
use App\Models\Document;
use App\Models\RawMaterial;
use App\Models\TestType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class Ccp1Controller extends Controller
{
    public string $obj = 'Hasil Uji Nitrit Selama Proses (CCP1)';
    public function index(): View
    {
        $results = Ccp1::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.ccp1', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'ccp1' => 'required|numeric|min:0|max:999.9',
        ]);

        $result = Ccp1::create($validated);
        $ccp = TestType::where('kode', 'QCCCPN')->first();

        $isValid = 
            $validated['ccp1'] > $ccp->standar_minimal && $validated['ccp1'] < $ccp->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = Ccp1::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'ccp1' => 'required|numeric|min:0|max:999.9'
        ]);
        
        $result = Ccp1::findOrFail($id);
        $ccp = TestType::where('kode', 'QCCCPN')->first();

        $isValid = 
            $validated['ccp1'] > $ccp->standar_minimal && $validated['ccp1'] < $ccp->standar_maksimal;

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
        $result = Ccp1::findOrFail($id);
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
            'items.*.ccp1' => 'nullable|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {
            Ccp1::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function export($id)
    {
        $ccpns = CCP1::with([
            'rawMaterial'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCCCPN')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.ccpn-form', compact('ccpns', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Nitri Selama Proses.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Ccp1::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
