<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\Employee;
use App\Models\Container;
use Illuminate\View\View;
use App\Models\RawMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContainerController extends Controller
{
    public string $obj = 'Kontainer';
    public function index(): View
    {
        $containers = Container::with('arrival', 'employee')->oldest()->get();
        $arrivals = Arrival::all();
        $employees = Employee::all();

        return view('raw-material.container', compact('containers', 'arrivals', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'arrivals_id' => 'required|exists:arrivals,id',
            'employees_id' => 'required|exists:employees,id',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable|string',
        ]);

        // Buat kontainer
        $container = Container::create($validated);

        // Ambil kode arrival (karena kode = milik arrival)
        $arrival = Arrival::find($validated['arrivals_id']);
        $kode = $arrival->kode;

        // Update RawMaterial
        $raw = RawMaterial::firstOrCreate(
            ['kode' => $kode],
            ['biji' => 0, 'berat' => 0]
        );

        $raw->biji += $validated['biji'];
        $raw->berat += $validated['berat'];
        $raw->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Kontainer berhasil ditambahkan',
            'data' => $container
        ]);
    }   

    public function show(int $id): JsonResponse
    {
        $container = Container::with('arrival', 'employee')->findOrFail($id);
        return response()->json($container);
    }


    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'arrivals_id' => 'required|exists:arrivals,id',
            'employees_id' => 'required|exists:employees,id',
            'biji' => 'required|integer|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable|string'
        ]);

        $container = Container::findOrFail($id);

        $arrivalOld = Arrival::find($container->arrivals_id);
        $arrivalNew = Arrival::find($validated['arrivals_id']);

        $kodeOld = $arrivalOld->kode;
        $kodeNew = $arrivalNew->kode;

        $selisihBiji = $validated['biji'] - $container->biji;
        $selisihBerat = $validated['berat'] - $container->berat;

        if ($kodeOld === $kodeNew) {
            $raw = RawMaterial::where('kode', $kodeOld)->first();
            if ($raw) {
                $raw->biji += $selisihBiji;
                $raw->berat += $selisihBerat;
                $raw->save();
            }
        } else {
            $rawOld = RawMaterial::where('kode', $kodeOld)->first();
            if ($rawOld) {
                $rawOld->biji -= $container->biji;
                $rawOld->berat -= $container->berat;
                $rawOld->save();
            }

            $rawNew = RawMaterial::firstOrCreate(
                ['kode' => $kodeNew],
                ['biji' => 0, 'berat' => 0]
            );

            $rawNew->biji += $validated['biji'];
            $rawNew->berat += $validated['berat'];
            $rawNew->save();
        }

        $container->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Kontainer berhasil diperbarui',
            'data' => $container,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $container = Container::findOrFail($id);

        $kode = $container->arrival->kode;

        $raw = RawMaterial::where('kode', $kode)->first();

        if ($raw) {
            $raw->biji -= $container->biji;
            $raw->berat -= $container->berat;
            $raw->save();
        }

        $container->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Kontainer berhasil dihapus',
        ]);
    }

    public function bulk(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'items' => 'required|array|min:1',
            'items.*.arrivals_id'   => 'required|exists:arrivals,id',
            'items.*.employees_id'  => 'required|exists:employees,id',
            'items.*.biji'          => 'required|integer|min:0',
            'items.*.berat'         => 'required|numeric|min:0|max:99999.99',
            'items.*.keterangan'    => 'nullable|string',
        ]);

        $items = $validated['items'];

        foreach ($items as $item) {

            // Insert container
            $container = Container::create($item);

            // Ambil kode arrival
            $arrival = Arrival::find($item['arrivals_id']);
            $kode = $arrival->kode;

            // Update raw material
            $raw = RawMaterial::firstOrCreate(
                ['kode' => $kode],
                ['biji' => 0, 'berat' => 0]
            );

            $raw->biji += $item['biji'];
            $raw->berat += $item['berat'];
            $raw->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Semua kontainer berhasil ditambahkan.',
        ]);
}

}
