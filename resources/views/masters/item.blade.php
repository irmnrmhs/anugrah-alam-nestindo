@extends('layouts.form')

@php
    $title = 'Kelola Data Item';
    $singular = 'Item';
    $deleteMultipleUrl = '/items/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Item</th>
    <th>Item (dalam tulisan Cina)</th>
@stop

@section('table-body')
    @foreach($items as $index => $item)
        <tr data-id="{{ $item->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->item }}</td>
            <td>{{ empty($item->item_cn) ? '-' : $item->item_cn }}</td>
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
        <label>Item (dalam tulisan Cina)</label>
        <input type="text" id="item_cn" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/items/${id}` : '/items';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        item: $('#item').val(),
        item_cn: $('#item_cn').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan Item tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/items/${id}`)
            .then(r => r.json())
            .then(item => {
                $('#item_id').val(item.id);
                $('#item').val(item.item);
                $('#item_cn').val(item.item_cn);
                $('#modalTitle').text('Edit Item');
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
                fetch(`/items/${id}`, {
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
