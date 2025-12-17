<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(): View
    {
        $cars = Car::latest()->get();
        return view('masters.car', compact('cars'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'plat' => 'required|string|max:10|unique:cars,plat',
            'merk' => 'nullable',
        ]);

        $car = Car::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Mobil berhasil ditambahkan.',
            'data' => $car,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $car = Car::findOrFail($id);
        return response()->json($car);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'plat' => 'required|string|max:10|unique:cars,plat,' . $id,
            'merk' => 'nullable',
        ]);

        $car = Car::findOrFail($id);
        $car->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => 'Data Mobil berhasil diperbaharui.',
                'data' => $car,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $car = Car::findOrFail($id);
        $car->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Mobil berhasil dihapus.',
        ]);
    }
}
