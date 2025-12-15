<?php

namespace App\Http\Controllers;

use App\Models\NestType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class NestTypeController extends Controller
{
    public string $obj = 'Tipe Sarang Walet';
    public function index(): View
    {
        $nests = NestType::latest()->get();
        return view('masters.nest-type', compact('nests'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:10|unique:nest_types,type',
            'keterangan' => 'nullable',
        ]);

        $nest = NestType::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $nest,
        ]);
    }
    
    public function show(int $id): JsonResponse{
        $nest = NestType::findOrFail($id);
        return response()->json($nest);
    }
    
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:10|unique:nest_types,type,' . $id,
            'keterangan' => 'nullable',
        ]);

        $nest = NestType::findOrFail($id);
        $nest->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbaharui',
                'data' => $nest,
        ]);
    }
    
    public function destroy(int $id): JsonResponse
    {
        $nest = NestType::findOrFail($id);
        $nest->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
