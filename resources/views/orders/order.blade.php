@extends('layouts.form')

@php
    $title = 'Kelola Data Pesanan';
    $singular = 'Pesanan';
    $deleteMultipleUrl = '/orders/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Invoice</th>
    <th>Produk Batch</th>
    <th>Item</th>
    <th>Spesification</th>
    <th>Packaging</th>
    <th>Label Nutrisi</th>
    <th>Net</th>
    <th>Gross</th>
    <th>Cartons</th>
    <th>Amount FOB</th>
    <th>Amount CIF</th>
@stop

@section('table-body')
    @foreach($orders as $index => $order)
        <tr data-id="{{ $order->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $order->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $order->export->inv }}</td>
            <td>{{ $order->batch->batch }}</td>
            <td>{{ $order->item->item->item}}</td>
            <td>{{ $order->item->specification}}</td>
            <td>{{ $order->packaging }}</td>
            <td>{{ $order->label }}</td>
            <td>{{ $order->net }}</td>
            <td>{{ $order->gross }}</td>
            <td>{{ $order->cartons }}</td>
            <td>{{ $order->amount_fob }}</td>
            <td>{{ $order->amount_cif }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Invoice</label>
        <select id="exports_id" class="form-control" required>
            <option value="">-- Pilih Invoice --</option>
            @foreach($exports as $export)
                <option value="{{ $export->id }}">{{ $export->inv }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Produk Batch</label>
        <select id="batch_id" class="form-control" required>
            <option value="">-- Pilih Produk Batch --</option>
            @foreach($batchs as $batch)
                <option value="{{ $batch->id }}">{{ $batch->batch }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Item</label>
        <select id="items_id" class="form-control" required>
            <option value="">-- Pilih Item --</option>
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->specification }} - {{ $item->item->item }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Packaging</label>
        <input type="number" id="packaging" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Label Nutrisi</label>
        <input type="number" id="label" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Net</label>
        <input type="number" id="net" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gross</label>
        <input type="number" id="gross" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Carton</label>
        <input type="number" id="cartons" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Amount FOB</label>
        <input type="number" id="amount_fob" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Gross CIF</label>
        <input type="number" id="amount_cif" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/orders/${id}` : '/orders';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        exports_id: $('#exports_id').val(),
        batch_id: $('#batch_id').val(),
        items_id: $('#items_id').val(),
        packaging: $('#packaging').val(),
        label: $('#label').val(),
        net: $('#net').val(),
        gross: $('#gross').val(),
        cartons: $('#cartons').val(),
        amount_fob: $('#amount_fob').val(),
        amount_cif: $('#amount_cif').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan no invoice tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/orders/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#exports_id').val(data.exports_id);
                $('#batch_id').val(data.batch_id);
                $('#items_id').val(data.items_id);
                $('#packaging').val(data.packaging);
                $('#label').val(data.label);
                $('#net').val(data.net);
                $('#gross').val(data.gross);
                $('#cartons').val(data.cartons);
                $('#amount_fob').val(data.amount_fob);
                $('#amount_cif').val(data.amount_cif);
                $('#modalTitle').text('Edit Pesanan');
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
                fetch(`/orders/${id}`, {
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
