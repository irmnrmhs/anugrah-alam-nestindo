@extends('layouts.form')

@php
    $title = 'Kelola Data Keterangan Pengiriman';
    $singular = 'Keterangan Pengiriman';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Supplier</th>
    <th>Rumah Burung</th>
    <th>Nomor SKP</th>
    <th>Tanggal SKP</th>
    <th>Tanggal Panen</th>
    <th>Berat Panen</th>
    <th>Tanggal Kirim</th>
    <th>Berat Kirim</th>
@stop

@section('table-body')
    @foreach($dcertificates as $index => $dcertificate)
        <tr data-id="{{ $dcertificate->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $dcertificate->supplier->nama }}</td>
            <td>{{ $dcertificate->wbhouse->nama }}</td>
            <td>{{ $dcertificate->no_skp }}</td>
            <td>{{ $dcertificate->tgl_skp }}</td>
            <td>{{ $dcertificate->tgl_panen }}</td>
            <td>{{ $dcertificate->berat_panen }}</td>
            <td>{{ $dcertificate->tgl_kirim }}</td>
            <td>{{ $dcertificate->berat_kirim }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <input type="hidden" id="companies_id" name="companies_id" value="1">
    <div class="mb-3">
        <label>Supplier</label>
        <select id="suppliers_id" class="form-control" required>
            <option value="">-- Pilih Supplier --</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Rumah Burung</label>
        <select id="wbhouses_id" class="form-control" required>
            <option value="">-- Pilih Rumah Burung --</option>
            @foreach($wbhouses as $wbhouse)
                <option value="{{ $wbhouse->id }}">{{ $wbhouse->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nomor SKP</label>
        <input type="text" id="no_skp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal SKP</label>
        <input type="date" id="tgl_skp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Panen</label>
        <input type="date" id="tgl_panen" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Panen</label>
        <input type="number" id="berat_panen" step="0.01" min="0" max="99999.99" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tanggal Kirim</label>
        <input type="date" id="tgl_kirim" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Kirim</label>
        <input type="number" id="berat_kirim" step="0.01" min="0" max="99999.99" class="form-control">
    </div>
    
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/dcertificates/${id}` : '/dcertificates';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        companies_id: $('#companies_id').val(),
        suppliers_id: $('#suppliers_id').val(),
        wbhouses_id: $('#wbhouses_id').val(),
        no_skp: $('#no_skp').val(),
        tgl_skp: $('#tgl_skp').val(),
        tgl_panen: $('#tgl_panen').val(),
        berat_panen: $('#berat_panen').val(),
        tgl_kirim: $('#tgl_kirim').val(),
        berat_kirim: $('#berat_kirim').val()
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan Nomor SKP belum digunakan.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/dcertificates/${id}`)
            .then(r => r.json())
            .then(dcertificate => {
                $('#item_id').val(dcertificate.id);
                $('#companies_id').val(dcertificate.companies_id)
                $('#suppliers_id').val(dcertificate.suppliers_id)
                $('#wbhouses_id').val(dcertificate.wbhouses_id)
                $('#no_skp').val(dcertificate.no_skp)
                $('#tgl_skp').val(dcertificate.tgl_skp)
                $('#tgl_panen').val(dcertificate.tgl_panen)
                $('#berat_panen').val(dcertificate.berat_panen)
                $('#tgl_kirim').val(dcertificate.tgl_kirim)
                $('#berat_kirim').val(dcertificate.berat_kirim)
                $('#modalTitle').text('Edit SKP');
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
                fetch(`/dcertificates/${id}`, {
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
