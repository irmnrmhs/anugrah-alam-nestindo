<?php

namespace App\Http\Controllers;

use App\Models\GradeColor;
use App\Models\GradeFeather;
use App\Models\RawMaterial;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Feather;
use App\Models\Color;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\GradeColorExport;

class GradeColorController extends Controller
{
    public string $obj = 'Grading Warna';

    public function index(): View
    {
        $colors = GradeColor::with('rawMaterial', 'employee', 'feather', 'color')->latest()->get();
        $rms = RawMaterial::all();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'Produksi');
            })->get();
        $featherList = Feather::all();
        $colorList = Color::all();

        return view('raw-material.grade-color', compact('colors','rms','employees','featherList','colorList'));
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'data' => 'nullable|array',
            'hancuran' => 'nullable|numeric|min:0',
        ]);

        $rm = RawMaterial::findOrFail($request->rms_id);

        DB::beginTransaction();

        try {

            $totalBerat = 0;
            $totalBiji  = 0;

            // Hitung total dr data normal
            if (!empty($request->data)) {
                foreach ($request->data as $featherId => $colors) {
                    foreach ($colors as $colorId => $values) {

                        $berat = (float) ($values['berat'] ?? 0);
                        $biji  = (int) ($values['biji'] ?? 0);

                        if ($berat <= 0 && $biji <= 0) {
                            continue;
                        }

                        $totalBerat += $berat;
                        $totalBiji  += $biji;
                    }
                }
            }

            // tambahkan hancuran ke total
            $hancuran = (float) ($request->hancuran ?? 0);

            if ($hancuran > 0) {
                $totalBerat += $hancuran;
            }

            // validasi stok
            if ($totalBerat > $rm->berat_sisa_color) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total berat melebihi stok sisa'
                ], 422);
            }

            if ($totalBiji > $rm->biji_sisa_color) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total biji melebihi stok sisa'
                ], 422);
            }

            // simpan data normal
            if (!empty($request->data)) {
                foreach ($request->data as $featherId => $colors) {
                    foreach ($colors as $colorId => $values) {

                        $berat = (float) ($values['berat'] ?? 0);
                        $biji  = (int) ($values['biji'] ?? 0);

                        if ($berat <= 0 && $biji <= 0) {
                            continue;
                        }

                        $feather = Feather::find($featherId);
                        $color   = Color::find($colorId);

                        if (!$feather || !$color) {
                            throw new \Exception('Data feather atau warna tidak ditemukan');
                        }

                        $grade = strtoupper($feather->kode . '-' . $color->kode);

                        $exists = GradeColor::where('rms_id', $request->rms_id)
                            ->where('grade', $grade)
                            ->exists();

                        if ($exists) {
                            throw new \Exception("Grade {$grade} sudah ada");
                        }

                        GradeColor::create([
                            'rms_id' => $request->rms_id,
                            'employees_id' => $request->employees_id,
                            'feathers_id' => $featherId,
                            'colors_id' => $colorId,
                            'tanggal' => $request->tanggal,
                            'grade' => $grade,
                            'berat' => $berat,
                            'biji' => $biji,
                            'other' => null,
                        ]);
                    }
                }
            }

            // simpan hancuran
            if ($hancuran > 0) {

                $existsHancuran = GradeColor::where('rms_id', $request->rms_id)
                    ->where('grade', 'HANCURAN')
                    ->whereDate('tanggal', $request->tanggal)
                    ->exists();

                if ($existsHancuran) {
                    throw new \Exception('Data HANCURAN pada tanggal ini sudah ada');
                }

                GradeColor::create([
                    'rms_id' => $request->rms_id,
                    'employees_id' => $request->employees_id,
                    'feathers_id' => null,
                    'colors_id' => null,
                    'tanggal' => $request->tanggal,
                    'grade' => 'HANCURAN',
                    'berat' => $hancuran,
                    'biji' => 0,
                    'other' => null,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => 'Grading warna berhasil disimpan'
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
        $color = GradeColor::with('rawMaterial','employee','color','feather')->findOrFail($id);
        return response()->json($color);
    }

    public function materialInfo($id)
    {
        $rm = RawMaterial::findOrFail($id);
        $last = GradeColor::where('rms_id',$id)->latest()->first();

        return response()->json([
            'berat_sisa' => $rm->berat_sisa_color,
            'biji_sisa' => $rm->biji_sisa_color,
            'last_date' => $last?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'data' => 'required|array',
        ]);

        $grade = GradeColor::findOrFail($id);
        $rm = RawMaterial::findOrFail($validated['rms_id']);

        DB::beginTransaction();

        try {

            $totalBeratBaru = 0;
            $totalBijiBaru  = 0;

            $featherIdBaru = null;
            $colorIdBaru   = null;

            foreach ($validated['data'] as $featherId => $colors) {
                foreach ($colors as $colorId => $values) {

                    $berat = $values['berat'] ?? 0;
                    $biji  = $values['biji'] ?? 0;

                    if ($berat <= 0 && $biji <= 0) continue;

                    $totalBeratBaru += $berat;
                    $totalBijiBaru  += $biji;

                    $featherIdBaru = $featherId;
                    $colorIdBaru   = $colorId;
                }
            }

            $stokBeratTersedia = $rm->berat_sisa_color + $grade->berat;
            $stokBijiTersedia  = $rm->biji_sisa_color + $grade->biji;

            if ($totalBeratBaru > $stokBeratTersedia) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total berat melebihi stok sisa'
                ], 422);
            }

            if ($totalBijiBaru > $stokBijiTersedia) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Total biji melebihi stok sisa'
                ], 422);
            }

            $feather = Feather::find($featherIdBaru);
            $color   = Color::find($colorIdBaru);

            $gradeText = strtoupper($feather->kode . '-' . $color->kode);

            $grade->update([
                'rms_id' => $validated['rms_id'],
                'employees_id' => $validated['employees_id'],
                'tanggal' => $validated['tanggal'],
                'feathers_id' => $featherIdBaru,
                'colors_id' => $colorIdBaru,
                'grade' => $gradeText,
                'berat' => $totalBeratBaru,
                'biji' => $totalBijiBaru,
            ]);

            DB::commit();

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil diperbarui',
                'data' => $grade
            ]);

        } catch (\Exception $e) {

            DB::rollBack();

            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 422);
        }
    }

    public function destroy(int $id): JsonResponse
    {
        $grade = GradeColor::findOrFail($id);
        $grade->delete();

        return response()->json(['status'=>'success','message'=>$this->obj.' berhasil dihapus']);
    }

    public function export(Request $request, $id)
    {
        $type = $request->get('type','pdf');

        $rm = RawMaterial::with(['arrivals.dcertificate.wbhouse','colors.employee','colors.feather','colors.color'])->findOrFail($id);

        $colors = $rm->colors()->with('employee','feather','color')->get();

        $grouped = $colors->groupBy(function($item){
            return $item->tanggal
                .'-'.$item->employees_id
                .'-'.$item->feathers_id;
        });

        $exportData = [];

        foreach($grouped as $items){

            $first = $items->first();

            $dataMap = $items->mapWithKeys(function($item){
                return [strtoupper($item->color->kode) => $item];
            });

            $exportData[] = [
                'tanggal' => $first->tanggal,
                'employee' => $first->employee,
                'arrival' => $rm->arrivals->first(),
                'rm' => $rm,
                'feather' => $first->feather,
                'data' => $dataMap
            ];
        }

        if($type==='excel'){
            $filename = 'Grading Warna - '.str_replace(['/','\\'],'-',$rm->kode).'.xlsx';
            return Excel::download(new GradeColorExport($exportData),$filename);
        }

        $document = Document::with(['employee','department'])->where('kode','PR01GB')->firstOrFail();

        $pdf = Pdf::loadView('exports.forms.gcolor-form',compact('exportData','document'))->setPaper('A4','landscape');

        $filename = 'Grading Warna - '.str_replace(['/','\\'],'-',$rm->kode).'.pdf';
        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;
        GradeColor::whereIn('id',$ids)->delete();

        return response()->json(['status'=>'success','message'=>'Data terpilih berhasil dihapus']);
    }

    public function getFeathersByRms($rms_id)
    {
        $feathers = GradeFeather::where('rms_id', $rms_id)
            ->with('feather')
            ->get()
            ->pluck('feather')
            ->unique('id')
            ->values();

        return response()->json($feathers);
    }
}