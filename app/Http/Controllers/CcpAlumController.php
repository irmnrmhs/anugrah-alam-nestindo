<?php

namespace App\Http\Controllers;

use App\Models\CcpAlum;
use App\Models\Document;
use App\Models\RawMaterial;
use App\Models\TestType;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CcpAlumController extends Controller
{
    public string $obj = 'Hasil Uji Aluminium Selama Proses (CCP1)';
    public function index(): View
    {
        $results = CcpAlum::with('rawMaterial')->latest()->get();
        $rms = RawMaterial::all();

        return view('quality-control.ccp-alum', compact('results', 'rms'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'ccp_al' => 'required|numeric|min:0|max:999.9',
        ]);

        $ccpl = TestType::where('kode', 'QCCCPL')->first();

        $isValid = 
            $validated['ccp_al'] > $ccpl->standar_minimal && $validated['ccp_al'] < $ccpl->standar_maksimal;

        $validated['hasil'] = $isValid ? 1 : 0;

        $result = CcpAlum::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $result,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $result = CcpAlum::findOrFail($id);
        return response()->json($result);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'tgl' => 'required|date',
            'ccp_al' => 'required|numeric|min:0|max:999.9'
        ]);
        
        $result = CcpAlum::findOrFail($id);
        $ccpl = TestType::where('kode', 'QCCCPL')->first();

        $isValid = 
            $validated['ccp_al'] > $ccpl->standar_minimal && $validated['ccp_al'] < $ccpl->standar_maksimal;

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
        $result = CcpAlum::findOrFail($id);
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
            'items.*.ccp_al' => 'required|numeric|min:0|max:999.9'
        ]);

        $items = $validated['items'];

        $ccpl = TestType::where('kode', 'QCCCPL')->first();

        foreach ($items as $item) {
            $isValid = 
                $item['ccp_al'] > $ccpl->standar_minimal && $item['ccp_al'] < $ccpl->standar_maksimal;

            $item['hasil'] = $isValid ? 1 : 0;

            CcpAlum::create($item);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua hasil uji berhasil ditambahkan.',
        ]);
    }

    public function export($id)
    {
        $ccpls = CcpAlum::with([
            'rawMaterial'
        ])
        ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'QCCCPL')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.ccpl-form', compact('ccpls', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'Aluminium Selama Proses.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        CcpAlum::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
