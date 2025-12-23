@extends('layouts.form')

@php
    $title = 'Kelola Data Supplier';
    $singular = 'Supplier';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Nama Supplier</th>
    <th>Alamat</th>
    <th>No. Telepon</th>
    <th>Kategori</th>
@stop

@section('table-body')
    @foreach($suppliers as $index => $supplier)
        <tr data-id="{{ $supplier->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $supplier->kode }}</td>
            <td>{{ $supplier->nama }}</td>
            <td>{{ empty($supplier->alamat) ? '-' : $supplier->alamat }}</td>
            <td>{{ empty($supplier->no_telp) ? '-' : $supplier->no_telp }}</td>
            <td>{{ $supplier->category->kategori }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" id="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <input type="text" id="alamat" class="form-control">
    </div>
    <div class="mb-3">
        <label>No. Telp</label>
        <input type="text" id="no_telp" class="form-control">
    </div>
    <div class="mb-3">
        <label>Kategori</label>
        <select id="categories_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->kategori }}</option>
            @endforeach
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/suppliers/${id}` : '/suppliers';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        nama: $('#nama').val(),
        alamat: $('#alamat').val(),
        no_telp: $('#no_telp').val(),
        categories_id: $('#categories_id').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
    if (res.status === 'success') {
        Swal.fire('Sukses', res.message, 'success')
            .then(() => location.reload());
    } else {
        Swal.fire(
                'Gagal',
                res.message || Object.values(res.errors || {}).join('\n'),
                'error'
            );
        }
    })
    .catch(err => {
        Swal.fire(
            'Error',
            'Terjadi kesalahan. Pastikan data valid dan tidak duplikat.',
            'error'
        );
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/suppliers/${id}`)
            .then(r => r.json())
            .then(supplier => {
                $('#item_id').val(supplier.id);
                $('#kode').val(supplier.kode);
                $('#nama').val(supplier.nama);
                $('#alamat').val(supplier.alamat);
                $('#no_telp').val(supplier.no_telp);
                $('#categories_id').val(supplier.categories_id);
                $('#modalTitle').text('Edit Supplier');
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
                fetch(`/suppliers/${id}`, {
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
