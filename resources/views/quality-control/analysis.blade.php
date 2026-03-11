@extends('layouts.form')

@php
    $title = 'Kelola Analisis';
    $singular = 'Analisis';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Item</th>
    <th>Standar</th>
@stop

@section('table-body')
    @foreach($analys as $index => $i)
        <tr data-id="{{ $i->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $i->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $i->item }}</td>
            <td>{{ $i->standard }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Item</label>
        <input type="text" id="item" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Standar</label>
        <input type="text" id="standard" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/analysis/${id}` : '/analysis';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        item: $('#item').val(),
        standard: $('#standard').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/analysis/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#item').val(data.item);
                $('#standard').val(data.standard);
                $('#modalTitle').text('Edit Analisis');
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
                fetch(`/analysis/${id}`, {
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
