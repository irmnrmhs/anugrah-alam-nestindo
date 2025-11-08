@extends('adminlte::page')

@section('title', 'Kelola Departemen')

@section('content_header')
    <h1>Kelola Departemen</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Departemen</h3>
            <button class="btn btn-primary" id="btnAdd">Tambah Departemen</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="deptTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Departemen</th>
                        <th>Nama Departemen</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $index => $department)
                        <tr id="row-{{ $department->id }}">
                            <!-- <td>{{ $department->id }}</td> -->
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $department->kd_dept }}</td>
                            <td>{{ $department->nama_dept }}</td>
                            <td>{{ $department->deskripsi }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $department->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger btnDelete" data-id="{{ $department->id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="deptModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deptForm">
                    @csrf
                    <input type="hidden" id="dept_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah Department</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>Kode Department</label>
                            <input type="text" id="kd_dept" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama Department</label>
                            <input type="text" id="nama_dept" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Deskripsi</label>
                            <input type="text" id="deskripsi" class="form-control">
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
document.addEventListener("DOMContentLoaded", function(){   
    const deptModal = new bootstrap.Modal(document.getElementById('deptModal'));
    const form = document.getElementById('deptForm');
    const kdDeptInput = document.getElementById('kd_dept');
    const namaDeptInput = document.getElementById('nama_dept');
    const deskripsiInput = document.getElementById('deskripsi');
    const modalTitle = document.getElementById('modalTitle')
    const deptIdInput = document.getElementById('dept_id');
    const obj = 'Departemen';

    document.getElementById('btnAdd').addEventListener('click', ()=>{
        modalTitle.textContent = 'Tambah Departemen';
        form.reset();
        deptIdInput.value = '';
        deptModal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const id = deptIdInput.value;
        const url = id ? `/departments/${id}` : '/departments';
        // const method = id ? 'PUT' : 'POST';
        const data = {
            kd_dept: kdDeptInput.value,
            nama_dept: namaDeptInput.value,
            deskripsi: deskripsiInput.value,
            _method: id ? 'PUT' : 'POST',
            _token: '{{ csrf_token() }}'
        };

        fetch(url, {
            // method: method,
            method: 'POST',
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
        .catch(() => Swal.fire('Error', 'Gagal mengirim data', 'error'));
    });

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;

            fetch(`/departments/${id}`)
                .then(res => res.json())
                .then(department => {
                    modalTitle.textContent = 'Edit Departemen';
                    deptIdInput.value = department.id;
                    kdDeptInput.value = department.kd_dept;
                    namaDeptInput.value = department.nama_dept;
                    deskripsiInput.value = department.deskripsi;
                    deptModal.show();
                });
        });
    });

    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Apakah Anda Yakin?',
                text: 'Data ini akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/departments/${id}`, {
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