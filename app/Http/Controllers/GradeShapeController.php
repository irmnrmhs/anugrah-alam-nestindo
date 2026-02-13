<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\View\View;
use App\Models\GradeShape;
use App\Models\RawMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Shape;
use Illuminate\Support\Facades\DB;

class GradeShapeController extends Controller
{
    public string $obj = 'Stok keluar';
    public function index(): View
    {
        $shapes = GradeShape::with('rawMaterial', 'employee', 'shape')->latest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::where('status', 1)->get();
        $shapeList = Shape::all();

        return view('raw-material.grade-shape', compact('shapes', 'rms', 'employees', 'shapeList'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'berat' => 'required|array'
        ]);

        $rm = RawMaterial::findOrFail($request->rms_id);

        DB::beginTransaction();

        try {

            foreach ($request->berat as $shapeId => $berat) {

                if ($berat <= 0) continue;

                $totalBerat = array_sum($request->berat);

                if ($totalBerat > $rm->berat_sisa_shape) {
                    throw new \Exception('Total berat melebihi stok sisa');
                }

                GradeShape::create([
                    'rms_id' => $request->rms_id,
                    'employees_id' => $request->employees_id,
                    'shapes_id' => $shapeId,
                    'tanggal' => $request->tanggal,
                    'berat' => $berat,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Grading berhasil disimpan'
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function show(int $id): JsonResponse
    {
        $shape = GradeShape::with('rawMaterial', 'employee', 'shape')->findOrFail($id);
        return response()->json($shape);
    }

    public function materialInfo($id)
    {
        $rm = RawMaterial::findOrFail($id);
        $last = GradeShape::where('rms_id', $id)->latest()->first();

        return response()->json([
            'berat_sisa' => $rm->berat_sisa_shape,
            'last_date' => $last?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'berat' => 'required|array',
        ]);

        $grade = GradeShape::findOrFail($id);
        $rm = RawMaterial::findOrFail($validated['rms_id']);

        $shapeId = array_key_first($validated['berat']);
        $beratBaru = $validated['berat'][$shapeId];

        $berat_sisa = $rm->berat_sisa_shape + $grade->berat;

        if ($beratBaru > $berat_sisa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa',
            ], 422);
        }

        $grade->update([
            'rms_id' => $validated['rms_id'],
            'employees_id' => $validated['employees_id'],
            'tanggal' => $validated['tanggal'],
            'shapes_id' => $shapeId,
            'berat' => $beratBaru,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $grade,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $grade = GradeShape::findOrFail($id);
        $grade->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        GradeShape::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
