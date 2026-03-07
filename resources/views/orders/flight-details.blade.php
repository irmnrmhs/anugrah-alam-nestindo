@extends('layouts.form')

@php
    $title = 'Kelola Data Detail Penerbangan';
    $singular = 'Detail Penerbangan';
    $deleteMultipleUrl = '/dflights/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Invoice</th>
    <th>No Penerbangan</th>
@stop

@section('table-body')
    @foreach($dflights as $index => $dflight)
        <tr data-id="{{ $dflight->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $dflight->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $dflight->export->inv }}</td>
            <td>{{ $dflight->flight->flight_no }}</td>
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
        <label>Penerbangan</label>
        <select id="flights_id" class="form-control" required>
            <option value="">-- Pilih No Penerbangan --</option>
            @foreach($flights as $flight)
                <option value="{{ $flight->id }}">{{ $flight->flight_no }}</option>
            @endforeach
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/dflights/${id}` : '/dflights';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        exports_id: $('#exports_id').val(),
        flights_id: $('#flights_id').val(),
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
        fetch(`/dflights/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#exports_id').val(data.exports_id);
                $('#flights_id').val(data.flights_id);
                $('#modalTitle').text('Edit Detail Penerbangan');
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
                fetch(`/dflights/${id}`, {
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
