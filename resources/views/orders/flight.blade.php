@extends('layouts.form')

@php
    $title = 'Kelola Penerbangan';
    $singular = 'Penerbangan';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tujuan</th>
    <th>Nomor Penerbangan</th>
    <th>Shipping Mark</th>
    <th>Perkiraan Tiba</th>
@stop

@section('table-body')
    @foreach($flights as $index => $flight)
        <tr data-id="{{ $flight->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $flight->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $flight->destination }}</td>
            <td>{{ $flight->flight_no }}</td>
            <td>
                @if ($flight->shipping_mark == 0)
                    N
                @else
                    M
                @endif
            </td>
            <td>{{ $flight->estimated_arrival }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Tujuan</label>
        <input type="text" id="destination" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nomor Penerbangan</label>
        <input type="text" id="flight_no" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanda Pengiriman</label>
        <select id="shipping_mark" class="form-control">
            <option value="">-- Pilih --</option>
                <option value=0>N</option>
                <option value=1>M</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Perkiraan Tiba</label>
        <input type="date" id="estimated_arrival" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/flights/${id}` : '/flights';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        destination: $('#destination').val(),
        flight_no: $('#flight_no').val(),
        shipping_mark: $('#shipping_mark').val(),
        estimated_arrival: $('#estimated_arrival').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/flights/${id}`)
            .then(r => r.json())
            .then(flight => {
                $('#item_id').val(flight.id);
                $('#destination').val(flight.destination);
                $('#flight_no').val(flight.flight_no);
                $('#shipping_mark').val(flight.shipping_mark);
                $('#estimated_arrival').val(flight.estimated_arrival);
                $('#modalTitle').text('Edit Mobil');
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
                fetch(`/flights/${id}`, {
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
