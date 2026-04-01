<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Employee;
use App\Models\Export;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public string $obj = 'Ceklis Kendaraan';
    public function index(): View
    {
        $vehicles = Vehicle::with('export', 'car', 'employee')->latest()->get();
        $exports = Export::all();
        $cars = Car::all();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'Produksi');
            })->get();

        return view('orders.vehicle', compact('vehicles', 'exports', 'cars', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'cars_id' => 'required|exists:cars,id',
            'emp_id' => 'required|exists:employees,id',
            'berat' => 'required|numeric',
            'kondisi_box' => 'required',
            'kemasan' => 'required',
        ]);

        Vehicle::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);
        return response()->json($vehicle);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'cars_id' => 'required|exists:cars,id',
            'emp_id' => 'required|exists:employees,id',
            'berat' => 'required|numeric',
            'kondisi_box' => 'required',
            'kemasan' => 'required',
        ]);

        $vehicle = Vehicle::findOrFail($id);

        $vehicle->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $vehicle,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $vehicle = Vehicle::findOrFail($id);
        $vehicle->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Vehicle::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
