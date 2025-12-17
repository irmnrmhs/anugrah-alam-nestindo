@extends('layouts.form')

@php
    $title = 'Kelola Jenis Warna';
    $singular = 'Jenis Warna';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Warna</th>
    <th>Jenis Warna</th>
@stop

@section('table-body')
    @foreach($colors as $index => $color)
        <tr data-id="{{ $color->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $color->kode }}</td>
            <td>{{ $color->jenis_warna }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode Warna</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Jenis Warna</label>
        <input type="text" id="jenis_warna" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/colors/${id}` : '/colors';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        jenis_warna: $('#jenis_warna').val()
    };

    fetch(url, {
        method: method,
        headers: {'Content-Type': 'application/json'},
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode dan kategori tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/colors/${id}`)
            .then(r => r.json())
            .then(color => {
                $('#item_id').val(color.id);
                $('#kode').val(color.kode);
                $('#jenis_warna').val(color.jenis_warna);
                $('#modalTitle').text('Edit Jenis Warna');
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
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/colors/${id}`, {
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
