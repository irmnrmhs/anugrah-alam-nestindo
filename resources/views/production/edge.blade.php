@extends('layouts.form')

@php
    $title = 'Kelola Data Sesek Kaki';
    $singular = 'Sesek Kaki';
    $deleteMultipleUrl = '/edges/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Produk</th>
    <th>Petugas</th>
    <th>Tanggal</th>
    <th>Jumlah Biji</th>
    <th>Berat</th>
@stop

@section('table-body')
    @foreach($edges as $index => $edge)
        <tr data-id="{{ $edge->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $edge->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $edge->history->gcolor->grade }}</td>
            <td>{{ $edge->employee->nama }}</td>
            <td>{{ $edge->tanggal }}</td>
            <td>{{ $edge->biji }}</td>
            <td>{{ $edge->berat }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
                <a href="{{ route('edges.export', $edge->id) }}" class="btn btn-sm btn-primary" target="_blank">Cetak Form</a>
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
                <option value="{{ $history->id }}" data-kode="{{ $history->gcolor->rawMaterial->kode }}">
                    {{ $history->gcolor->rawMaterial->kode }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="row mt-3 justify-content-center">
        <div class="col-md-5">
            <label style="font-size: 10pt">Biji Sisa</label>
            <input type="number" id="biji_sisa" class="form-control" readonly>
        </div>

        <div class="col-md-5">
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
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="row">
        @foreach($grades as $grade)
            <div class="col-md-4 mb-3">
                <div class="card p-3 h-100 grade-card" data-kode="{{ $grade->kode_bahan_baku }}">
                    <h6 class="text-center">{{ $grade->grade }}</h6>

                    <input type="number"
                        name="biji[]"
                        class="form-control mb-2"
                        placeholder="Biji"
                        min="0">

                    <input type="number"
                        name="berat[]"
                        class="form-control"
                        placeholder="Berat"
                        step="0.01"
                        min="0">
                </div>
            </div>
        @endforeach
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/edges/${id}` : '/edges';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        histories_id: $('#histories_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
        berat: $('#berat').val(),
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
    {{-- $('#histories_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/edges-info/${id}`)
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
    }); --}}

    $('#histories_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/edges-info/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#biji_sisa').val(info.biji_sisa);
                $('#berat_sisa').val(info.berat_sisa);
                $('#last').val(info.last ?? '-');

                const kodeBahanBaku = info.kode_bahan_baku;

                $('.grade-card').each(function () {
                    const gradeKode = $(this).data('kode');
                    if (gradeKode === kodeBahanBaku) {
                        $(this).find('input').prop('disabled', false);
                        $(this).removeClass('disabled-card'); // opsional styling
                    } else {
                        $(this).find('input').prop('disabled', true);
                        $(this).addClass('disabled-card'); // opsional styling
                    }
                });
            })
            .catch(() => {
                $('#biji_sisa').val('-');
                $('#berat_sisa').val('-');
                $('#last').val('-');
                $('.grade-card').find('input').prop('disabled', false).removeClass('disabled-card');
            });
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/edges/${id}`)
            .then(r => r.json())
            .then(edge => {
                $('#item_id').val(edge.id);
                $('#histories_id').val(edge.histories_id).trigger('change');
                $('#employees_id').val(edge.employees_id);
                $('#tanggal').val(edge.tanggal);
                $('#biji').val(edge.biji);
                $('#berat').val(edge.berat);
                $('#modalTitle').text('Edit Sesek Kaki');
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
