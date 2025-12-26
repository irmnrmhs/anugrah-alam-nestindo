@extends('layouts.form')

@php
    $title = 'Kelola Data Cabut Bilas';
    $singular = 'Cabut Bilas';
    $deleteMultipleUrl = '/rinses/delete-multiple';
    $hideImportButton = true;
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
    <th>Shift</th>
    <th>Keterangan</th>
    <th>Status</th>
@stop

@section('table-body')
    @foreach($rinses as $index => $rinse)
        <tr data-id="{{ $rinse->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $rinse->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $rinse->history->identifier->kode }}</td>
            <td>{{ $rinse->employee->nama }}</td>
            <td>{{ $rinse->tgl_mulai }}</td>
            <td>{{ $rinse->biji_masuk }}</td>
            <td>{{ $rinse->berat_masuk }}</td>
            <td>{{ empty($rinse->tgl_selesai) ? '-' : $rinse->tgl_selesai }}</td>
            <td>{{ empty($rinse->biji_keluar) ? 0 : $rinse->biji_keluar }}</td>
            <td>{{ empty($rinse->berat_keluar) ? 0 : $rinse->berat_keluar }}</td>
            <td>{{ $rinse->shift }}</td>
            <td>{{ empty($rinse->keterangan) ? '-' : $rinse->keterangan }}</td>
            <td>
                @if($rinse->status == 0)
                <span class="badge bg-warning">Menunggu Persetujuan</span>
                @elseif ($rinse->status == 1)
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
    <div class="mb-3">
        <label>Shift</label>
        <select id="shift" class="form-control" required>
            <option value="">-- Pilih Shift --</option>
            <option value="{{ '1' }}">1</option>
            <option value="{{ '2' }}">2</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/rinses/${id}` : '/rinses';
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
        shift: $('#shift').val(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/rinses/${id}`)
            .then(r => r.json())
            .then(rinse => {
                $('#item_id').val(rinse.id);
                $('#histories_id').val(rinse.histories_id);
                $('#employees_id').val(rinse.employees_id);
                $('#tgl_mulai').val(rinse.tgl_mulai);
                $('#biji_masuk').val(rinse.biji_masuk);
                $('#berat_masuk').val(rinse.berat_masuk);
                $('#tgl_selesai').val(rinse.tgl_selesai);
                $('#biji_keluar').val(rinse.biji_keluar);
                $('#berat_keluar').val(rinse.berat_keluar);
                $('#shift').val(rinse.shift);
                $('#keterangan').val(rinse.keterangan);
                $('#modalTitle').text('Edit Cabut Bilas');
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
                fetch(`/rinses/${id}`, {
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
