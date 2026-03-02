@extends('layouts.form')

@php
    $title = 'Kelola Data Jadwal';
    $singular = 'Jadwal';
    $deleteMultipleUrl = '/schedules/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Nomor Penerbangan</th>
    <th>Tanggal Preshipment</th>
    <th>Tanggal Shipment</th>
    <th>Petugas Karantina</th>
@stop

@section('table-body')
    @foreach($schedules as $index => $schedule)
        <tr data-id="{{ $schedule->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $schedule->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $schedule->flight->flight_no }}</td>
            <td>{{ $schedule->preshipment }}</td>
            <td>{{ $schedule->shipment }}</td>
            <td>{{ $schedule->officer->nama }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Penerbangan</label>
        <select id="flights_id" class="form-control" required>
            <option value="">-- Pilih No Penerbangan --</option>
            @foreach($flights as $flight)
                <option value="{{ $flight->id }}">{{ $flight->flight_no }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Preshipment</label>
        <input type="date" id="preshipment" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Shipment</label>
        <input type="date" id="shipment" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Petugas Karantina</label>
        <select id="officers_id" class="form-control" required>
            <option value="">-- Pilih Petugas Karantina --</option>
            @foreach($officers as $officer)
                <option value="{{ $officer->id }}">{{ $officer->nama }}</option>
            @endforeach
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/schedules/${id}` : '/schedules';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        flights_id: $('#flights_id').val(),
        officers_id: $('#officers_id').val(),
        preshipment: $('#preshipment').val() || null,
        shipment: $('#shipment').val(),
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
        fetch(`/schedules/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#flights_id').val(data.flights_id);
                $('#officers_id').val(data.officers_id);
                $('#preshipment').val(data.preshipment);
                $('#shipment').val(data.shipment);
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
                fetch(`/schedules/${id}`, {
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
