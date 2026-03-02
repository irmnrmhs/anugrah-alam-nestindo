<?php

namespace App\Http\Controllers;

use App\Models\Officer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class OfficerController extends Controller
{
    public string $obj = 'Petugas Karantina';
    public function index(): View
    {
        $officers = Officer::latest()->get();
        return view('masters.officer', compact('officers'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'nama' => 'required',
        ]);

        $officer = Officer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
        ]);
    }

    public function show(int $id): JsonResponse{
        $officer = Officer::findOrFail($id);
        return response()->json($officer);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'officer' => 'required',
        ]);

        $officer = Officer::findOrFail($id);
        $officer->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui.',
                'data' => $officer,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $officer = Officer::findOrFail($id);
        $officer->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
