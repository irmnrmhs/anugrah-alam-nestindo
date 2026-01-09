<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\History;
use App\Models\Employee;
use App\Models\FpGrade;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ProductController extends Controller
{
    public string $obj = 'Pengidentifikasi Produk';
    public function index(): View
    {
        $products = Product::with('history', 'employee', 'grade')->latest()->get();
        $histories = History::where('tujuan', 'PR11GP')->get();
        $employees = Employee::all();
        $grades = FpGrade::all();

        return view('production.product', compact('products', 'histories', 'employees', 'grades'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'grades_id' => 'required|exists:fp_grades,id',
            'tgl_mulai' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'required|date'
        ]);

        $tracker = History::with('identifier')->find($validated['histories_id']);

        $grade = FpGrade::find($validated['grades_id']);
        $pi = preg_replace('/[^A-Za-z0-9]/', '', $tracker->identifier->kode);
        $validated['kode'] = $grade->kode . "-" . $pi;

        if(
            $validated['biji'] > $tracker->sisa_biji_produk ||
            $validated['berat'] > $tracker->sisa_berat_produk
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $product = Product::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $product,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        return response()->json($product);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Product::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_sesek,
            'berat_sisa' => $tracker->sisa_berat_sesek,
            'last' => $last?->tgl_mulai,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'grades_id' => 'required|exists:fp_grades,id',
            'tgl_mulai' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'required|date'
        ]);

        $product = Product::findOrFail($id);
        $tracker = History::with('identifier')->find($validated['histories_id']);

        $grade = FpGrade::find($validated['grades_id']);
        $pi = preg_replace('/[^A-Za-z0-9]/', '', $tracker->identifier->kode);
        $validated['kode'] = $grade->kode . "-" . $pi;

        $biji_sisa = $tracker->sisa_biji_produk + $product->biji_masuk;
        $berat_sisa = $tracker->sisa_berat_produk + $product->berat_masuk;

        if(
            $validated['biji'] > $biji_sisa ||
            $validated['berat'] > $berat_sisa   
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan lainnya',
            ], 422);
        }

        $product->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus.',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Product::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
