@extends('layouts.form')

@php
    $title = 'Kelola Data Packing';
    $singular = 'Packing';
    $deleteMultipleUrl = '/packs/delete-multiple';
    // $importUrl = route('packs.import');
    // $templateUrl = route('packs.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    {{-- <th>No Batch</th>
    <th>Buyer</th>
    <th>Invoice</th>
    <th>Kode Bahan Baku</th> --}}
    <th>Tanggal Kirim</th>
@stop

@section('table-body')
    @foreach($packs as $index => $pack)
        <tr data-id="{{ $pack->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $pack->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $pack->tanggal }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/packs/${id}` : '/packs';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        orders_id: $('#orders_id').val(),
        tanggal: $('#tanggal').val(),
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
        fetch(`/packs/${id}`)
            .then(r => r.json())
            .then(pack => {
                $('#item_id').val(pack.id);
                $('#orders_id').val(pack.orders_id);
                $('#tanggal').val(pack.tanggal);
                $('#modalTitle').text('Edit Packing');
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
                fetch(`/packs/${id}`, {
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
