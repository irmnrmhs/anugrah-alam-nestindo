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
            'kode'              => 'required|unique:arrivals,kode',
            'dcertificates_id'  => 'required|unique:dcertificates,id',
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_kedatangan'    => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'required',
            // 'receivers_id'      => 'required',
        ]);

        $dcertificate = Dcertificate::find($request->wbhouse->kode);

        $validated['kode'] = $dcertificate . '-' . 'tgl_kedatangan';

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
            'kode'              => 'required|unique:arrivals,kode,' . $id,
            'dcertificates_id'  => 'required|unique:dcertificates,id,' . $id,
            'cars_id'           => 'required|exists:cars,id',
            'employees_id'      => 'required|exists:employees,id',
            'tgl_pemeriksaan'   => 'required|date',
            'kondisi'           => 'required',
            'keterangan'        => 'required',
            // 'receivers_id'      => 'required',
        ]);

        $dcertificate = Dcertificate::find($request->wbhouse->kode);

        $validated['kode'] = $dcertificate . '-' . 'tgl_kedatangan';

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
        $arrival = Arrival::findOrFail($id);
        $arrival->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data kedatangan berhasil dihapus.',
        ]);
    }
}