@extends('layouts.form')

@php
    $title = 'Kelola Hasil Uji';
    $singular = 'Hasil Uji';
    $deleteMultipleUrl = '/rm-results/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Rumah Burung/No. Registrasi</th>
    <th>Kadar Air</th>
    <th>Kadar Nitrit</th>
    <th>Kadar Aluminium</th>
@stop

@section('table-body')
    @foreach($results as $index => $result)
        <tr data-id="{{ $result->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $result->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $result->rawMaterial->kode }}</td>
            <td>{{ $result->kadar_air }}</td>
            <td>{{ $result->kadar_nitrit }}</td>
            <td>{{ $result->kadar_aluminium }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Rumah Burung/No. Registrasi</label>
        <select id="rms_id" class="form-control" required>
            <option value="">-- Pilih Rumah Burung --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode}}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kadar Air</label>
        <input type="number" id="kadar_air" step="0.01" min="0" max="999.99" class="form-control">
    </div>
    <div class="mb-3">
        <label>Kadar Nitrit</label>
        <input type="number" id="kadar_nitrit" step="0.01" min="0" max="999.9" class="form-control">
    </div>
    <div class="mb-3">
        <label>Kadar Aluminium</label>
        <input type="number" id="kadar_aluminium" step="0.01" min="0" max="999.9" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/rm-results/${id}` : '/rm-results';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        kadar_air: $('#kadar_air').val(),
        kadar_nitrit: $('#kadar_nitrit').val(),
        kadar_aluminium: $('#kadar_aluminium').val()
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
        fetch(`/rm-results/${id}`)
            .then(r => r.json())
            .then(result => {
                $('#item_id').val(result.id);
                $('#rms_id').val(result.rms_id);
                $('#kadar_air').val(result.kadar_air);
                $('#kadar_nitrit').val(result.kadar_nitrit);
                $('#kadar_aluminium').val(result.kadar_aluminium);
                $('#modalTitle').text('Edit Hasil Uji');
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
                fetch(`/rm-results/${id}`, {
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
