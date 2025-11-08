@extends('adminlte::page')

@section('title', 'Kelola Role')

@section('content_header')
    <h1>Kelola Role</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Role</h3>
            <button class="btn btn-primary" id="btnAdd">Tambah Role</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="roleTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Role</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $index => $role)
                        <tr id="row-{{ $role->id }}">
                             <td>{{ $index + 1 }}</td>
                            <td>{{ $role->name }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $role->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger btnDelete" data-id="{{ $role->id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="roleModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="roleForm">
                    @csrf
                    <input type="hidden" id="role_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Role</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Nama Role</label>
                            <input type="text" id="name" class="form-control" required>
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
    const roleModal = new bootstrap.Modal(document.getElementById('roleModal'));
    const form = document.getElementById('roleForm');
    const nameInput = document.getElementById('name');
    const roleIdInput = document.getElementById('role_id');
    const modalTitle = document.getElementById('modalTitle');
    const obj = 'Role';

    // Tambah
    document.getElementById('btnAdd').addEventListener('click', () => {
        modalTitle.textContent = 'Tambah Role';
        form.reset();
        roleIdInput.value = '';
        roleModal.show();
    });

    // Submit form
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const id = roleIdInput.value;
        const url = id ? `/roles/${id}` : '/roles';
        const method = id ? 'PUT' : 'POST';
        const data = {
            name: nameInput.value,
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

    // Edit
    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            fetch(`/roles/${id}`)
                .then(res => res.json())
                .then(role => {
                    modalTitle.textContent = 'Edit Role';
                    roleIdInput.value = role.id;
                    nameInput.value = role.name;
                    roleModal.show();
                });
        });
    });

    // Delete
    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: 'Data ini akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/roles/${id}`, {
                        method: 'DELETE',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'}
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.status === 'success') {
                            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
                        } else {
                            Swal.fire('Gagal', 'Tidak dapat menghapus data ${obj}', 'error');
                        }
                    });
                }
            });
        });
    });
});
</script>
@stop
