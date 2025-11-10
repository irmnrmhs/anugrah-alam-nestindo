<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index()
    {
        $company = Company::first();
        return view('masters.company', compact('company'));
    }

    public function storeOrUpdate(Request $request)
    {
        $validated = $request->validate([
            'ikh'    => 'required|string|max:10',
            'nama'   => 'required|string|max:100',
            'alamat' => 'nullable|string|max:255',
            'telp'   => 'nullable|string|max:20',
            'fax'    => 'nullable|string|max:20',
            'negara' => 'nullable|string|max:50',
        ]);

        $company = Company::first();

        if ($company) {
            $company->update($validated);
            $message = 'Profil perusahaan berhasil diperbarui.';
        } else {
            $company = Company::create($validated);
            $message = 'Profil perusahaan berhasil disimpan.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $company
        ]);
    }
}
