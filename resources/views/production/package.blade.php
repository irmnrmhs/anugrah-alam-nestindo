@extends('layouts.form')

@php
    $title = 'Kelola Kemasan';
    $singular = 'Kemasan';
    $deleteMultipleUrl = '/packages/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Jenis Kemasan</th>
    <th>Kemasan</th>
    <th>Satuan</th>
    <th>Panjang</th>
    <th>Lebar</th>
    <th>Tinggi</th>
    <th>Lainnya</th>
    <th>Toleransi</th>
@stop

@section('table-body')
    @foreach($packages as $index => $package)
        <tr data-id="{{ $package->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $package->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $package->type->type }}</td>
            <td>{{ $package->bahan }}</td>
            <td>{{ $package->satuan }}</td>
            <td>{{ $package->panjang }}</td>
            <td>{{ $package->lebar }}</td>
            <td>{{ $package->tinggi }}</td>
            <td>{{ $package->lainnya }}</td>
            <td>{{ $package->toleransi }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Jenis Kemasan</label>
        <select id="types_id" class="form-control" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($types as $type)
                <option value="{{ $type->id }}">{{ $type->type }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Bahan Kemas</label>
        <input type="text" id="bahan" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <input type="text" id="satuan" class="form-control">
    </div>
    <div class="mb-3">
        <label>Panjang</label>
        <input type="number" id="panjang" min="0" class="form-control">
    </div>
    <div class="mb-3">
        <label>Lebar</label>
        <input type="number" id="lebar" min="0" class="form-control">
    </div>
    <div class="mb-3">
        <label>Tinggi</label>
        <input type="number" id="tinggi" min="0" class="form-control">
    </div>
    </div>
    <div class="mb-3">
        <label>Lainnya</label>
        <input type="text" id="lainnya" class="form-control">
    </div>
    <div class="mb-3">
        <label>Toleransi</label>
        <input type="number" id="toleransi" min="0" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/packages/${id}` : '/packages';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        types_id: $('#types_id').val(),
        bahan: $('#bahan').val(),
        satuan: $('#satuan').val(),
        panjang: $('#panjang').val(),
        lebar: $('#lebar').val(),
        tinggi: $('#tinggi').val(),
        lainnya: $('#lainnya').val(),
        toleransi: $('toleransi').val(),
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
    fetch(`/packages/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#types_id').val(data.types_id);
                $('#bahan').val(data.bahan);
                $('#satuan').val(data.satuan);
                $('#panjang').val(data.panjang);
                $('#lebar').val(data.lebar);
                $('#tinggi').val(data.tinggi);
                $('#lainnya').val(data.lainnya);
                $('#toleransi').val(data.toleransi);
                $('#modalTitle').text('Edit Kemasan');
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
