<?php

namespace App\Http\Controllers;

use App\Models\FpGrade;
use App\Models\History;
use App\Models\Product;
use App\Models\Document;
use App\Models\Employee;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;

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
            'tgl_selesai' => 'nullable|date'
        ]);

        $tracker = History::with('identifier')->find($validated['histories_id']);

        // Kode Grade
        $grade = FpGrade::find($validated['grades_id']);
        $pi = preg_replace('/[^A-Za-z0-9]/', '', $tracker->identifier->kode);
        $validated['kode'] = $grade->kode . "-" . $pi;

        // Kode Proses
        $kd_reg = History::with('identifier.rawMaterial.arrivals.dcertificate.wbhouse')->find($validated['histories_id']);
        $tgl = $validated['tgl_mulai'];
        $format_tgl = date('dmy', strtotime($tgl));

        $validated['kd_proses'] = $grade->kode . $kd_reg->kode . '-' . $format_tgl;

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
            'biji_sisa' => $tracker->sisa_biji_produk,
            'berat_sisa' => $tracker->sisa_berat_produk,
            'last' => $last?->tgl_mulai,
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'histories_id' => 'required|exists:histories,id',
            'employees_id' => 'required|exists:employees,id',
            'grades_id' => 'required|exists:fp_grades,id',
            'kd_proses' => 'required',
            'tgl_mulai' => 'required|date',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'tgl_selesai' => 'nullable|date'
        ]);

        $product = Product::findOrFail($id);
        $tracker = History::with('identifier')->find($validated['histories_id']);

        // Kode Grade
        $grade = FpGrade::find($validated['grades_id']);
        $pi = preg_replace('/[^A-Za-z0-9]/', '', $tracker->identifier->kode);
        $validated['kode'] = $grade->kode . "-" . $pi;

        $biji_sisa = $tracker->sisa_biji_produk + $product->biji;
        $berat_sisa = $tracker->sisa_berat_produk + $product->berat;

        // Kode Proses
        $kd_reg = History::with('identifier.rawMaterial.arrivals.dcertificate.wbhouse')->find($validated['histories_id']);
        $tgl = $validated['tgl_mulai'];
        $format_tgl = date('dmy', strtotime($tgl));

        $validated['kd_proses'] = $grade . $kd_reg . '-' . $format_tgl;

        if(
            $validated['biji'] > $biji_sisa ||
            $validated['berat'] > $berat_sisa   
        ){
            return response()->json([
                'status' => 'error',
                'message' => 'Melebihi stok sisa pada tahapan sebelumnya',
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

    public function export($id)
    {
        $products = Product::with(['employee', 'history'])
            ->findOrFail($id);

        $document = Document::with([
            'employee',
            'department'
        ])
        ->where('kode', 'PR11GP')
        ->firstOrFail();

        $pdf = Pdf::loadView('exports.product-form', compact('products', 'document'))
                ->setPaper('A4', 'landscape');

        $filename = 'Grading Produk Jadi.pdf';

        return $pdf->stream($filename);
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
