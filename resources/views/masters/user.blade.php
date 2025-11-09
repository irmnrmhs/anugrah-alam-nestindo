@extends('layouts.form')

@php
    $title = 'Kelola User';
    $singular = 'User';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Role</th>
    <th>Karyawan</th>
    <th>Username</th>
    <th>Email</th>
@stop

@section('table-body')
    @foreach($users as $index => $user)
        <tr data-id="{{ $user->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->role->name }}</td>
            <td>{{ $user->employee->nama }}</td>
            <td>{{ $user->username }}</td>
            <td>{{ $user->email }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Role</label>
        <select id="roles_id" class="form-control" required>
            <option value="">-- Pilih Role --</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Karyawan</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Karyawan --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text" id="username" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Email</label>
        <input type="email" id="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" id="password" class="form-control" placeholder="Isi jika ingin ubah password">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/users/${id}` : '/users';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        roles_id: $('#roles_id').val(),
        employees_id: $('#employees_id').val(),
        username: $('#username').val(),
        email: $('#email').val(),
        password: $('#password').val()
    };

    fetch(url, {
        method: method,
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
        }
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/users/${id}`)
            .then(r => r.json())
            .then(user => {
                $('#item_id').val(user.id);
                $('#roles_id').val(user.roles_id);
                $('#employees_id').val(user.employees_id);
                $('#username').val(user.username);
                $('#email').val(user.email);
                $('#password').val('');
                $('#modalTitle').text('Edit User');
                new bootstrap.Modal('#crudModal').show();
            });
    });

    $(document).on('click', '.btnDelete', function() {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/users/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success').then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message || 'Tidak bisa menghapus data', 'error');
                    }
                });
            }
        });
    });
@stop