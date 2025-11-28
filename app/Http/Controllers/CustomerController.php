<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $customers = Customer::latest()->get();
        $countries = [
            'Indonesia', 'China', 'Malaysia', 'Singapura', 'Thailand', 'Jepang', 'Taiwan'
        ];

        return view('masters.customer', compact('customers', 'countries'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:25|unique:customers,kode',
            'nama'      => 'required|string|max:25|unique:customers,kode',
            'alamat'    => 'nullable',
            'no_telp'   => 'nullable',
            'fax'       => 'nullable',
            'negara'    => 'required',
        ]);

        $customer = Customer::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Customer berhasil ditambahkan.',
            'data' => $customer,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        return response()->json($customer);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'kode'      => 'required|string|max:25|unique:customers,kode,' . $id,
            'nama'      => 'required|string|max:25|unique:customers,kode,' . $id,
            'alamat'    => 'nullable',
            'no_telp'   => 'nullable',
            'fax'       => 'nullable',
            'negara'   => 'required',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->update($validated);

        return response()->json([
                'status' => 'success',
                'message' => 'Customer berhasil diperbaharui.',
                'data' => $customer,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Customer berhasil dihapus.',
        ]);
    }
}