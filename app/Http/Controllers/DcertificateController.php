<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Supplier;
use App\Models\WBHouse;
use App\Models\Dcertificate;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class DcertificateController extends Controller
{
    public string $obj = 'Grade';
    public function index(): View
    {
        $dcertificates = Dcertificate::with('company', 'supplier', 'wbhouse')->latest()->get();
        $companies = Company::all();
        $suppliers = Supplier::all();
        $wbhouses = WBHouse::all();

        return view('raw-material.dcertificate', compact('dcertificates', 'companies', 'suppliers', 'wbhouses'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'companies_id' => 'required|exists:companies,id',
            'suppliers_id' => 'required|exists:suppliers,id',
            'wbhouses_id' => 'required|exists:w_b_houses,id',
            // 'no_skp' => 'required|string|max:100|unique:dcertificates,no_skp',
            'tgl_skp' => 'required|date',
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99',
        ]);

        $wbhouses = WBHouse::with('area')->find($validated['wbhouses_id']);
        $area = $wbhouses->area->kode;
        
        $bln = Carbon::parse($validated['tgl_skp'])->month;
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        $blnRmw = $romanMonths[$bln];
        $nextId = (Dcertificate::max('id') ?? 0) + 1;
        $serial = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $kode = 'SKP/' . $area . '/' . $blnRmw . '/'. $serial;
        $validated['no_skp'] = $kode;

        $dcertificate = Dcertificate::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $dcertificate,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $dcertificate = Dcertificate::with('company', 'supplier', 'wbhouse')->findOrFail($id);
        return response()->json($dcertificate);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'companies_id' => 'required|exists:companies,id',
            'suppliers_id' => 'required|exists:suppliers,id',
            'wbhouses_id' => 'required|exists:w_b_houses,id',
            // 'no_skp' => 'required|string|max:100|unique:dcertificates,no_skp,' . $id,
            'tgl_skp' => 'required|date',
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99',
        ]);

        $wbhouses = WBHouse::with('area')->find($validated['wbhouses_id']);
        $area = $wbhouses->area->kode;
        
        $bln = Carbon::parse($validated['tgl_skp'])->month;
        $romanMonths = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ];
        $blnRmw = $romanMonths[$bln];
        $nextId = (Dcertificate::max('id') ?? 0) + 1;
        $serial = str_pad($nextId, 3, '0', STR_PAD_LEFT);
        $kode = 'SKP/' . $area . '/' . $blnRmw . '/'. $serial;
        $validated['no_skp'] = $kode;

        $dcertificate = Dcertificate::findOrFail($id);
        $dcertificate->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $dcertificate,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $dcertificate = Dcertificate::findOrFail($id);
        $dcertificate->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function export($id)
    {
        $dcertificate = Dcertificate::with(['company', 'supplier', 'wbhouse'])->findOrFail($id);

        $pdf = Pdf::loadView('exports.skp', compact('dcertificate'))
                ->setPaper('A4', 'portrait');

        return $pdf->download('SKP-' . $dcertificate->no_skp . '.pdf');
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Dcertificate::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
