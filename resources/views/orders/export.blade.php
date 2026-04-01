@extends('layouts.form')

@php
    $title = 'Kelola Data Ekspor';
    $singular = 'Ekspor';
    $deleteMultipleUrl = '/exports/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Invoice</th>
    <th>Customer</th>
    <th>No. Kontrak</th>
    <th>Tanggal</th>
    <th>Sarana Transportasi</th>
@stop

@section('table-body')
    @foreach($exports as $index => $export)
        <tr data-id="{{ $export->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $export->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $export->inv }}</td>
            <td>{{ $export->customer->nama }}</td>
            <td>{{ $export->contract_no }}</td>
            <td>{{ $export->date }}</td>
            <td>{{ $export->by }}</td>
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
        <input type="text" id="inv" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Customer</label>
        <select id="customers_id" class="form-control" required>
            <option value="">-- Pilih Customer --</option>
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>No. Kontrak</label>
        <input type="text" id="contract_no" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" id="date" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Sarana Transportasi</label>
        <input type="string" id="by" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/exports/${id}` : '/exports';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        inv: $('#inv').val(),
        customers_id: $('#customers_id').val(),
        contract_no: $('#contract_no').val(),
        date: $('#date').val(),
        by: $('#by').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan no invoice dan no kontrak tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/exports/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#inv').val(data.inv);
                $('#customers_id').val(data.customers_id);
                $('#contract_no').val(data.contract_no);
                $('#date').val(data.date);
                $('#by').val(data.by);
                $('#modalTitle').text('Edit Ekspor');
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
                fetch(`/exports/${id}`, {
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
