<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SteamOfficer;
use App\Models\Employee;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SteamOfficerController extends Controller
{
    public function index(): View
    {
        $officers = SteamOfficer::latest()->get();
        $employees = Employee::all();
        return view('masters.steam-officer', compact('officers', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'employees_id' => 'required|exists:employees,id|unique:steams,employees_id',
            'status' => 'required|boolean'
        ]);

        $officer = SteamOfficer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data Rumah Burung berhasil ditambahkan.',
            'data' => $officer,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $officer = SteamOfficer::with('area')->findOrFail($id);
        return response()->json($officer);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'employees_id' => 'required|exists:employees,id|unique:steams,employees_id',
            'status' => 'required|boolean'
        ]);

        $officer = SteamOfficer::findOrFail($id);
        $officer->update($validated);

        return response()->json([
                'status'  => 'success',
                'message' => 'Data Rumah Burung berhasil diperbaharui.',
                'data'    => $officer,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $officer = SteamOfficer::findOrFail($id);
        $officer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data Rumah Burung berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        SteamOfficer::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
