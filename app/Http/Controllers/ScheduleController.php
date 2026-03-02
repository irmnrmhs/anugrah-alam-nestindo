<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use App\Models\Officer;
use App\Models\Schedule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public string $obj = 'Ekspor';
    public function index(): View
    {
        $schedules = Schedule::with('flight', 'officer')->latest()->get();
        $flights = Flight::all();
        $officers = Officer::all();

        return view('orders.schedule', compact('schedules', 'officers', 'flights'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'flights_id' => 'required|exists:flights,id',
            'officers_id' => 'required|exists:officers,id',
            'preshipment' => 'required|date',
            'shipment' => 'required|date',
        ]);

        Schedule::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $schedule = Schedule::findOrFail($id);
        return response()->json($schedule);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'flights_id' => 'required|exists:flights,id',
            'officers_id' => 'required|exists:officers,id',
            'preshipment' => 'required|date',
            'shipment' => 'required|date',
        ]);

        $schedule = Schedule::findOrFail($id);

        $schedule->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $schedule,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Schedule::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
