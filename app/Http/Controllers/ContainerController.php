<?php

namespace App\Http\Controllers;

use App\Models\Arrival;
use App\Models\Container;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
            'arrivals_id' => 'required|exists:arrivals,id|unique:containers,arrivals_id',
            'employees_id' => 'required|exists:employees,id',
            'biji' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $container = Container::create($validated);

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
            'arrivals_id' => 'required|exists:arrivals,id|unique:containers,arrivals_id,' . $id,
            'employees_id' => 'required|exists:employees,id',
            'biji' => 'required|numeric|min:0',
            'berat' => 'required|numeric|min:0|max:99999.99',
            'keterangan' => 'nullable'
        ]);

        $container = Container::findOrFail($id);

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
        $container->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }
}
