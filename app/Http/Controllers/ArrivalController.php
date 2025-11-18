<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\Car;
use App\Models\Dcertificate;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ArrivalController extends Controller
{
    public string $obj = 'Kedatangan';
    public function index(): View
    {
        $arrivals = Arrival::with('employee', 'car', 'dcertificate')->oldest()->get();
        $employees = Employee::all();
        $cars = Car::all();
        $dcertificates = Dcertificate::all();

        return view('raw-material.arrival', compact('arrivals', 'employees', 'cars', 'dcertificates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            // 'kode'              => 'required|unique:arrivals,kode',
            'dcertificates_id'  => 'required|exists:dcertificates,id|unique:arrivals,dcertificates_id',
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_kedatangan'    => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'required',
            // 'receivers_id'      => 'required',
        ]);

        // 1. Ambil objek Dcertificate (termasuk relasi wbhouse)
        $dcertificate = Dcertificate::with('wbhouse')->find($validated['dcertificates_id']);

        // 2. Ambil KODE dari WBHouse yang berelasi
        $kode_wbhouse = $dcertificate->wbhouse->kode; 
        $tgl_kedatangan = $validated['tgl_kedatangan'];

        $format_tgl = date('dmy', strtotime($tgl_kedatangan));
        // 3. Buat nilai 'kode' baru (KODE_WBHOUSE-TGL_KEDATANGAN)
        $validated['kode'] = $kode_wbhouse . '-' . $format_tgl;

        // 4. Pastikan kode yang dibuat unik di tabel arrivals
        if (Arrival::where('kode', $validated['kode'])->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal: Kode Kedatangan otomatis (' . $validated['kode'] . ') sudah ada. Silahkan periksa SKP atau tanggal kedatangan.',
            ], 409);
        }
        
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
            // 'kode'              => 'required|unique:arrivals,kode,' . $id,
            'dcertificates_id'  => 'required|exists:dcertificates,id|unique:arrivals,dcertificates_id,' . $id,
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_kedatangan'   => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'required',
            // 'receivers_id'      => 'required',
        ]);

        // 1. Ambil objek Dcertificate (termasuk relasi wbhouse)
        $dcertificate = Dcertificate::with('wbhouse')->find($validated['dcertificates_id']);

        // 2. Ambil KODE dari WBHouse yang berelasi
        $kode_wbhouse = $dcertificate->wbhouse->kode; 
        $tgl_kedatangan = $validated['tgl_kedatangan'];

        $format_tgl = date('dmy', strtotime($tgl_kedatangan));

        // 3. Buat nilai 'kode' baru (KODE_WBHOUSE-TGL_KEDATANGAN)
        $validated['kode'] = $kode_wbhouse . '-' . $format_tgl;

        $arrival = Arrival::findOrFail($id);
        
        // 4. Pastikan kode yang diperbarui unik, mengabaikan baris saat ini
        if (Arrival::where('kode', $validated['kode'])->where('id', '!=', $id)->exists()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal: Kode Kedatangan otomatis yang diperbarui (' . $validated['kode'] . ') sudah ada.',
            ], 409);
        }

        $arrival->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil diperbarui.',
            'data' => $arrival,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $arrival = Arrival::findOrFail($id);
        $arrival->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil dihapus.',
        ]);
    }
}