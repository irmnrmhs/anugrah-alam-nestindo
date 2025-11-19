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
            'biji' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        // Tambah kontainer
        $container = Container::create($validated);

        // Ambil kode arrival (Wajib pakai find untuk memastikan konsisten)
        $kode = Arrival::find($request->arrivals_id)->kode;

        // Ambil atau buat raw material untuk kode ini
        $raw = RawMaterial::where('kode', $kode)->first();

        if (!$raw) {
            // Jika belum ada, buat baru
            $raw = RawMaterial::create([
                'kode' => $kode,
                'biji' => $request->biji,
                'berat' => $request->berat,
            ]);
        } else {
            // Jika sudah ada, update total
            $raw->increment('biji', $request->biji);
            $raw->increment('berat', $request->berat);
        }

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $container,
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
            'biji' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $container = Container::findOrFail($id);

        // Ambil kode arrival lama & baru
        $arrivalOld = Arrival::find($container->arrivals_id);
        $arrivalNew = Arrival::find($request->arrivals_id);

        $kodeOld = $arrivalOld->kode;
        $kodeNew = $arrivalNew->kode;

        // Hitung selisih biji & berat
        $selisihBiji = $request->biji - $container->biji;
        $selisihBerat = $request->berat - $container->berat;

        // Jika kode sama → cukup update selisih
        if ($kodeOld === $kodeNew) {

            $raw = RawMaterial::where('kode', $kodeOld)->first();
            if ($raw) {
                $raw->biji += $selisihBiji;
                $raw->berat += $selisihBerat;
                $raw->save();
            }

        } else {
            // Kode berbeda → kurangi dari kode lama
            $rawOld = RawMaterial::where('kode', $kodeOld)->first();
            if ($rawOld) {
                $rawOld->biji -= $container->biji;
                $rawOld->berat -= $container->berat;
                $rawOld->save();
            }

            // Tambahkan ke kode baru
            $rawNew = RawMaterial::firstOrCreate(
                ['kode' => $kodeNew],
                ['biji' => 0, 'berat' => 0]
            );

            $rawNew->biji += $request->biji;
            $rawNew->berat += $request->berat;
            $rawNew->save();
        }

        // Update data kontainer
        $container->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $container,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $container = Container::findOrFail($id);

        // Ambil kode arrival untuk raw_material
        $kode = $container->arrival->kode;

        $raw = RawMaterial::where('kode', $kode)->first();

        if ($raw) {
            $raw->biji -= $container->biji;
            $raw->berat -= $container->berat;
            $raw->save();
        }

        // Hapus kontainer
        $container->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
