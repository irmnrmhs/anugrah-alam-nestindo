@extends('layouts.form')

@php
    $title = 'Kelola Stok Bahan Baku';
    $singular = 'Stok Bahan Baku';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Bahan Baku</th>
    <th>Petugas</th>
    <th>Tanggal Keluar</th>
    <th>Biji Keluar</th>
    <th>Berat Keluar</th>
    <th>Biji Sisa</th>
    <th>Berat Sisa</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($stocks as $index => $stock)
        <tr data-id="{{ $stock->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $stock->rawMaterial->kode }}</td>
            <td>{{ $stock->employee->nama }}</td>
            <td>{{ $stock->tgl_keluar }}</td>
            <td>{{ $stock->biji_keluar }}</td>
            <td>{{ $stock->berat_keluar }}</td>
            <td>{{ $stock->biji_sisa }}</td>
            <td>{{ $stock->berat_sisa }}</td>
            <td>{{ $stock->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Bahan Baku</label>
        <select id="rms_id" class="form-control" required>
            <option value="">-- Pilih Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Petugas</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Petugas --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Keluar</label>
        <input type="date" id="tgl_keluar" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji Keluar</label>
        <input type="number" id="biji_keluar" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Keluar</label>
        <input type="number" id="berat_keluar" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji Sisa</label>
        <input type="number" id="biji_sisa" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Sisa</label>
        <input type="number" id="berat_sisa" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/rmstocks/${id}` : '/rmstocks';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        employees_id: $('#employees_id').val(),
        tgl_keluar: $('#tgl_keluar').val(),
        biji_keluar: $('#biji_keluar').val(),
        berat_keluar: $('#berat_keluar').val(),
        biji_sisa: $('#biji_sisa').val(),
        berat_sisa: $('#berat_sisa').val(),
        keterangan: $('#keterangan').val()
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/rmstocks/${id}`)
            .then(r => r.json())
            .then(stock => {
                $('#item_id').val(stock.id);
                $('#rms_id').val(stock.rms_id);
                $('#employees_id').val(stock.employees_id);
                $('#tgl_keluar').val(stock.tgl_keluar);
                $('#biji_keluar').val(stock.biji_keluar);
                $('#berat_keluar').val(stock.berat_keluar);
                $('#biji_sisa').val(stock.biji_sisa);
                $('#berat_sisa').val(stock.berat_sisa);
                $('#keterangan').val(stock.keterangan);
                $('#modalTitle').text('Edit Stok Bahan Baku');
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
                fetch(`/rmstocks/${id}`, {
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
