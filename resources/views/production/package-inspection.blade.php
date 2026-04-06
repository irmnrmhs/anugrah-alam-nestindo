@extends('layouts.form')

@php
    $title = 'Pemeriksaan Bahan Kemas';
    $singular = 'Pemeriksaan Bahan Kemas';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Bahan Kemas</th>
    <th>Tanggal Uji</th>
    <th>Hasil Uji</th>
@stop

@section('table-body')
    @foreach($inspections as $index => $inspection)
        <tr data-id="{{ $inspection->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $inspection->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $inspection->package->bahan }}</td>
            <td>{{ $inspection->tanggal }}</td>
            <td>{{ $inspection->hasil }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Jenis Bahan Kemas</label>
        <select id="packages_id" class="form-control" required>
            <option value="">-- Pilih Jenis --</option>
            @foreach($packages as $package)
                <option value="{{ $package->id }}">{{ $package->bahan }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Uji</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Hasil Uji</label>
        <input type="text" id="hasil" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/inspections/${id}` : '/inspections';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        packages_id: $('#packages_id').val(),
        tanggal: $('#tanggal').val(),
        hasil: $('#hasil').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/inspections/${id}`)
            .then(r => r.json())
            .then(inspection => {
                $('#item_id').val(inspection.id);
                $('#packages_id').val(inspection.packages_id);
                $('#tanggal').val(inspection.tanggal);
                $('#hasil').val(inspection.hasil);
                $('#modalTitle').text('Edit Pemeriksaan Bahan Kemas');
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
                fetch(`/inspections/${id}`, {
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
