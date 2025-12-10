@extends('layouts.form')

@php
    $title = 'Kelola Data Sesek Kaki';
    $singular = 'Sesek Kaki';
    $deleteMultipleUrl = '/edges/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
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
            <td>
                @if($edge->status == 0)
                    <span class="badge bg-warning">Menunggu Persetujuan</span>
                @elseif ($edge->status == 1)
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
    <div class="mb-3">
        <label>Karyawan</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Karyawan --</option>
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
    {{-- <div class="mb-3">
        <label>Status</label>
        <select id="status" class="form-control" required>
            <option value="">-- Pilih Status --</option>
            <option value="{{ 0 }}">{{ 'Menunggu Persetujuan' }}</option>
            <option value="{{ 1 }}">{{ 'Disetujui' }}</option>
            <option value="{{ 2 }}">{{ 'Ditolak' }}</option>
        </select>
    </div> --}}
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/edges/${id}` : '/edges';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        histories_id: $('#histories_id').val(),
        employees_id: $('#employees_id').val(),
        tgl_mulai: $('#tgl_mulai').val(),
        biji_masuk: $('#biji_masuk').val(),
        berat_masuk: $('#berat_masuk').val(),
        tgl_selesai: $('#tgl_selesai').val(),
        biji_keluar: $('#biji_keluar').val(),
        berat_keluar: $('#berat_keluar').val(),
        {{-- status: $('#status').val() --}}
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan data diisi lengkap.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/edges/${id}`)
            .then(r => r.json())
            .then(edge => {
                $('#item_id').val(edge.id);
                $('#histories_id').val(edge.histories_id);
                $('#employees_id').val(edge.employees_id);
                $('#tgl_mulai').val(edge.tgl_mulai);
                $('#biji_masuk').val(edge.biji_masuk);
                $('#berat_masuk').val(edge.berat_masuk);
                $('#tgl_selesai').val(edge.tgl_selesai);
                $('#biji_keluar').val(edge.biji_keluar);
                $('#berat_keluar').val(edge.berat_keluar);
                {{-- $('#status').val(edge.status); --}}
                $('#modalTitle').text('Edit Sesek Kaki');
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
                fetch(`/edges/${id}`, {
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
