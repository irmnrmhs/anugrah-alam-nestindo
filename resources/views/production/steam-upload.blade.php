@extends('layouts.form')

@php
    $title = 'Kelola Dokumen Steaming';
    $singular = 'Dokumen Steaming';
    $deleteMultipleUrl = '/usteams/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Batch Produk</th>
    <th>Nama File</th>
    <th>Link</th>
@stop

@section('table-body')
    @foreach($uploads as $index => $upload)
        <tr data-id="{{ $upload->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $upload->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $upload->fproduct->batch }}</td>
            <td>{{ $upload->file }}</td>
            <td>Unduh</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Batch Produk</label>
        <select id="fproducts_id" class="form-control" required>
            <option value="">-- Pilih Batch Produk --</option>
            @foreach($fproducts as $fproduct)
                <option value="{{ $fproduct->id }}">{{ $fproduct->batch }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama File</label>
        <input type="text" id="file" class="form-control" required>
    </div>
@stop

@section('form-submit-script')  
    const id = $('#item_id').val();
    const url = id ? `/usteams/${id}` : '/usteams';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        products_id: $('#products_id').val(),
        file: $('#file').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/usteams/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#fproducts_id').val(data.fproducts_id);
                $('#file').val(data.file);
                $('#modalTitle').text('Edit Dokumen Steaming');
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
                fetch(`/usteams/${id}`, {
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
