@extends('adminlte::page')

@section('title', 'Kelola User')

@section('content_header')
    <h1>Kelola User</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar User</h3>
            <button class="btn btn-primary" id="btnAdd">Tambah User</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="userTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Role</th>
                        <th>Karyawan</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $index => $user)
                        <tr id="row-{{ $user->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->role->name }}</td>
                            <td>{{ $user->employee->nama }}</td>
                            <td>{{ $user->username }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $user->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger btnDelete" data-id="{{ $user->id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="userModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="userForm">
                    @csrf
                    <input type="hidden" id="users_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
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
                            <input type="password" id="password" class="form-control" placeholder="Isi jika ingin ubah password" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="btnSave">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const userModal = new bootstrap.Modal(document.getElementById('userModal'));
    const form = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const userIdInput = document.getElementById('users_id');
    const obj = 'User';

    document.getElementById('btnAdd').addEventListener('click', () => {
        modalTitle.textContent = 'Tambah User';
        form.reset();
        userIdInput.value = '';
        userModal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = userIdInput.value;
        const url = id ? `/users/${id}` : '/users';
        const method = id ? 'PUT' : 'POST';

        const data = {
            username: document.getElementById('username').value,
            email: document.getElementById('email').value,
            password: document.getElementById('password').value,
            roles_id: document.getElementById('roles_id').value,
            employees_id: document.getElementById('employees_id').value,
            _token: '{{ csrf_token() }}'
        };

        fetch(url, {
            method: method,
            headers: {'Content-Type': 'application/json'},
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Gagal', 'Terjadi kesalahan!', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Gagal menyimpan data', 'error'));
    });

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/users/${id}`)
                .then(res => res.json())
                .then(user => {
                    modalTitle.textContent = 'Edit User';
                    userIdInput.value = user.id;
                    document.getElementById('username').value = user.username;
                    document.getElementById('email').value = user.email;
                    document.getElementById('password').value = '';
                    document.getElementById('roles_id').value = user.roles_id;
                    document.getElementById('employees_id').value = user.employees_id;
                    userModal.show();
                });
        });
    });

    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/users/${id}`, {
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status === 'success') {
                            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', 'Tidak dapat menghapus ${obj}', 'error');
                        }
                    });
                }
            });
        });
    });
});
</script>
@stop
