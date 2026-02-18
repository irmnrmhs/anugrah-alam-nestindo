<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Container;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ContainerExport;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class ContainerController extends Controller
{
    public string $obj = 'Kontainer';

    public function index(): View
    {
        $containers = Container::with(['arrival', 'employee'])->oldest()->get();
        $arrivals  = Arrival::orderBy('kode')->get()->unique('kode')->values();
        $employees = Employee::where('status', 1)->get();

        return view('raw-material.container', compact('containers', 'arrivals', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'arrivals_id'  => 'required|exists:arrivals,id',
            'employees_id' => 'required|exists:employees,id',
            'biji'         => 'required|integer|min:0',
            'berat'        => 'required|numeric|min:0|max:99999.99',
            'keterangan'   => 'nullable|string',
        ]);

        $container = Container::create($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kontainer berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $container = Container::with(['arrival', 'employee'])
            ->findOrFail($id);

        return response()->json($container);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'arrivals_id'  => 'required|exists:arrivals,id',
            'employees_id' => 'required|exists:employees,id',
            'biji'         => 'required|integer|min:0',
            'berat'        => 'required|numeric|min:0|max:99999.99',
            'keterangan'   => 'nullable|string',
        ]);

        $container = Container::findOrFail($id);

        $container->update($validated);

        return response()->json([
            'status'  => 'success',
            'message' => 'Kontainer berhasil diperbarui',
            'data'    => $container,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $container = Container::findOrFail($id);

        $container->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Kontainer berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items'                   => 'required|array|min:1',
            'items.*.arrivals_id'     => 'required|exists:arrivals,id',
            'items.*.employees_id'    => 'required|exists:employees,id',
            'items.*.biji'            => 'required|integer|min:0',
            'items.*.berat'           => 'required|numeric|min:0|max:99999.99',
            'items.*.keterangan'      => 'nullable|string',
        ]);

        DB::transaction(function () use ($validated) {
            foreach ($validated['items'] as $item) {
                Container::create($item);
            }
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Semua kontainer berhasil ditambahkan.',
        ]);
    }
    
    public function export(Request $request, Arrival $arrival)
    {
        $type = $request->get('type', 'pdf');

        $containers = $arrival->containers()
            ->with(['employee', 'arrival.dcertificate.wbhouse', 'arrival.rawMaterial'])
            ->orderBy('created_at')
            ->get();
        
        $first = $containers->first();
        $date = $first->arrival->tgl_kedatangan;

        if ($type === 'excel') {

            $filename = 'Kedatangan - ' . str_replace(['/', '\\'], '-', $arrival->kode) . '.xlsx';

            return Excel::download(
                new ContainerExport($containers),
                $filename
            );
        }

        $document = Document::where('kode', 'DBB058')->firstOrFail();

        $pdf = Pdf::loadView(
            'exports.forms.container-form',
            compact('containers', 'date', 'document')
        )->setPaper('A4', 'portrait');

        $filename = 'Kedatangan Bahan Baku -' .
            str_replace(['/', '\\'], '-', $arrival->kode) . '.pdf';

        return $pdf->stream($filename);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->validate([
            'ids'   => 'required|array|min:1',
            'ids.*' => 'exists:containers,id'
        ])['ids'];

        DB::transaction(function () use ($ids) {
            Container::whereIn('id', $ids)->each(function ($container) {
                $container->delete();
            });
        });

        return response()->json([
            'status'  => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
