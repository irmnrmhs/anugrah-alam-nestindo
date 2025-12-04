@extends('layouts.form')

@php
    $title = 'Kelola Data Sesek Kaki';
    $singular = 'Sesek Kaki';
    $deleteMultipleUrl = '/edges/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>Kode Produk</th>
    <th>Karyawan</th>
    <th>Tanggal Mulai</th>
    <th>Jumlah Biji</th>
    <th>Berat</th>
    <th>Tanggal Selesai</th>
    <th>Biji Keluar</th>
    <th>Berat Keluar</th>
    <th>Status</th>
@stop

@section('table-body')
    @foreach($edges as $index => $edge)
        <tr data-id="{{ $edge->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $edge->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $edge->history->identifier->kode }}</td>
            <td>{{ $edge->employee->nama }}</td>
            <td>{{ $edge->tgl_mulai }}</td>
            <td>{{ $edge->biji_masuk }}</td>
            <td>{{ $edge->berat_masuk }}</td>
            <td>{{ $edge->tgl_selesai }}</td>
            <td>{{ $edge->biji_keluar }}</td>
            <td>{{ $edge->berat_keluar }}</td>
            <td>{{ $edge->status }}</td>
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
        <select id="histories_id" class="form-control" required>
            <option value="">-- Pilih Kode --</option>
            @foreach($histories as $history)
                <option value="{{ $history->id }}">{{ $history->nama_dept }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="nip" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nama</label>
        <input type="text" id="nama" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/employees/${id}` : '/employees';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        nip: $('#nip').val(),
        nama: $('#nama').val(),
        dept_id: $('#dept_id').val(),
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
        fetch(`/employees/${id}`)
            .then(r => r.json())
            .then(employee => {
                $('#item_id').val(employee.id);
                $('#nip').val(employee.nip);
                $('#nama').val(employee.nama);
                $('#dept_id').val(employee.dept_id);
                $('#modalTitle').text('Edit Karyawan');
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
                fetch(`/employees/${id}`, {
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
