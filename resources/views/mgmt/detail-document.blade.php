@extends('layouts.form')

@php
    $title = 'Kelola Detail Dokumen';
    $singular = 'Detail Dokumen';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th>No</th>
    <th>Dokumen</th>
    <th>Shift</th>
    <th>PIC</th>
@stop

@section('table-body')
    @foreach($details as $index => $detail)
        <tr data-id="{{ $detail->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $detail->document->name }}</td>
            <td>{{ empty($detail->shift) ? '-' : $detail->shift }}</td>
            <td>{{ $detail->employee->nama }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Dokumen</label>
        <select id="documents_id" class="form-control" required>
            <option value="">-- Pilih Dokumen --</option>
            @foreach($documents as $document)
                <option value="{{ $document->id }}">{{ $document->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>PIC</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Karyawan --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Shift</label>
        <select id="shift" class="form-control">
            <option value="">-- Pilih Shift --</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/det-documents/${id}` : '/det-documents';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        documents_id: $('#documents_id').val(),
        employees_id: $('#employees_id').val(),
        shift: $('#shift').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
    if (res.status === 'success') {
        Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
    } else {
        Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/det-documents/${id}`)
            .then(r => r.json())
            .then(detail => {
                $('#item_id').val(detail.id);
                $('#documents_id').val(detail.documents_id);
                $('#employees_id').val(detail.employees_id);
                $('#shift').val(detail.shift);
                $('#modalTitle').text('Edit Detail Dokumen');
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
                fetch(`/det-documents/${id}`, {
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
