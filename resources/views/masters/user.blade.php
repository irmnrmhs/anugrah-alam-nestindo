@extends('adminlte::page')

@section('title', 'Kelola User')

@section('content_header')
    <h1>Kelola User</h1>
@stop

@section('content')
    <div class="card">
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

@section('css')
    {{-- DataTables + Buttons --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
@stop

@section('js')
    {{-- jQuery & DataTables --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>

    {{-- Buttons --}}
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    {{-- Bootstrap 5 & SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    $(document).ready(function() {
        // === DataTables Init ===
        const table = $('#userTable').DataTable({
            responsive: true,
            dom: '<"d-flex justify-content-between align-items-center mb-3"Bf>rt<"d-flex justify-content-between mt-3"lip>',
            buttons: [
                { extend: 'excelHtml5', text: 'Export Excel', className: 'btn btn-success btn-sm' },
                { extend: 'pdfHtml5', text: 'Export PDF', className: 'btn btn-danger btn-sm' },
                { extend: 'print', text: 'Print', className: 'btn btn-secondary btn-sm' },
                { extend: 'colvis', text: 'Kolom', className: 'btn btn-info btn-sm' }
            ],
            language: {
                search: "Cari:",
                lengthMenu: "Tampilkan _MENU_ data per halaman",
                zeroRecords: "Tidak ada data ditemukan",
                info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                infoEmpty: "Tidak ada data",
                infoFiltered: "(difilter dari _MAX_ total data)"
            }
        });

        // Tambahkan tombol "Tambah User" ke toolbar
        $('.dt-buttons').prepend(
            '<button class="btn btn-primary btn-sm me-2" id="btnAdd">Tambah User</button>'
        );

        // === CRUD ===
        const userModal = new bootstrap.Modal(document.getElementById('userModal'));
        const form = document.getElementById('userForm');
        const modalTitle = document.getElementById('modalTitle');
        const userIdInput = document.getElementById('users_id');
        const obj = 'User';

        // Tambah data
        $(document).on('click', '#btnAdd', function() {
            modalTitle.textContent = 'Tambah User';
            form.reset();
            userIdInput.value = '';
            userModal.show();
        });

        // Simpan data
        $('#userForm').on('submit', function(e) {
            e.preventDefault();
            const id = userIdInput.value;
            const url = id ? `/users/${id}` : '/users';
            const method = id ? 'PUT' : 'POST';

            const data = {
                username: $('#username').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                roles_id: $('#roles_id').val(),
                employees_id: $('#employees_id').val(),
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

        // Edit data
        $(document).on('click', '.btnEdit', function() {
            const id = $(this).data('id');
            fetch(`/users/${id}`)
                .then(res => res.json())
                .then(user => {
                    modalTitle.textContent = 'Edit User';
                    userIdInput.value = user.id;
                    $('#username').val(user.username);
                    $('#email').val(user.email);
                    $('#password').val('');
                    $('#roles_id').val(user.roles_id);
                    $('#employees_id').val(user.employees_id);
                    userModal.show();
                });
        });

        // Hapus data
        $(document).on('click', '.btnDelete', function() {
            const id = $(this).data('id');
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
                            Swal.fire('Gagal', 'Tidak dapat menghapus ' + obj, 'error');
                        }
                    });
                }
            });
        });
    });
    </script>
@stop
