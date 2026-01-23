@extends('layouts.form')

@php
    $title = 'Kelola Rumah Burung';
    $singular = 'Rumah Burung';
    $deleteMultipleUrl = '/wbhouses/delete-multiple';
    // $importUrl = route('wbhouses.import');
    $templateUrl = route('wbhouses.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Nomor Registrasi</th>
    <th>Nama Rumah Burung</th>
    <th>Alamat</th>
    <th>Area</th>
    <th>Kapasitas</th>
    <th>Pemilik</th>
@stop

@section('table-body')
    @foreach($wbhouses as $index => $wbhouse)
        <tr data-id="{{ $wbhouse->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $wbhouse->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $wbhouse->kode }}</td>
            <td>{{ $wbhouse->nama }}</td>
            <td>{{ empty($wbhouse->alamat) ? '-' : $wbhouse->alamat }}</td>
            <td>{{ $wbhouse->area->area }}</td>
            <td>{{ empty($wbhouse->kapasitas) ? 0 : $wbhouse->kapasitas }}</td>
            <td>{{ $wbhouse->owner }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Nomor Registrasi</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama Rumah Burung</label>
        <input type="text" id="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <input type="text" id="alamat" class="form-control">
    </div>
    <div class="mb-3">
        <label>Area</label>
        <select id="areas_id" class="form-control" required>
            <option value="">-- Pilih Area --</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->area }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kapasitas</label>
        <input type="number" id="kapasitas" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Owner</label>
        <input type="number" id="owner" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/wbhouses/${id}` : '/wbhouses';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        nama: $('#nama').val(),
        alamat: $('#alamat').val(),
        areas_id: $('#areas_id').val(),
        kapasitas: $('#kapasitas').val(),
        owner: $('#owner').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan no registrasi dan nama tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/wbhouses/${id}`)
            .then(r => r.json())
            .then(wbhouse => {
                $('#item_id').val(wbhouse.id);
                $('#kode').val(wbhouse.kode);
                $('#nama').val(wbhouse.nama);
                $('#alamat').val(wbhouse.alamat);
                $('#areas_id').val(wbhouse.areas_id);
                $('#kapasitas').val(wbhouse.kapasitas);
                $('#owner').val(wbhouse.owner);
                $('#modalTitle').text('Edit Rumah Burung');
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
                fetch(`/wbhouses/${id}`, {
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
