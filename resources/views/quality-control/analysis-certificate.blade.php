@extends('layouts.form')

@php
    $title = 'Kelola Data Sertifikat Analisis';
    $singular = 'Sertifikat Analisis';
    $deleteMultipleUrl = '/certificates/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Batch</th>
    <th>Invoice</th>
    <th>Item Analisis</th>
    <th>Tanggal</th>
    <th>Hasil</th>
@stop

@section('table-body')
    @foreach($certificates as $index => $certificate)
        <tr data-id="{{ $certificate->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $certificate->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $certificate->fproduct->batch }}</td>
            <td>{{ $certificate->export->inv }}</td>
            <td>{{ $certificate->analysis->item }}</td>
            <td>{{ $certificate->tanggal }}</td>
            <td>{{ $certificate->hasil }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Batch</label>
        <select id="fproducts_id" class="form-control" required>
            <option value="">-- Pilih Batch Produk --</option>
            @foreach($fproducts as $fproduct)
                <option value="{{ $fproduct->id }}">{{ $fproduct->batch }}</option>
            @endforeach
        </select>
    </div>
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
        <label>Item Analisis</label>
        <select id="analysis_id" class="form-control" required>
            <option value="">-- Pilih Item --</option>
            @foreach($analysis as $analys)
                <option value="{{ $analys->id }}">{{ $analys->item }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Hasil Uji</label>
        <select id="hasil" class="form-control">
            <option value="">-- Pilih --</option>
                <option value=1>Lulus Uji</option>
                <option value=0>Tidak Lulus Uji</option>
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/certificates/${id}` : '/certificates';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        fproducts_id: $('#fproducts_id').val(),
        exports_id: $('#exports_id').val(),
        analysis_id: $('#analysis_id').val(),
        tanggal: $('#tanggal').val(),
        hasil: $('#hasil').val(),
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
        fetch(`/certificates/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#fproducts_id').val(data.fproducts_id);
                $('#exports_id').val(data.exports_id);
                $('#analysis_id').val(data.analysis_id);
                $('#tanggal').val(data.tanggal);
                $('#hasil').val(data.hasil);
                $('#modalTitle').text('Edit Sertifikat Analisis');
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
                fetch(`/certificates/${id}`, {
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
