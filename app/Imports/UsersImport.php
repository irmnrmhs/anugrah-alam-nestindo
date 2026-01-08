<?php

namespace App\Imports;

use App\Models\Role;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $rows)
    {
        foreach($rows as $row) {
            $role = Role::where('nama', $row['role'])->first();
            $employee = Employee::where('nama', $row['karyawan'])->first();

            if(!$role){
                continue;
            }

            if(!$employee){
                continue;
            }

            if(User::where('username', $row['username'])->exists()){
                continue;
            }

            User::create([
                'username'      => $row['username'],
                'email'         => $row['email'],
                'password'      => $row['password'],
                'roles_id'      => $role->id,
                'employees_id'  => $employee->id,
            ]);
        }
    }
}
