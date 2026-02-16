<?php

namespace App\Http\Controllers;

use App\Models\Wash;
use App\Models\History;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class WashController extends Controller
{
    public string $obj = 'Pencucian';
    public function index(): View
    {
        $washes = Wash::with('history', 'employee')->latest()->get();
        $histories = History::where('tujuan', 'PR03PC')->get();
        $employees = Employee::with('position')->where('status', 1)->whereHas('position', function ($query) {
                $query->where('posisi', 'karyawan');
            })->get();

        return view('production.wash', compact('washes', 'histories', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji_in' => 'required|integer|min:0',
            'biji_out' => 'required|integer|min:0',
        ]);

        $tracker = History::find($validated['histories_id']);

        if(
            $validated['biji_in'] > $tracker->sisa_biji_cuci
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_out'] > $validated['biji_in']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Stok keluar melebihi stok masuk',
            ], 422);
        }

        $wash = Wash::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $wash,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $wash = Wash::findOrFail($id);
        return response()->json($wash);
    }

    public function info($id)
    {
        $tracker = History::findOrFail($id);
        $last = Wash::where('histories_id', $id)->latest()->first();

        return response()->json([
            'biji_sisa' => $tracker->sisa_biji_cuci,
            'last' => $last?->tanggal,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'tanggal' => 'required|date',
            'biji_in' => 'required|integer|min:0',
            'biji_out' => 'required|integer|min:0',
        ]);

        $wash = Wash::findOrFail($id);
        $tracker = History::find($validated['histories_id']);

        $masuk_sisa = $tracker->sisa_biji_cuci + $wash->biji_in;

        if(
            $validated['biji_in'] > $masuk_sisa
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
            ], 422);
        }

        if(
            $validated['biji_out'] > $validated['biji_in']
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Stok keluar melebihi stok masuk',
            ], 422);
        }
        
        $wash->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui'
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        try {
            $wash = Wash::findOrFail($id);
            $wash->delete();

            return response()->json([
                'status' => 'success',
                'message' => $this->obj . ' berhasil dihapus.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus data',
            ], 500);
        }
    }

    public function export($id)
    {
        $washes = Wash::with(['employee', 'history'])
            ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR03PC')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.wash-form', compact('washes', 'document'))
                ->setPaper('A4', 'landscape');

        $filename = 'Pencucian.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        try {
            foreach ($request->ids as $id) {
                Wash::findOrFail($id)->delete();
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Data terpilih berhasil dihapus'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 422);
        }
    }
}
