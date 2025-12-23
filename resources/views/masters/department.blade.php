@extends('layouts.form')

@php
    $title = 'Kelola Departemen';
    $singular = 'Departemen';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Departemen</th>
    <th>Nama Departemen</th>
    <th>Deskripsi</th>
@stop

@section('table-body')
    @foreach($departments as $index => $department)
        <tr data-id="{{ $department->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $department->kd_dept }}</td>
            <td>{{ $department->nama_dept }}</td>
            <td>{{ empty($department->deskripsi) ? '-' : $department->deskripsi }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode Departemen</label>
        <input type="text" id="kd_dept" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama Departemen</label>
        <input type="text" id="nama_dept" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Deskripsi</label>
        <input type="text" id="deskripsi" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/departments/${id}` : '/departments';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kd_dept: $('#kd_dept').val(),
        nama_dept: $('#nama_dept').val(),
        deskripsi: $('#deskripsi').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode dan nama departemen tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/departments/${id}`)
            .then(r => r.json())
            .then(department => {
                $('#item_id').val(department.id);
                $('#kd_dept').val(department.kd_dept);
                $('#nama_dept').val(department.nama_dept);
                $('#deskripsi').val(department.deskripsi);
                $('#modalTitle').text('Edit Departemen');
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
                fetch(`/departments/${id}`, {
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
