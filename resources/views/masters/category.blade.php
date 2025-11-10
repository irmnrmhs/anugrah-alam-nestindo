@extends('layouts.form')

@php
    $title = 'Kelola Kategori';
    $singular = 'Kategori';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Kategori</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($categories as $index => $category)
        <tr data-id="{{ $category->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $category->kode }}</td>
            <td>{{ $category->kategori }}</td>
            <td>{{ $category->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <input type="text" id="kategori" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/categories/${id}` : '/categories';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        kategori: $('#kategori').val(),
        keterangan: $('#keterangan').val()
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
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/categories/${id}`)
            .then(r => r.json())
            .then(category => {
                $('#item_id').val(category.id);
                $('#kode').val(category.kode);
                $('#kategori').val(category.kategori);
                $('#keterangan').val(category.keterangan);
                $('#modalTitle').text('Edit Kategori');
                new bootstrap.Modal('#crudModal').show();
            });
    });

    $(document).on('click', '.btnDelete', function() {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/categories/${id}`, {
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
                });
            }
        });
    });
@stop
