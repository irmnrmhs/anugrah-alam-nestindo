<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\WBHouse;
use App\Models\Supplier;
use Illuminate\View\View;
use App\Models\Dcertificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;

class DcertificateController extends Controller
{
    public string $obj = 'Data Pengiriman';
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
            // 'tgl_skp' => 'required|date',
            'tgl_skp' => [
                'required',
                'date',
                Rule::unique('dcertificates')->where(function ($query) use ($request) {
                    return $query->where('suppliers_id', $request->suppliers_id)
                                ->where('wbhouses_id', $request->wbhouses_id);
                }),
            ]
        ]);

        $wb = WBHouse::with('area')->find($validated['wbhouses_id']);

        if ($wb->area->kh == 1) {
            if (empty($validated['no_skp'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor SKP wajib diisi oleh nomor KH'
                ], 422);
            }
        }

        if ($wb->area->kh == 0) {

            $area = $wb->area->kode;

            $bulan = Carbon::parse($validated['tgl_skp'])->month;
            $romanArr = [
                1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI',
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
            ];
            $roman = $romanArr[$bulan];
            $tahun = Carbon::parse($validated['tgl_skp'])->year;

            $last = Dcertificate::where('no_skp', 'like', 'AAN/SKP/%')
                ->orderByRaw(
                    "CAST(SUBSTRING_INDEX(SUBSTRING_INDEX(no_skp,'/',3),'/',-1) AS UNSIGNED) DESC"
                )
                ->first();

            if ($last) {
                $parts = explode('/', $last->no_skp);
                $lastNum = (int) $parts[2];
                $nextNumber = $lastNum + 1;
            } else {
                $nextNumber = 1;
            }

            $validated['no_skp'] =
                'AAN/SKP/' .
                str_pad($nextNumber, 3, '0', STR_PAD_LEFT) . '/' .
                $area . '/' .
                $roman . '/' .
                $tahun;
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
        $dcertificate = Dcertificate::with('company', 'supplier', 'wbhouse.area')->findOrFail($id);
        return response()->json($dcertificate);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dcertificate = Dcertificate::findOrFail($id);
        $wb = WBHouse::with('area')->findOrFail($request->wbhouses_id);

        $validated = $request->validate([
            'companies_id' => 'required|exists:companies,id',
            'suppliers_id' => 'required|exists:suppliers,id',
            'wbhouses_id'  => 'required|exists:w_b_houses,id',
            'tgl_skp'      => 'required|date',
            // 'no_skp'       => 'nullable|string|max:100',
            'tgl_skp' => [
                'required',
                'date',
                Rule::unique('dcertificates')
                    ->where(fn ($q) =>
                        $q->where('suppliers_id', $request->suppliers_id)
                        ->where('wbhouses_id', $request->wbhouses_id)
                    )
                    ->ignore($id),
            ]
        ]);

        if ($wb->area->kh == 1) {
            if (empty($validated['no_skp'])) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nomor SKP wajib diisi oleh nomor KH'
                ], 422);
            }

            $dcertificate->update($validated);
        }
        
        if ($wb->area->kh == 0) {

            $oldNo = $dcertificate->no_skp;

            $parts = explode('/', $oldNo);

            $number = $parts[2];

            $area  = $wb->area->kode;
            $bulan = Carbon::parse($validated['tgl_skp'])->month;
            $tahun = Carbon::parse($validated['tgl_skp'])->year;

            $romanArr = [
                1=>'I', 2=>'II', 3=>'III', 4=>'IV', 5=>'V', 6=>'VI',
                7=>'VII', 8=>'VIII', 9=>'IX', 10=>'X', 11=>'XI', 12=>'XII'
            ];
            $roman = $romanArr[$bulan];

            $validated['no_skp'] =
                'AAN/SKP/' .
                $number . '/' .
                $area . '/' .
                $roman . '/' .
                $tahun;

            $dcertificate->update($validated);
        }

        return response()->json([
            'status'  => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data'    => $dcertificate,
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

        $filename = 'SKP-' . str_replace(['/', '\\'], '-', $dcertificate->no_skp) . '.pdf';

        return $pdf->download($filename);
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
