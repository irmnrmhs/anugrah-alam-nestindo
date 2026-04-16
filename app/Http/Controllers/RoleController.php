<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public string $obj = 'Role';

    public function index(): View
    {
        $roles = Role::latest()->get();

        return view('masters.role', compact('roles'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        $role = Role::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $role,
        ]);
    }
    
    public function show(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        return response()->json($role);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $role,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);
        $role->delete();
                                                                                                                
        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
