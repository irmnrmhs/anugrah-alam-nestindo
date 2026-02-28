<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Export;
use App\Models\Flight;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class ExportController extends Controller
{
    public string $obj = 'Ekspor';
    public function index(): View
    {
        $exports = Export::with('customer', 'flight')->latest()->get();
        $customers = Customer::all();
        $flights = Flight::all();

        return view('orders.export', compact('exports', 'customers', 'flights'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'inv' => 'required|unique:exports,inv',
            'customers_id' => 'required|exists:customers,id',
            'flights_id' => 'nullable|exists:flights,id',
            'contract_no' => 'required|unique:exports,contract_no',
            'date' => 'required|date',
            'by' => 'required',
        ]);

        Export::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $export = Export::findOrFail($id);
        return response()->json($export);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'inv' => 'required|unique:exports,inv,' .$id,
            'customers_id' => 'required|exists:customers,id',
            'flights_id' => 'nullable|exists:flights,id',
            'contract_no' => 'required|unique:exports,contract_no,' .$id,
            'date' => 'required|date',
            'by' => 'required',
        ]);

        $export = Export::findOrFail($id);

        $export->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $export,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $export = Export::findOrFail($id);
        $export->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        Export::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }
}
