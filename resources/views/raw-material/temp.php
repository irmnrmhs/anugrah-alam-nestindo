@extends('layouts.form')

@php
    $title = 'Kelola Stok Bahan Baku';
    $singular = 'Stok Bahan Baku';
    $deleteMultipleUrl = '/rmstocks/delete-multiple';
    // $importUrl = route('rmstocks.import');
    // $templateUrl = route('rmstocks.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Bahan Baku</th>
    <th>Petugas</th>
    <th>Tanggal Keluar</th>
    <th>Biji Keluar</th>
    <th>Berat Keluar</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($stocks as $index => $stock)
        <tr data-id="{{ $stock->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $stock->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $stock->rawMaterial->kode }}</td>
            <td>{{ $stock->employee->nama }}</td>
            <td>{{ $stock->tgl_keluar }}</td>
            <td>{{ $stock->biji_keluar }}</td>
            <td>{{ $stock->berat_keluar }}</td>
            <td>{{ empty($stock->keterangan) ? '-' : $stock->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
                <a href="{{ route('rmstocks.export', $stock->id) }}" class="btn btn-sm btn-primary" target="_blank">Cetak Form</a>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Bahan Baku</label>
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
        <label>Petugas</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Petugas --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->nip }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Keluar</label>
        <input type="date" id="tgl_keluar" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji Keluar</label>
        <input type="number" id="biji_keluar" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Keluar</label>
        <input type="number" id="berat_keluar" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" placeholder="Optional" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/rmstocks/${id}` : '/rmstocks';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        employees_id: $('#employees_id').val(),
        tgl_keluar: $('#tgl_keluar').val(),
        biji_keluar: $('#biji_keluar').val(),
        berat_keluar: $('#berat_keluar').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data', 'error'));
@stop

@section('custom-js')
    $('#rms_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/raw-material-info/${id}`)
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
        fetch(`/rmstocks/${id}`)
            .then(r => r.json())
            .then(stock => {
                $('#item_id').val(stock.id);
                $('#rms_id').val(stock.rms_id).trigger('change');
                $('#employees_id').val(stock.employees_id);
                $('#tgl_keluar').val(stock.tgl_keluar);
                $('#biji_keluar').val(stock.biji_keluar);
                $('#berat_keluar').val(stock.berat_keluar);
                $('#keterangan').val(stock.keterangan);
                $('#modalTitle').text('Edit Stok Bahan Baku');
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
                fetch(`/rmstocks/${id}`, {
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
