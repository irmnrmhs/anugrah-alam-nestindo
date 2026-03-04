@extends('layouts.form')

@php
    $title = 'Kelola Data Item';
    $singular = 'Item';
    $deleteMultipleUrl = '/items/delete-multiple';
    $importUrl = route('items.import');
    $templateUrl = route('items.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Grade</th>
    <th>Kode</th>
    <th>Item</th>
    <th>Spesifikasi</th>
    <th>Harga</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($items as $index => $item)
        <tr data-id="{{ $item->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $item->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $item->product->grade->grade }}</td>
            <td>{{ $item->kode }}</td>
            <td>{{ $item->item }}</td>
            <td>{{ $item->spesifikasi }}</td>
            <td>{{ $item->harga }}</td>
            <td>{{ $item->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Produk</label>
        <select id="products_id" class="form-control">
            <option value="">Pilih Produk</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->grade->grade }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="kode" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Item</label>
        <input type="text" id="item" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Spesifikasi</label>
        <input type="text" id="spesification" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Harga</label>
        <input type="number" id="price" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="ket" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/items/${id}` : '/items';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        products_id: $('#products_id').val(),
        kode: $('#kode').val(),
        item: $('#item').val(),
        spesification: $('#spesification').val(),
        price: $('#price').val(),
        ket: $('#ket').val(),
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
        fetch(`/items/${id}`)
            .then(r => r.json())
            .then(item => {
                $('#item_id').val(item.id);
                $('#products_id').val(item.products_id);
                $('#kode').val(item.kode);
                $('#item').val(item.item);
                $('#spesification').val(item.spesification);
                $('#price').val(item.price);
                $('#ket').val(item.ket);
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
