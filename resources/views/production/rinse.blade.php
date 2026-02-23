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
    <th>Petugas</th>
    <th>Tanggal</th>
    <th>Biji</th>
    <th>Shift</th>
    <th>Keterangan</th>
    <th>Hasil Cek</th>
@stop

@section('table-body')
    @foreach($rinses as $index => $rinse)
        <tr data-id="{{ $rinse->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $rinse->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $rinse->history->gcolor->kode }}</td>
            <td>{{ $rinse->employee->nama }}</td>
            <td>{{ $rinse->tanggal }}</td>
            <td>{{ $rinse->biji }}</td>
            <td>{{ $rinse->shift }}</td>
            <td>{{ empty($rinse->keterangan) ? '-' : $rinse->keterangan }}</td>
            <td>
                @if($rinse->cek == 1)
                    <span class="badge bg-success">Lulus Cek</span>
                @else
                    <span class="badge bg-danger">Tidak Lulus Cek</span>
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
                <option value="{{ $history->id }}">{{ $history->grade_rm }}</option>
            @endforeach
        </select>
    </div>
    <div class="row mt-3 justify-content-center">
        <div class="col-md-5">
            <label style="font-size: 10pt">Tanggal Keluar Terakhir</label>
            <input type="text" id="last" class="form-control" readonly>
        </div>

        <div class="col-md-5">
            <label style="font-size: 10pt">Biji Sisa</label>
            <input type="number" id="biji_sisa" class="form-control" readonly>
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
        <label>Tanggal</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
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
        <input type="text" id="keterangan" placeholder="Optional" class="form-control">
    </div>
    <div class="mb-3">
        <label>Hasil Cek</label>
        <select id="cek" class="form-control" required>
            <option value="">-- Lulus/Tidak --</option>
            <option value=1>Lulus</option>
            <option value=0>Tidak Lulus</option>
        </select>
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
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
        shift: $('#shift').val(),
        keterangan: $('#keterangan').val(),
        cek: $('#cek').val(),
    };

    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(async response => {
        const text = await response.text();

        let res;
        try {
            res = JSON.parse(text);
        } catch (e) {
            console.error(text);
            throw { message: 'Response bukan JSON' };
        }

        if (!response.ok) {
            throw res;
        }

        return res;
    })
    .then(res => {
        Swal.fire('Sukses', res.message, 'success')
            .then(() => location.reload());
    })
    .catch(err => {
        let message = 'Terjadi kesalahan';

        if (err.message) {
            message = err.message;
        } else if (err.errors) {
            message = Object.values(err.errors).flat().join('<br>');
        }

        Swal.fire('Gagal', message, 'error');
    });
@stop

@section('custom-js')
    $('#histories_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/rinses-info/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#biji_sisa').val(info.biji_sisa);
                $('#last').val(info.last ?? '-');
            })
            .catch(() => {
                $('#biji_sisa').val('-');
                $('#last').val('-');
            });
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/rinses/${id}`)
            .then(r => r.json())
            .then(rinse => {
                $('#item_id').val(rinse.id);
                $('#histories_id').val(rinse.histories_id).trigger('change');
                $('#employees_id').val(rinse.employees_id);
                $('#tanggal').val(rinse.tanggal);
                $('#biji').val(rinse.biji);
                $('#shift').val(rinse.shift);
                $('#keterangan').val(rinse.keterangan);
                $('#cek').val(rinse.cek);
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
