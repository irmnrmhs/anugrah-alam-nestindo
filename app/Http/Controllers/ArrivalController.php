<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Arrival;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use App\Models\Dcertificate;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ArrivalExport;

class ArrivalController extends Controller
{
    public string $obj = 'Kedatangan';
    public function index(): View
    {
        $arrivals = Arrival::with('employee', 'car', 'dcertificate')->latest()->get();
        $employees = Employee::whereHas('position', function ($q) {
            $q->where('posisi', 'Supir');
        })->get();

        $cars = Car::all();
        $dcertificates = Dcertificate::all();

        return view('raw-material.arrival', compact('arrivals', 'employees', 'cars', 'dcertificates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'dcertificates_id'  => 'required|exists:dcertificates,id',
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_kedatangan'    => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'nullable',
        ]);

        $dcertificate = Dcertificate::with('wbhouse')->find($validated['dcertificates_id']);

        $kode_wbhouse = $dcertificate->wbhouse->kode; 
        $tgl_kedatangan = $validated['tgl_kedatangan'];

        $format_tgl = date('dmy', strtotime($tgl_kedatangan));
        $validated['kode'] = $kode_wbhouse . '-' . $format_tgl;

        // if (Arrival::where('kode', $validated['kode'])->exists()) {
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Gagal: Kode Kedatangan otomatis (' . $validated['kode'] . ') sudah ada. Silahkan periksa SKP atau tanggal kedatangan.',
        //     ], 409);
        // }
        
        $arrival = Arrival::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Kedatangan berhasil ditambahkan.',
            'data' => $arrival,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $arrival = Arrival::findOrFail($id);
        return response()->json($arrival);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'dcertificates_id'  => 'required|exists:dcertificates,id',
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_kedatangan'   => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'nullable',
        ]);

        $dcertificate = Dcertificate::with('wbhouse')->find($validated['dcertificates_id']);

        $kode_wbhouse = $dcertificate->wbhouse->kode; 
        $tgl_kedatangan = $validated['tgl_kedatangan'];

        $format_tgl = date('dmy', strtotime($tgl_kedatangan));

        $validated['kode'] = $kode_wbhouse . '-' . $format_tgl;

        $arrival = Arrival::findOrFail($id);

        $arrival->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil diperbarui.',
            'data' => $arrival,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $arrival = Arrival::findOrFail($id);

            $arrival->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Data kedatangan berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 409);
        }
    }

    public function export(Request $request, $id)
    {
        $type = $request->get('type', 'pdf');

        $arrival = Arrival::findOrFail($id);

        if ($type === 'excel') {

            $filename = 'Kedatangan - ' . str_replace(['/', '\\'], '-', $arrival->kode) . '.xlsx';

            return Excel::download(
                new ArrivalExport($arrival),
                $filename
            );
        }

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'KBB058')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.arrival-form', compact('arrival', 'document'))
                ->setPaper('A4', 'portrait');

        $filename = 'SKP-' . str_replace(['/', '\\'], '-', $arrival->kode) . '.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Arrival::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}