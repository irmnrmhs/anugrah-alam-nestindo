@extends('layouts.form')

@php
    $title = 'Kelola Grading Bentuk';
    $singular = 'Grading Bentuk';
    $deleteMultipleUrl = '/gshapes/delete-multiple';
    // $importUrl = route('rmstocks.import');
    // $templateUrl = route('rmstocks.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tanggal</th>
    <th>RBW/Noreg</th>
    <th>Kode Bahan Baku</th>
    <th>Jenis Bentuk</th>
    <th>Berat</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($shapes as $index => $shape)
        <tr data-id="{{ $shape->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $shape->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $shape->tanggal }}</td>
            <td>{{ $shape->rawMaterial->kode }}</td>
            <td>
                {{ (optional(optional($shape->rawMaterial->arrivals->first())->dcertificate)->wbhouse->nama) . " / " . optional(optional($shape->rawMaterial->arrivals->first())->dcertificate)->wbhouse->kode }}
            </td>
            <td>{{ $shape->shape->jenis_bentuk }}</td>
            <td>{{ $shape->berat }}</td>
            <td>{{ $shape->employee->nama }}</td>
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
    <div class="row mt-3 justify-content-center">
        <div class="col-md-5">
            <label style="font-size: 10pt">Tanggal Keluar Terakhir</label>
            <input type="text" id="last" class="form-control" readonly>
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
    <div class="mt-4">
    <div class="border rounded p-3">
        <h6 class="mb-3 fw-bold">Grade Bentuk</h6>

        <div class="row">
            @foreach ($shapeList as $shape)
                <div class="col-md-6 mb-3 shape-field" id="shape-{{ $shape->id }}">
                    <label class="form-label small">
                        {{ $shape->jenis_bentuk }}
                    </label>
                    <input type="number"
                        name="berat[{{ $shape->id }}]"
                        step="0.01"
                        min="0"
                        max="99999.99"
                        class="form-control form-control-sm">
                </div>
            @endforeach
        </div>
    </div>
</div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/gshapes/${id}` : '/gshapes';
    const method = id ? 'PUT' : 'POST';

    const beratInputs = {};
    $('input[name^="berat"]').each(function () {
        const name = $(this).attr('name');
        const value = $(this).val();
        const shapeId = name.match(/\d+/)[0];

        if (value && parseFloat(value) > 0) {
            beratInputs[shapeId] = value;
        }
    });

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        berat: beratInputs
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
    $('#rms_id').on('change', function () {
        const id = $(this).val();
        if (!id) return;

        fetch(`/raw-material-info-gs/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#berat_sisa').val(info.berat_sisa);
                $('#last').val(info.last_date ?? '-');
            })
            .catch(() => {
                $('#berat_sisa').val('-');
                $('#last').val('-');
            });
    });

    $(document).on('click', '.btnTambah', function() {
        $('#item_id').val('');
        $('#rms_id').val('');
        $('#employees_id').val('');
        $('#tanggal').val('');

        $('input[name^="berat"]').val('');

        $('.shape-field').show();
    });

    $('#crudModal').on('hidden.bs.modal', function () {
        $('.shape-field').show();
        $('input[name^="berat"]').val('');
        $('#item_id').val('');
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');

        fetch(`/gshapes/${id}`)
            .then(r => r.json())
            .then(data => {

                $('#item_id').val(data.id);
                $('#rms_id').val(data.rms_id).trigger('change');
                $('#employees_id').val(data.employees_id);
                $('#tanggal').val(data.tanggal);

                $('input[name^="berat"]').val('');

                $('.shape-field').hide();

                const shapeId = data.shapes_id;

                $(`#shape-${shapeId}`).show();
                $(`input[name="berat[${shapeId}]"]`).val(data.berat);

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
                fetch(`/gshapes/${id}`, {
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
