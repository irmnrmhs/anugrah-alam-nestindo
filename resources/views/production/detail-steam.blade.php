@extends('layouts.form')

@php
    $title = 'Kelola Detail Steam';
    $singular = 'Detail Steam';
    $deleteMultipleUrl = '/dsteams/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Batch</th>
    <th>Suhu Preheating</th>
    <th>Waktu Preheating</th>
    <th>Suhu Total</th>
    <th>Waktu Total</th>
    <th>Jumlah Tray</th>
@stop

@section('table-body')
    @foreach($dsteams as $index => $dsteam)
        <tr data-id="{{ $dsteam->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $dsteam->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $dsteam->order->steams->first()->batch }}</td>
            <td>{{ $dsteam->suhu_preheating }}</td>
            <td>{{ $dsteam->waktu_preheating }}</td>
            <td>{{ $dsteam->suhu_total }}</td>
            <td>{{ $dsteam->waktu_total }}</td>
            <td>{{ $dsteam->jml_tray }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode Batch</label>
        <select id="orders_id" class="form-control" required>
            <option value="">-- Pilih Tipe Sarang Walet --</option>
            @foreach($orders as $order)
                <option value="{{ $order->id }}">{{ $order->steams->first()->batch }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Suhu Preheating</label>
        <input type="number" id="suhu_preheating" step="0.01" min="0" max="999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Waktu Preheating</label>
        <input type="time" id="waktu_preheating" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Suhu Total</label>
        <input type="number" id="suhu_total" step="0.01" min="0" max="999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Waktu Total</label>
        <input type="time" id="waktu_total" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Jumlah Tray</label>
        <input type="number" id="jml_tray" min="1" max="6" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/dsteams/${id}` : '/dsteams';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        orders_id: $('#orders_id').val(),
        suhu_preheating: $('#suhu_preheating').val(),
        waktu_preheating: $('#waktu_preheating').val(),
        suhu_total: $('#suhu_total').val(),
        waktu_total: $('#waktu_total').val(),
        jml_tray: $('#jml_tray').val(),
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
        fetch(`/dsteams/${id}`)
            .then(r => r.json())
            .then(dsteam => {
                $('#item_id').val(dsteam.id);
                $('#orders_id').val(dsteam.orders_id);
                $('#suhu_preheating').val(dsteam.suhu_preheating);
                $('#waktu_preheating').val(dsteam.waktu_preheating);
                $('#suhu_total').val(dsteam.suhu_total);
                $('#waktu_total').val(dsteam.waktu_total);
                $('#jml_tray').val(dsteam.jml_tray);
                $('#modalTitle').text('Edit Detail Steam');
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
                fetch(`/dsteams/${id}`, {
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
