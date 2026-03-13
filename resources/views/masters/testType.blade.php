@extends('layouts.form')

@php
    $title = 'Kelola Data Jenis Uji';
    $singular = 'Jenis Uji';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kategori</th>
    <th>Kode</th>
    <th>Nama Uji</th>
    <th>Satuan</th>
    <th>Standar Minimal</th>
    <th>Standar Maksimal</th>
@stop

@section('table-body')
    @foreach($testTypes as $index => $testType)
        <tr data-id="{{ $testType->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $testType->category->kategori }}</td>
            <td>{{ $testType->kode }}</td>
            <td>{{ $testType->nama_uji }}</td>
            <td>{{ $testType->satuan }}</td>
            <td>{{ $testType->standar_minimal }}</td>
            <td>{{ $testType->standar_maksimal }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kategori</label>
        <select id="categories_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->kategori }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama Uji</label>
        <input type="text" id="nama_uji" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Satuan</label>
        <select id="satuan" class="form-control" required>
            <option value="">-- Pilih Satuan --</option>
            <option value="%">%</option>
            <option value="mg/kg">mg/kg</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Standar Minimal</label>
        <input type="number" id="standar_minimal" step="0.001" min="0" max="999.999" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Standar Maksimal</label>
        <input type="number" id="standar_maksimal" step="0.001" min="0" max="999.999" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/testTypes/${id}` : '/testTypes';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        categories_id: $('#categories_id').val(),
        kode: $('#kode').val(),
        nama_uji: $('#nama_uji').val(),
        satuan: $('#satuan').val(),
        standar_minimal: $('#standar_minimal').val(),
        standar_maksimal: $('#standar_maksimal').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kategori, kode dan nama uji tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/testTypes/${id}`)
            .then(r => r.json())
            .then(testType => {
                $('#item_id').val(testType.id);
                $('#categories_id').val(testType.categories_id);
                $('#kode').val(testType.kode);
                $('#nama_uji').val(testType.nama_uji);
                $('#satuan').val(testType.satuan);
                $('#standar_minimal').val(testType.standar_minimal);
                $('#standar_maksimal').val(testType.standar_maksimal);
                $('#modalTitle').text('Edit Jenis Uji');
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
                fetch(`/testTypes/${id}`, {
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
