<?php

namespace App\Http\Controllers;

use App\Exports\GradeFeatherExport;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Feather;
use App\Models\GradeFeather;
use App\Models\GradeShape;
use App\Models\RawMaterial;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class GradeFeatherController extends Controller
{
    public string $obj = 'Grading Bulu';

    public function index(): View
    {
        $feathers = GradeFeather::with('rawMaterial', 'employee', 'feather')
            ->latest()
            ->get();

        $rms = RawMaterial::all();
        $employees = Employee::where('status', 1)->get();
        $featherList = Feather::all();

        return view(
            'raw-material.grade-feather',
            compact('feathers', 'rms', 'employees', 'featherList')
        );
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            // 'feathers_id' => 'required|exists:feathers,id',
            'tanggal' => 'required|date',
            'biji' => 'required|array',
            'berat' => 'required|array',
        ]);

        $rm = RawMaterial::findOrFail($request->rms_id);

        DB::beginTransaction();

        try {

            $totalBerat = array_sum($request->berat);
            $totalBiji  = array_sum($request->biji);

            if ($totalBerat > $rm->berat_sisa_feather) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total berat melebihi stok sisa',
                ], 422);
            }

            if ($totalBiji > $rm->biji_sisa_feather) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total biji melebihi stok sisa',
                ], 422);
            }

            foreach ($request->berat as $featherId => $berat) {

                $biji = $request->biji[$featherId] ?? 0;

                if ($berat <= 0 || $biji <= 0) {
                    continue;
                }

                GradeFeather::create([
                    'rms_id' => $request->rms_id,
                    'employees_id' => $request->employees_id,
                    'feathers_id' => $featherId,
                    'tanggal' => $request->tanggal,
                    'berat' => $berat,
                    'biji' => $biji,
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
        $feather = GradeFeather::with(
            'rawMaterial',
            'employee',
            'feather'
        )->findOrFail($id);

        return response()->json($feather);
    }

    public function materialInfo($id)
    {
        $rm = RawMaterial::findOrFail($id);
        $last = GradeFeather::where('rms_id', $id)->latest()->first();

        return response()->json([
            'berat_sisa' => $rm->berat_sisa_feather,
            'biji_sisa' => $rm->biji_sisa_feather,
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
            'biji' => 'required|array',
        ]);

        $grade = GradeFeather::findOrFail($id);
        $rm = RawMaterial::findOrFail($validated['rms_id']);

        $totalBeratBaru = array_sum($validated['berat']);
        $totalBijiBaru  = array_sum($validated['biji']);

        $stokBeratTersedia = $rm->berat_sisa_feather + $grade->berat;
        $stokBijiTersedia  = $rm->biji_sisa_feather + $grade->biji;

        if ($totalBeratBaru > $stokBeratTersedia) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total berat melebihi stok sisa',
            ], 422);
        }

        if ($totalBijiBaru > $stokBijiTersedia) {
            return response()->json([
                'status' => 'error',
                'message' => 'Total biji melebihi stok sisa',
            ], 422);
        }

        foreach ($validated['berat'] as $featherId => $berat) {

            if ($berat <= 0 && ($validated['biji'][$featherId] ?? 0) <= 0) {
                continue;
            }

            $grade->update([
                'rms_id' => $validated['rms_id'],
                'employees_id' => $validated['employees_id'],
                'tanggal' => $validated['tanggal'],
                'feathers_id' => $featherId,
                'berat' => $berat,
                'biji' => $validated['biji'][$featherId] ?? 0,
            ]);
        }

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $grade,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $grade = GradeFeather::findOrFail($id);
        $grade->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function export(Request $request, $id)
    {
        $type = $request->get('type', 'pdf');

        $rm = RawMaterial::with([
            'arrivals.dcertificate.wbhouse',
        ])->findOrFail($id);

        $feathers = GradeFeather::with('employee', 'feather')
            ->where('rms_id', $id)
            ->orderBy('tanggal')
            ->get();

        $shapes = GradeShape::with('employee')
            ->where('rms_id', $id)
            ->orderBy('tanggal')
            ->get();

        if ($type === 'excel') {

            $filename = 'Grading Bulu - ' .
                str_replace(['/', '\\'], '-', $rm->kode) . '.xlsx';

            return Excel::download(
                new GradeFeatherExport($feathers),
                $filename
            );
        }

        $document = Document::with(['employee', 'department'])
            ->where('kode', 'PR01GB')
            ->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.forms.gfeather-form',
            compact(
                'rm',
                'feathers',
                'shapes',
                'document'
            )
        )->setPaper('A4', 'landscape');

        $filename = 'Grading Bulu - ' .
            str_replace(['/', '\\'], '-', $rm->kode) . '.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        GradeFeather::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}