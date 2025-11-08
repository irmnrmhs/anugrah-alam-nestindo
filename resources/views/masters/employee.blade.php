@extends('adminlte::page')

@section('title', 'Kelola Data Karyawan')

@section('content_header')
    <h1>Kelola Data Karyawan</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">Daftar Karyawan</h3>
            <button class="btn btn-primary" id="btnAdd">Tambah Karyawan</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-striped" id="employeeTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Departemen</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($employees as $index => $employee)
                        <tr id="row-{{ $employee->id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $employee->nip }}</td>
                            <td>{{ $employee->nama }}</td>
                            <td>{{ $employee->department->nama_dept }}</td>
                            <td>
                                <button class="btn btn-sm btn-warning btnEdit" data-id="{{ $employee->id }}">Edit</button>
                                <button class="btn btn-sm btn-danger btnDelete" data-id="{{ $employee->id }}">Hapus</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="employeeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="employeeForm">
                    @csrf
                    <input type="hidden" id="employee_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label>NIP</label>
                            <input type="text" id="nip" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Nama</label>
                            <input type="text" id="nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label>Departemen</label>
                            <select id="dept_id" class="form-control" required>
                                <option value="">-- Pilih Departemen --</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama_dept }}</option>
                                @endforeach
                            </select>
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
    const employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
    const form = document.getElementById('employeeForm');
    const modalTitle = document.getElementById('modalTitle');
    const employeeIdInput = document.getElementById('employee_id');
    const nipInput = document.getElementById('nip');
    const namaInput = document.getElementById('nama');
    const deptInput = document.getElementById('dept_id');
    const obj = 'Karyawan';

    document.getElementById('btnAdd').addEventListener('click', () => {
        modalTitle.textContent = 'Tambah Data Karyawan';
        form.reset();
        employeeIdInput.value = '';
        employeeModal.show();
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        const id = employeeIdInput.value;
        const url = id ? `/employees/${id}` : '/employees';
        const method = id ? 'PUT' : 'POST';

        const data = {
            nip: nipInput.value,
            nama: namaInput.value,
            dept_id: deptInput.value,
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
        .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan NIP tidak ada yang sama', 'error'));
    });

    document.querySelectorAll('.btnEdit').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            fetch(`/employees/${id}`)
                .then(res => res.json())
                .then(employee => {
                    modalTitle.textContent = 'Edit Data Karyawan';
                    employeeIdInput.value = employee.id;
                    nipInput.value = employee.nip;
                    namaInput.value = employee.nama;
                    deptInput.value = employee.dept_id;
                    employeeModal.show();
                });
        });
    });

    document.querySelectorAll('.btnDelete').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Yakin?',
                text: 'Data user ini akan dihapus permanen',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    fetch(`/employees/${id}`, {
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
