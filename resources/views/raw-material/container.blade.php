@extends('layouts.form')

@php
    $title = 'Kelola Kontainer';
    $singular = 'Kontainer';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Biji</th>
    <th>Berat</th>
    <th>Keterangan</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($containers as $index => $container)
        <tr data-id="{{ $container->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $container->arrival->kode }}</td>
            <td>{{ $container->biji }}</td>
            <td>{{ $container->berat }}</td>
            <td>{{ $container->keterangan }}</td>
            <td>{{ $container->employee->nama }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="arrivals_id" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach($arrivals as $arrival)
                <option value="{{ $arrival->id }}">{{ $arrival->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Petugas</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Petugas --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" step="0.01" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/containers/${id}` : '/containers';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        arrivals_id: $('#arrivals_id').val(),
        employees_id: $('#employees_id').val(),
        biji: $('#biji').val(),
        berat: $('#berat').val(),
        keterangan: $('#keterangan').val()
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
        fetch(`/containers/${id}`)
            .then(r => r.json())
            .then(container => {
                $('#item_id').val(container.id);
                $('#arrivals_id').val(container.arrivals_id);
                $('#employees_id').val(container.employees_id);
                $('#biji').val(container.biji);
                $('#berat').val(container.berat);
                $('#keterangan').val(container.keterangan);
                $('#modalTitle').text('Edit Kontainer');
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
                fetch(`/containers/${id}`, {
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
