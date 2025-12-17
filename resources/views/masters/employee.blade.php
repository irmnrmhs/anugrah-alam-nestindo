@extends('layouts.form')

@php
    $title = 'Kelola Data Karyawan';
    $singular = 'Karyawan';
    $deleteMultipleUrl = '/employees/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>NIP</th>
    <th>Nama</th>
    <th>Departemen</th>
@stop

@section('table-body')
    @foreach($employees as $index => $employee)
        <tr data-id="{{ $employee->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $employee->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $employee->nip }}</td>
            <td>{{ $employee->nama }}</td>
            <td>{{ $employee->department->nama_dept }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
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
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/employees/${id}` : '/employees';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        nip: $('#nip').val(),
        nama: $('#nama').val(),
        dept_id: $('#dept_id').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan NIP tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/employees/${id}`)
            .then(r => r.json())
            .then(employee => {
                $('#item_id').val(employee.id);
                $('#nip').val(employee.nip);
                $('#nama').val(employee.nama);
                $('#dept_id').val(employee.dept_id);
                $('#modalTitle').text('Edit Karyawan');
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
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/employees/${id}`, {
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
