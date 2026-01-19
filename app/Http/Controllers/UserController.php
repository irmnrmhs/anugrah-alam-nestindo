<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Exports\UsersExport;

class UserController extends Controller
{
    public string $obj = 'User';
    public function index(): View
    {
        $users = User::with('role', 'employee')->latest()->get();
        $roles = Role::all();
        $employees = Employee::where('status', 1)->get();

        return view('masters.user', compact('users', 'roles', 'employees'));
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'username' => [
                'required',
                'string',
                'min:3',
                'max:30',
                'unique:users,username',
                'regex:/^[a-zA-Z0-9._]+$/'
            ],
            'email' => 'nullable|email',
            'password' => 'required',
            'roles_id' => 'required|exists:roles,id',
            'employees_id' => 'required|exists:employees,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $user = User::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil ditambahkan',
            'data' => $user,
        ]);
    }

    public function show(int $id): JsonResponse
    {
        $user = User::with('role', 'employee')->findOrFail($id);
        return response()->json($user);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'username' => [
            'required',
            'string',
            'min:3',
            'max:30',
            'unique:users,username,' . $id,
            'regex:/^[a-zA-Z0-9._]+$/'
        ],
            'email' => 'nullable|email',
            'password' => 'nullable',
            'roles_id' => 'required|exists:roles,id',
            'employees_id' => 'required|exists:employees,id',
        ]);

        $user = User::findOrFail($id);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diperbarui',
            'data' => $user,
        ]);
    }

    public function destroy(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil dihapus',
        ]);
    }

    public function deleteMultiple(Request $request): JsonResponse
    {
        $ids = $request->ids;

        User::whereIn('id', $ids)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data terpilih berhasil dihapus'
        ]);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $import = new UsersImport();
        Excel::import($import, $request->file('file'));

        return response()->json([
            'status' => 'success',
            'message' => $this->obj . ' berhasil diimport',
        ]);
    }

    public function downloadTemplate()
    {
        return Excel::download(
            new UsersExport,
            'template_import_user.xlsx'
        );
    }
}
