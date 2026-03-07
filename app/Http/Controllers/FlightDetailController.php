<?php

namespace App\Http\Controllers;

use App\Models\Export;
use App\Models\Flight;
use App\Models\FlightDetail;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class FlightDetailController extends Controller
{
    public string $obj = 'Detail Penerbangan';
    public function index(): View
    {
        $dflights = FlightDetail::with('export', 'flight')->latest()->get();
        $exports = Export::all();
        $flights = Flight::all();

        return view('orders.flight-details', compact('dflights', 'exports', 'flights'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'flights_id' => 'required|exists:flights,id',
        ]);

        FlightDetail::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $dflight = FlightDetail::with('export', 'flight')->findOrFail($id);
        return response()->json($dflight);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'exports_id' => 'required|exists:exports,id',
            'flights_id' => 'required|exists:flights,id',
        ]);

        $dflight = FlightDetail::findOrFail($id);

        $dflight->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $dflight,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $dflight = FlightDetail::findOrFail($id);
        $dflight->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        FlightDetail::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
