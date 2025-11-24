@extends('layouts.form')

@php
    $title = 'Kelola Pengidentifikasi Produk';
    $singular = 'Pengidentifikasi Produk';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Supplier</th>
    <th>Kode Bahan Baku</th>
    <th>Grade</th>
    <th>Kode Produk</th>
    <th>Tanggal Grading</th>
    <th>Jumlah Biji</th>
    <th>Jumlah Berat</th>
@stop

@section('table-body')
    @foreach($identifiers as $index => $identifier)
        <tr data-id="{{ $identifier->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $identifier->supplier->nama }}</td>
            <td>{{ $identifier->rawMaterial->kode }}</td>
            <td>{{ $identifier->grade->grade }}</td>
            <td>{{ $identifier->kode }}</td>
            <td>{{ $identifier->tanggal }}</td>
            <td>{{ $identifier->biji }}</td>
            <td>{{ $identifier->berat }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Supplier</label>
        <select id="suppliers_id" class="form-control" required>
            <option value="">-- Pilih Supplier --</option>
            @foreach($suppliers as $supplier)
                <option value="{{ $supplier->id }}">{{ $supplier->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="rms_id" class="form-control" required>
            <option value="">-- Pilih Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Grade</label>
        <select id="grades_id" class="form-control" required>
            <option value="">-- Pilih Grade --</option>
            @foreach($grades as $grade)
                <option value="{{ $grade->id }}">{{ $grade->grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Grading</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/identifiers/${id}` : '/identifiers';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        suppliers_id: $('#suppliers_id').val(),
        rms_id: $('#rms_id').val(),
        grades_id: $('#grades_id').val(),
        {{-- kode: $('#kode').val(), --}}
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
        berat: $('#berat').val()
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan NIP tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/identifiers/${id}`)
            .then(r => r.json())
            .then(identifier => {
                $('#item_id').val(identifier.id);
                $('#suppliers_id').val(identifier.suppliers_id);
                $('#rms_id').val(identifier.rms_id);
                $('#grades_id').val(identifier.grades_id);
                {{-- $('#kode').val(identifier.kode); --}}
                $('#tanggal').val(identifier.tanggal);
                $('#biji').val(identifier.biji);
                $('#berat').val(identifier.berat);
                $('#modalTitle').text('Edit Pengidentifikasi Produk');
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
                fetch(`/identifiers/${id}`, {
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
