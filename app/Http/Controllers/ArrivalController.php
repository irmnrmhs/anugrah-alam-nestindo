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
    public function index(): View
    {
        $arrivals = Arrival::with('employee', 'car', 'dcertificate')->oldest()->get();
        $employees = Employee::all();
        $cars = Car::all();
        $dcertificates = Dcertificate::all();

        return view('masters.arrival', compact('arrivals', 'employees', 'cars', 'dcertificates'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kondisi'           => 'required',
            'keterangan'        => 'nullable',
            'drivers_id'      => 'required',
            // 'receivers_id'      => 'required',
            'cars_id'           => 'required',
            'dcertificates_id'  => 'required',
        ]);

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
            'kondisi'           => 'required',
            'keterangan'        => 'nullable',
            'drivers_id'      => 'required',
            // 'receivers_id'      => 'required',
            'cars_id'           => 'required',
            'dcertificates_id'  => 'required',
        ]);

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