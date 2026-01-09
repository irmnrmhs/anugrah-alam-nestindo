@extends('layouts.form')

@php
    $title = 'Kelola Data Pengeringan';
    $singular = 'Pengeringan';
    $deleteMultipleUrl = '/dries/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Produk</th>
    <th>Petugas</th>
    <th>Tanggal Mulai</th>
    <th>Jumlah Biji</th>
    <th>Berat</th>
    <th>Waktu Masuk</th>
    <th>Tanggal Selesai</th>
    <th>Biji Keluar</th>
    <th>Berat Keluar</th>
    <th>Waktu Keluar</th>
    <th>Shift</th>
    <th>Keterangan</th>
    <th>Status</th>
@stop

@section('table-body')
    @foreach($dries as $index => $dry)
        <tr data-id="{{ $dry->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $dry->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $dry->history->identifier->kode }}</td>
            <td>{{ $dry->employee->nama }}</td>
            <td>{{ $dry->tgl_mulai }}</td>
            <td>{{ $dry->biji_masuk }}</td>
            <td>{{ $dry->berat_masuk }}</td>
            <td>{{ $dry->waktu_masuk }}</td>
            <td>{{ empty($dry->tgl_selesai) ? '-' : $dry->tgl_selesai }}</td>
            <td>{{ empty($dry->biji_keluar) ? 0 : $dry->biji_keluar }}</td>
            <td>{{ empty($dry->berat_keluar) ? 0 : $dry->berat_keluar }}</td>
            <td>{{ empty($dry->waktu_keluar) ? '-' : $dry->waktu_keluar }}</td>
            <td>{{ $dry->shift }}</td>
            <td>{{ empty($dry->keterangan) ? '-' : $dry->keterangan }}</td>
            <td>
                @if($dry->status == 0)
                <span class="badge bg-warning">Menunggu Persetujuan</span>
                @elseif ($dry->status == 1)
                <span class="badge bg-success">Disetujui</span>
                @else
                <span class="badge bg-danger">Ditolak</span>
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
        <label>Kode</label>
        <select id="histories_id" class="form-control" required>
            <option value="">-- Pilih Produk --</option>
            @foreach($histories as $history)
                <option value="{{ $history->id }}">{{ $history->identifier->kode }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mt-3">
        <div class="col-md-4">
            <label style="font-size: 10pt">Tanggal Keluar Terakhir</label>
            <input type="text" id="last" class="form-control" readonly>
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
        <label>Tanggal Mulai</label>
        <input type="date" id="tgl_mulai" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji Sebelum Proses</label>
        <input type="number" id="biji_masuk" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat Sebelum Proses</label>
        <input type="number" id="berat_masuk" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Waktu Masuk</label>
        <input type="time" id="waktu_masuk" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal Selesai</label>
        <input type="date" id="tgl_selesai" class="form-control">
    </div>
    <div class="mb-3">
        <label>Biji Setelah Proses</label>
        <input type="number" id="biji_keluar" step="1" min="0" class="form-control">
    </div>
    <div class="mb-3">
        <label>Berat Setelah Proses</label>
        <input type="number" id="berat_keluar" step="0.001" min="0" max="99999.99" class="form-control">
    </div>
    <div class="mb-3">
        <label>Waktu Keluar</label>
        <input type="time" id="waktu_keluar" class="form-control">
    </div>
    <div class="mb-3">
        <label>Shift</label>
        <select id="shift" class="form-control" required>
            <option value="">-- Pilih Shift --</option>
            <option value="1">1</option>
            <option value="2">2</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/dries/${id}` : '/dries';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        histories_id: $('#histories_id').val(),
        employees_id: $('#employees_id').val(),
        tgl_mulai: $('#tgl_mulai').val(),
        biji_masuk: $('#biji_masuk').val(),
        berat_masuk: $('#berat_masuk').val(),
        waktu_masuk: $('#waktu_masuk').val(),
        tgl_selesai: $('#tgl_selesai').val(),
        biji_keluar: $('#biji_keluar').val(),
        berat_keluar: $('#berat_keluar').val(),
        waktu_keluar: $('#waktu_keluar').val(),
        shift: $('#shift').val(),
        keterangan: $('#keterangan').val(),
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
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap.', 'error'));
@stop

@section('custom-js')
    $('#histories_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/dries-info/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#biji_sisa').val(info.biji_sisa);
                $('#berat_sisa').val(info.berat_sisa);
                $('#last').val(info.last ?? '-');
            })
            .catch(() => {
                $('#biji_sisa').val('-');
                $('#berat_sisa').val('-');
                $('#last').val('-');
            });
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/dries/${id}`)
            .then(r => r.json())
            .then(dry => {
                $('#item_id').val(dry.id);
                $('#histories_id').val(dry.histories_id).trigger('change');
                $('#employees_id').val(dry.employees_id);
                $('#tgl_mulai').val(dry.tgl_mulai);
                $('#biji_masuk').val(dry.biji_masuk);
                $('#berat_masuk').val(dry.berat_masuk);
                $('#waktu_masuk').val(dry.waktu_masuk);
                $('#tgl_selesai').val(dry.tgl_selesai);
                $('#biji_keluar').val(dry.biji_keluar);
                $('#berat_keluar').val(dry.berat_keluar);
                $('#waktu_keluar').val(dry.waktu_keluar);
                $('#keterangan').val(dry.keterangan);
                $('#shift').val(dry.shift);
                $('#modalTitle').text('Edit Masuk Cetak');
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
                fetch(`/dries/${id}`, {
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
