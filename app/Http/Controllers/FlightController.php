<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class FlightController extends Controller
{
    public string $obj = 'Penerbangan';
    public function index(): View
    {
        $flights = Flight::latest()->get();
        return view('orders.flight', compact('flights'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'flight_no'     => 'required|unique:flights,flight_no',
            'destination'   => 'required',
            'shipping_mark' => 'required|boolean',
            'estimated_arrival' => 'required|date',
        ]);

        Flight::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function show(int $id): JsonResponse{
        $flight = Flight::findOrFail($id);
        return response()->json($flight);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'flight_no'     => 'required|unique:flights,flight_no,' .$id,
            'destination'   => 'required',
            'shipping_mark' => 'required|boolean',
            'estimated_arrival' => 'required|date',
        ]);

        $flight = Flight::findOrFail($id);
        $flight->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $flight = Flight::findOrFail($id);
        $flight->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Flight::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }   
}
