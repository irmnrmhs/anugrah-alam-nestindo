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
            'no_skp' => $request->kh == 1 ? 'required' : 'nullable',
            'tgl_skp' => [
                'required',
                'date',
                Rule::unique('dcertificates')->where(function ($query) use ($request) {
                    return $query->where('suppliers_id', $request->suppliers_id)
                                ->where('wbhouses_id', $request->wbhouses_id);
                }),
            ],
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99',
        ]);

        $wb = WBHouse::with('area')->find($validated['wbhouses_id']);

        // 1. Jika KH = ya → user input
        if ($wb->area->kh == 1) {
            if (empty($validated['no_skp'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor SKP wajib diisi karena KH = ya'
                ], 422);
            }
        }

        // 2. Jika KH = tidak → generate otomatis
        if ($wb->area->kh == 0) {

            // $nextId = (Dcertificate::max('id') ?? 0) + 1;
            // $serial = str_pad($nextId, 3, '0', STR_PAD_LEFT);

            $area = $wb->area->kode;

            $bln = Carbon::parse($validated['tgl_skp'])->month;
            $roman = [
                1=>'I',2=>'II',3=>'III',4=>'IV',5=>'V',6=>'VI',
                7=>'VII',8=>'VIII',9=>'IX',10=>'X',11=>'XI',12=>'XII'
            ][$bln];

            $last = Dcertificate::whereHas('wbhouse.area', function($q) {
                $q->where('kh', 0);
            })
            ->orderBy('id', 'desc')
            ->first();

            $nextNumber = $last ? intval(substr($last->no_skp, -3)) + 1 : 1;

            $validated['no_skp'] = 'SKP/'.$area.'/'.$roman.'/'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            // $validated['no_skp'] = "SKP/$area/$roman/$serial";
        }

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
            'no_skp' => 'nullable|string|max:100',
            'tgl_skp' => [
                'required',
                'date',
                Rule::unique('dcertificates')->where(function ($query) use ($request) {
                    return $query->where('suppliers_id', $request->suppliers_id)
                                ->where('wbhouses_id', $request->wbhouses_id);
                }),
            ],
            'tgl_panen' => 'required|date',
            'berat_panen' => 'required|numeric|min:0|max:99999.99',
            'tgl_kirim' => 'required|date',
            'berat_kirim' => 'required|numeric|min:0|max:99999.99',
        ]);

        $dcertificate = Dcertificate::findOrFail($id);

        $wb = WBHouse::with('area')->find($validated['wbhouses_id']);

        if ($wb->area->kh == 1) {
            // KH manual → wajib diisi user
            if (empty($validated['no_skp'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor SKP wajib diisi karena KH = ya'
                ], 422);
            }
            $dcertificate->update($validated);
        } else {
            // KH auto → generate nomor urut
            $area = $wb->area->kode;

            $bln = Carbon::parse($validated['tgl_skp'])->month;
            $romanMonths = [
                1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
                7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
            ];
            $blnRmw = $romanMonths[$bln];

            // Ambil nomor terakhir hanya untuk kh = 0
            $last = Dcertificate::whereHas('wbhouse.area', function($q) {
                    $q->where('kh', 0);
                })
                ->orderBy('id', 'desc')
                ->first();

            $nextNumber = $last ? intval(substr($last->no_skp, -3)) + 1 : 1;

            $validated['no_skp'] = 'SKP/'.$area.'/'.$blnRmw.'/'.str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

            $dcertificate->update($validated);
        }

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
