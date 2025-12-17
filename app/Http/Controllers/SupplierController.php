<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class SupplierController extends Controller
{
    public string $obj = 'Supplier';
    public function index(): View
    {
        $suppliers = Supplier::with('category')->latest()->get();
        $categories = Category::all();

        return view('masters.supplier', compact('suppliers', 'categories'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:25|unique:suppliers,kode',
            'nama' => 'required|string|max:255|unique:suppliers,nama',
            'alamat' => 'nullable',
            'no_telp' => 'nullable',
            'categories_id' => 'required|exists:categories,id',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan.',
            'data' => $supplier,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        return response()->json($supplier);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode' => 'required|string|max:25|unique:suppliers,kode,' .$id,
            'nama' => 'required|string|max:255|unique:suppliers,nama,' .$id,
            'alamat' => 'nullable',
            'no_telp' => 'nullable',
            'categories_id' => 'required|exists:categories,id',
        ]);

        $supplier = Supplier::findOrFail($id);

        $supplier->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui.',
            'data' => $supplier,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }
}
