@extends('layouts.form')

@php
    $title = 'Kelola Data Grade Produk Jadi';
    $singular = 'Grade Produk Jadi';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Grade</th>
    <th>Keterangan</th>
    <th>Status</th>
@stop

@section('table-body')
    @foreach($grades as $index => $grade)
        <tr data-id="{{ $grade->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $grade->grade }}</td>
            <td>{{ $grade->keterangan}}</td>
            <td>
                @if($grade->status)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Non Aktif</span>
                @endif
            </td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Grade Produk Jadi</label>
        <input type="text" id="grade" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select id="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Non Aktif</option>
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/fp-grades/${id}` : '/fp-grades';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        grade: $('#grade').val(),
        keterangan: $('#keterangan').val(),
        status: $('#status').val()
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
    .catch(() => Swal.fire('Error', 'Kode grade sudah ada / tidak valid!', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/fp-grades/${id}`)
            .then(r => r.json())
            .then(grade => {
                $('#item_id').val(grade.id);
                $('#grade').val(grade.grade);
                $('#keterangan').val(grade.keterangan);
                $('#status').val(grade.status);
                $('#modalTitle').text('Edit Grade Produk Jadi');
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
                fetch(`/fp-grades/${id}`, {
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
