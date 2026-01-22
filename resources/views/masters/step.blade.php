@extends('layouts.form')

@php
    $title = 'Kelola Daftar Proses';
    $singular = 'Daftar Proses';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Proses</th>
    <th>PIC</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($steps as $index => $step)
        <tr data-id="{{ $step->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $step->kode }}</td>
            <td>{{ $step->proses }}</td>
            <td>{{ $step->employee->nama }}</td>
            <td>{{ empty($step->ket) ? '-' : $step->ket }}</td>
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
        <input type="text" id="kode" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Proses</label>
        <input type="text" id="proses" class="form-control" required>
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
        <label>Keterangan</label>
        <input type="text" id="ket" placeholder="Optional" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/steps/${id}` : '/steps';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        proses: $('#proses').val(),
        employees_id: $('#employees_id').val(),
        ket: $('#ket').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode dan nama proses tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/steps/${id}`)
            .then(r => r.json())
            .then(step => {
                $('#item_id').val(step.id);
                $('#kode').val(step.kode);
                $('#employees_id').val(step.employees_id);
                $('#proses').val(step.proses);
                $('#ket').val(step.ket);
                $('#modalTitle').text('Edit Proses Kerja');
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
                fetch(`/steps/${id}`, {
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
