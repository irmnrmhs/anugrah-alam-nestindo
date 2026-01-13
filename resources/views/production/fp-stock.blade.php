@extends('layouts.form')

@php
    $title = 'Kelola Stok Produk Jadi';
    $singular = 'Stok Produk Jadi';
    $deleteMultipleUrl = '/fp-stocks/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Produk Jadi</th>
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
            <td>{{ $stock->fproduct->kode }}</td>
            <td>{{ $stock->employee->nama }}</td>
            <td>{{ empty($stock->tgl_keluar) ? '-' : $stock->tgl_keluar }}</td>
            <td>{{ empty($stock->biji_keluar) ? 0 : $stock->biji_keluar }}</td>
            <td>{{ empty($stock->berat_keluar) ? 0 : $stock->berat_keluar }}</td>
            <td>{{ empty($stock->keterangan) ? '-' : $stock->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Produk Jadi</label>
        <select id="fproducts_id" class="form-control" required>
            <option value="">-- Pilih Produk Jadi --</option>
            @foreach($fproducts as $fproduct)
                <option value="{{ $fproduct->id }}">{{ $fproduct->kode }}</option>
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
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
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
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/fp-stocks/${id}` : '/fp-stocks';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        fproducts_id: $('#fproducts_id').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data', 'error'));
@stop

@section('custom-js')
    $('#fproducts_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/product-info/${id}`)
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
        fetch(`/fp-stocks/${id}`)
            .then(r => r.json())
            .then(stock => {
                $('#item_id').val(stock.id);
                $('#fproducts_id').val(stock.fproducts_id).trigger('change');
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
                fetch(`/fp-stocks/${id}`, {
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
