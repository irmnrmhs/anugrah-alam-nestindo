@extends('layouts.form')

@php
    $title = 'Kelola Pengidentifikasi Produk';
    $singular = 'Pengidentifikasi Produk';
    $deleteMultipleUrl = '/identifiers/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
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
            <td><input type="checkbox" class="row-check" value="{{ $identifier->id }}"></td>
            <td>{{ $index + 1 }}</td>
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
        <label>Kode Bahan Baku</label>
        <select id="rms_id" class="form-control" required>
            <option value="">-- Pilih Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <label style="font-size: 10pt">Tanggal Keluar Terakhir</label>
            <input type="text" id="last_out_date" class="form-control" readonly>
        </div>

        <div class="col-md-4">
            <label style="font-size: 10pt">Biji Sisa</label>
            <input type="number" id="biji_sisa" class="form-control" readonly>
        </div>

        <div class="col-md-4">
            <label style="font-size: 10pt">Berat Sisa</label>
            <input type="number" id="berat_sisa" class="form-control" readonly>
        </div>
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
        rms_id: $('#rms_id').val(),
        grades_id: $('#grades_id').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode grade tidak sama. Jika sama maka ubah pada grade yang sesuai.', 'error'));
@stop

@section('custom-js')
    $('#rms_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/raw-material-info-pi/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#biji_sisa').val(info.biji_sisa);
                $('#berat_sisa').val(info.berat_sisa);
                $('#last_out_date').val(info.last_date ?? '-');
            })
            .catch(() => {
                $('#biji_sisa').val('-');
                $('#berat_sisa').val('-');
                $('#last_out_date').val('-');
            });
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/identifiers/${id}`)
            .then(r => r.json())
            .then(identifier => {
                $('#item_id').val(identifier.id);
                $('#rms_id').val(identifier.rms_id);
                $('#grades_id').val(identifier.grades_id);
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
            title: 'Anda Yakin?',
            text: 'Data tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya',
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
                })
                .catch(() => Swal.fire('Error', 'Gagal menghapus data. Pastikan data tidak terintegrasi dengan data lainnya.', 'error'));
            }
        });
    });
@stop