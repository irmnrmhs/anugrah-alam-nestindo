@extends('layouts.form')

@php
    $title = 'Kelola Dokumen';
    $singular = 'Dokumen';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th>No</th>
    <th>Departemen</th>
    <th>Nomor Dokumen</th>
    <th>Nama Dokumen</th>
    <th>Nomor Revisi</th>
@stop

@section('table-body')
    @foreach($docs as $index => $document)
        <tr data-id="{{ $document->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $document->department->nama_dept }}</td>
            <td>{{ $document->no }}</td>
            <td>{{ $document->name }}</td>
            <td>{{ $document->rev }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Departemen</label>
        <select id="depts_id" class="form-control" required>
            <option value="">-- Pilih Departemen --</option>
            @foreach($departments as $department)
                <option value="{{ $department->id }}">{{ $department->nama_dept }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nomor Dokumen</label>
        <input type="text" id="no" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama Dokumen</label>
        <input type="text" id="name" class="form-control">
    </div>
    <div class="mb-3">
        <label>Nomor Revisi</label>
        <input type="text" id="rev" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/documents/${id}` : '/documents';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        depts_id: $('#depts_id').val(),
        no: $('#no').val(),
        name: $('#name').val(),
        rev: $('#rev').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
    if (res.status === 'success') {
        Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
    } else {
        Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/documents/${id}`)
            .then(r => r.json())
            .then(document => {
                $('#item_id').val(document.id);
                $('#depts_id').val(document.depts_id);
                $('#no').val(document.no);
                $('#name').val(document.name);
                $('#rev').val(document.rev);
                $('#modalTitle').text('Edit Dokumen');
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
                fetch(`/documents/${id}`, {
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
