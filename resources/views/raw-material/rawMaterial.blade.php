@extends('layouts.form')

@php
    $title = 'Kelola Bahan Baku';
    $singular = 'Bahan Baku';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Biji</th>
    <th>Berat</th>
@stop

@section('table-body')
    @foreach($rawMaterials as $index => $rawMaterial)
        <tr data-id="{{ $rawMaterial->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $rawMaterial->kode }}</td>
            <td>{{ $rawMaterial->biji }}</td>
            <td>{{ $rawMaterial->berat }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

{{-- @section('form-fields')
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" min="0" class="form-control">
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" step="0.01" min="0" max="99999.99" class="form-control">
    </div>
@stop --}}
{{-- 
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
                });
            }
        });
    });
@stop --}}
