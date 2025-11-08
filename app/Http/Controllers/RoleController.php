<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoleController extends Controller
{
    public string $obj = 'Role';

    // Display a listing of the resource.
    public function index(): View
    {
        $roles = Role::oldest()->get();

        return view('masters.role', compact('roles'));
    }

    // Simpan data role baru (AJAX)
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $role,
        ]);
    }

    
    // Ambil data satu role (untuk edit modal)
    public function show(int $id): JsonResponse
    {
        $role = Role::findOrFail($id);

        return response()->json($role);
    }

    
    // Update data role (AJAX)
    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $id,
        ]);

        $role = Role::findOrFail($id);
        $role->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $role,
        ]);
    }

    // Hapus role (AJAX)
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
