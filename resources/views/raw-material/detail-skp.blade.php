@extends('layouts.form')

@php
    $title = 'Kelola Detail Pengiriman';
    $singular = 'Detail Pengiriman';
    $deleteMultipleUrl = '/details/delete-multiple';
    $hideImportButton = true;
    $hideExportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Nomor SKP</th>
    <th>Tanggal Panen</th>
    <th>Berat Panen</th>
    <th>Tanggal Kirim</th>
    <th>Berat Kirim</th>
@stop

@section('table-body')
    @foreach($details as $index => $detail)
        <tr data-id="{{ $detail->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $detail->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail->dcertificate->no_skp }}</td>
            <td>{{ $detail->tgl_panen }}</td>
            <td>{{ $detail->berat_panen }}</td>
            <td>{{ $detail->tgl_kirim }}</td>
            <td>{{ $detail->berat_kirim }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Nomor SKP</label>
        <select id="dcertificates_id" class="form-control" required>
            <option value="">-- Pilih Nomor SKP --</option>
            @foreach($dcertificates as $dcertificate)
                <option value="{{ $dcertificate->id }}">{{ $dcertificate->no_skp }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Panen</label>
        <input type="date" id="tgl_panen" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Panen</label>
        <input type="number" id="berat_panen" min="0" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Kirim</label>
        <input type="date" id="tgl_kirim" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Kirim</label>
        <input type="number" id="berat_kirim" min="0" step="0.01" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/details/${id}` : '/details';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        dcertificates_id: $('#dcertificates_id').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan NIP tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/details/${id}`)
            .then(r => r.json())
            .then(detail => {
                $('#item_id').val(detail.id);
                $('#dcertificates_id').val(detail.dcertificates_id);
                $('#tgl_panen').val(detail.tgl_panen);
                $('#berat_panen').val(detail.berat_panen);
                $('#tgl_kirim').val(detail.tgl_kirim);
                $('#berat_kirim').val(detail.berat_kirim);
                $('#modalTitle').text('Edit Karyawan');
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
                fetch(`/details/${id}`, {
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
