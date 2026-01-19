@extends('layouts.form')

@php
    $title = 'Kelola Pengguna';
    $singular = 'User';
    $deleteMultipleUrl = '/users/delete-multiple';
    // $importUrl = route('users.import');
    $templateUrl = route('users.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Role</th>
    <th>Karyawan</th>
    <th>Username</th>
@stop

@section('table-body')
    @foreach($users as $index => $user)
        <tr data-id="{{ $user->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $user->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $user->role->name }}</td>
            <td>{{ $user->employee->nama }}</td>
            <td>{{ $user->username }}</td>
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
                <option value="{{ $employee->id }}">{{ $employee->nama }}({{ $employee->nip }})</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Username</label>
        <input type="text"
           id="username"
           class="form-control"
           required
           pattern="^[a-zA-Z0-9._]{3,30}$"
           oninvalid="this.setCustomValidity('Wajib diisi. Minimal 3 huruf, tidak boleh mengandung spasi. Hanya titik dan underscore yang diperbolehkan.')"
           oninput="this.setCustomValidity('')">
    </div>

    <div class="mb-3">
        <label>Password</label>
        <input type="password" id="password" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/users/${id}` : '/users';
    const method = id ? 'PUT' : 'POST';
    $('#password').removeAttr('placeholder');

    const data = {
        _token: '{{ csrf_token() }}',
        roles_id: $('#roles_id').val(),
        employees_id: $('#employees_id').val(),
        username: $('#username').val(),
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
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap dan username tidak duplikat.', 'error'));
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
                $('#password').val('');
                $('#password').attr('placeholder', 'Kosongkan jika tidak diubah');
                $('#modalTitle').text('Edit User');
                new bootstrap.Modal('#crudModal').show();
            });
    });

    $(document).on('click', '.btnDelete', function() {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Data tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
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
                })
                .catch(() => Swal.fire('Error', 'Gagal menghapus data. Pastikan data tidak terintegrasi dengan data lainnya.', 'error'));
            }
        });
    });
@stop