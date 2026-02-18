<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Company;
use App\Models\WBHouse;
use App\Models\Document;
use Illuminate\View\View;
use App\Models\Dcertificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Validation\Rule;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\SkpExport;

class DcertificateController extends Controller
{
    public string $obj = 'Data Pengiriman';
    public function index(): View
    {
        $dcertificates = Dcertificate::with('company', 'wbhouse')->latest()->get();
        $companies = Company::all();
        $wbhouses = WBHouse::all();

        return view('raw-material.dcertificate', compact('dcertificates', 'companies', 'wbhouses'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'companies_id' => 'required|exists:companies,id',
            'wbhouses_id' => 'required|exists:w_b_houses,id',
            'no_skp' => 'required|unique:dcertificates,no_skp',
            'tgl_skp' => ['required','date',
                Rule::unique('dcertificates')->where(function ($query) use ($request) {
                    return $query->where('wbhouses_id', $request->wbhouses_id);
                }),
            ]
        ]);
 
        $dcertificate = Dcertificate::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $dcertificate = Dcertificate::with('company', 'wbhouse.area')->findOrFail($id);
        return response()->json($dcertificate);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $dcertificate = Dcertificate::findOrFail($id);

        $validated = $request->validate([
            'companies_id' => 'required|exists:companies,id',
            'wbhouses_id'  => 'required|exists:w_b_houses,id',
            'no_skp' => 'required|unique:dcertificates,no_skp,' .$id,
            'tgl_skp' => ['required', 'date',
                Rule::unique('dcertificates')
                    ->where(fn ($q) =>
                        $q->where('wbhouses_id', $request->wbhouses_id)
                    )
                    ->ignore($id),
            ]
        ]);

        $dcertificate->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            // 'data'    => $dcertificate->fresh(),
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

    public function preview($id)
    {
        $dcertificate = Dcertificate::with([
            'company', 'wbhouse','details'
        ])->findOrFail($id);

        $document = Document::where('kode', 'SKP058')->firstOrFail();

        return view('exports.forms.skp', compact('dcertificate', 'document'));
    }

    public function export(Request $request, $id)
    {
        $type = $request->get('type', 'pdf');

        $dcertificate = Dcertificate::with([
            'company','wbhouse','details'
        ])->findOrFail($id);

        if ($type === 'excel') {

            $filename = 'SKP-' . str_replace(['/', '\\'], '-', $dcertificate->no_skp) . '.xlsx';

            return Excel::download(
                new SkpExport($dcertificate),
                $filename
            );
        }

        $document = Document::where('kode', 'SKP058')->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.forms.skp',
            compact('dcertificate', 'document')
        )->setPaper('A4', 'portrait');

        $filename = 'SKP-' . str_replace(['/', '\\'], '-', $dcertificate->no_skp) . '.pdf';

        return $pdf->stream($filename);
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
