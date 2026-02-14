<?php

namespace App\Http\Controllers;

use App\Models\GradeColor;
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
        $employees = Employee::where('status',1)->get();
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
            'data' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->data as $featherId => $colors) {
                foreach ($colors as $colorId => $values) {
                    $berat = $values['berat'] ?? 0;
                    $biji  = $values['biji'] ?? 0;
                    if ($berat <= 0 && $biji <= 0) continue;

                    GradeColor::create([
                        'rms_id' => $request->rms_id,
                        'employees_id' => $request->employees_id,
                        'feathers_id' => $featherId,
                        'colors_id' => $colorId,
                        'tanggal' => $request->tanggal,
                        'other' => $request->other,
                        'berat' => $berat,
                        'biji' => $biji,
                    ]);
                }
            }

            DB::commit();
            return response()->json(['status'=>'success','message'=>'Grading warna berhasil disimpan']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status'=>'error','message'=>$e->getMessage()],422);
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

    public function update(Request $request,int $id): JsonResponse
    {
        $validated = $request->validate([
            'rms_id' => 'required|exists:raw_materials,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'berat' => 'required|array',
            'biji' => 'required|array',
        ]);

        $grade = GradeColor::findOrFail($id);
        $rm = RawMaterial::findOrFail($validated['rms_id']);

        $colorId = array_key_first($validated['berat']);
        $beratBaru = $validated['berat'][$colorId];
        $bijiBaru = $validated['biji'][$colorId];

        $berat_sisa = $rm->berat_sisa_color + $grade->berat;
        $biji_sisa  = $rm->biji_sisa_color + $grade->biji;

        if($beratBaru > $berat_sisa || $bijiBaru > $biji_sisa){
            return response()->json(['status'=>'error','message'=>'Melebihi stok sisa'],422);
        }

        $grade->update([
            'rms_id' => $validated['rms_id'],
            'employees_id' => $validated['employees_id'],
            'tanggal' => $validated['tanggal'],
            'colors_id' => $colorId,
            'berat' => $beratBaru,
            'biji' => $bijiBaru,
        ]);

        return response()->json(['status'=>'success','message'=>$this->obj.' berhasil diperbarui','data'=>$grade]);
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

        // --- Mapping grouping by tanggal & petugas ---
        $grouped = $colors->groupBy(function($item){
            return $item->tanggal.'-'.$item->employees_id;
        });

        $exportData = [];
        foreach($grouped as $key => $items){
            $first = $items->first();

            // mapping by feather + color
            $dataMap = $items->mapWithKeys(function($item){
                return [$item->feather->kode.'-'.$item->color->kode => $item];
            });

            $exportData[] = [
                'tanggal' => $first->tanggal,
                'employee' => $first->employee,
                'arrival' => $rm->arrivals->first(),
                'rm' => $rm,
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
}