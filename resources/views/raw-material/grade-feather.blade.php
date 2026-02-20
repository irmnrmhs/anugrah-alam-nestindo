@extends('layouts.form')

@php
    $title = 'Kelola Grading Bulu';
    $singular = 'Grading Bulu';
    $deleteMultipleUrl = '/gfeathers/delete-multiple';
    // $importUrl = route('gfeathers.import');
    // $templateUrl = route('gfeathers.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tanggal</th>
    <th>RBW/Noreg</th>
    <th>Kode Bahan Baku</th>
    <th>Jenis Bentuk</th>
    <th>Berat</th>
    <th>Biji</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($feathers as $index => $feather)
        <tr data-id="{{ $feather->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $feather->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $feather->tanggal }}</td>
            <td>{{ $feather->rawMaterial->kode }}</td>
            <td>
                {{ (optional(optional($feather->rawMaterial->arrivals->first())->dcertificate)->wbhouse->nama) . " / " . optional(optional($feather->rawMaterial->arrivals->first())->dcertificate)->wbhouse->kode }}
            </td>
            <td>{{ $feather->feather->jenis_bulu }}</td>
            <td>{{ $feather->berat }}</td>
            <td>{{ $feather->biji }}</td>
            <td>{{ $feather->employee->nama }}</td>
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
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mt-3">
    <div class="border rounded p-3">
        <h6 class="mb-3 fw-bold">Grade Bulu</h6>
            @foreach ($featherList as $feather)
                <div class="mb-3 feather-field" id="feather-{{ $feather->id }}">
                    <label class="form-label fw-semibold small">
                        {{ $feather->jenis_bulu }}
                    </label>

                    <div class="row">
                        <div class="col-md-6">
                            <label class="small">Biji</label>
                            <input type="number"
                                name="biji[{{ $feather->id }}]"
                                step="1"
                                min="0"
                                class="form-control form-control-sm">
                        </div>
                        <div class="col-md-6">
                            <label class="small">Berat</label>
                            <input type="number"
                                name="berat[{{ $feather->id }}]"
                                step="0.01"
                                min="0"
                                max="99999.99"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="export_controller" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($feathers->pluck('rawMaterial')->unique('id') as $rm)
                <option value="{{ $rm->id }}">
                    {{ $rm->kode }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Format</label>
        <select name="type" class="form-control" required>
            <option value="">-- Pilih Format --</option>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
        </select>
    </div>
@endsection

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/gfeathers/${id}` : '/gfeathers';
    const method = id ? 'PUT' : 'POST';

    const beratInputs = {};
    const bijiInputs = {};
    $('input[name^="berat"]').each(function () {
        const name = $(this).attr('name');
        const value = $(this).val();
        const featherId = name.match(/\d+/)[0];

        if (value && parseFloat(value) > 0) {
            beratInputs[featherId] = value;
        }
    });

    $('input[name^="biji"]').each(function () {
        const name = $(this).attr('name');
        const value = $(this).val();
        const featherId = name.match(/\d+/)[0];

        if (value && parseFloat(value) > 0) {
            bijiInputs[featherId] = value;
        }
    });

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        berat: beratInputs,
        biji: bijiInputs,
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

        fetch(`/raw-material-info-gf/${id}`)
            .then(r => r.json())
            .then(info => {
                $('#biji_sisa').val(info.biji_sisa);
                $('#berat_sisa').val(info.berat_sisa);
                $('#last').val(info.last_date ?? '-');
            })
            .catch(() => {
                $('#biji_sisa').val('-');
                $('#berat_sisa').val('-');
                $('#last').val('-');
            });
    });

    $(document).on('click', '.btnTambah', function() {
        $('#item_id').val('');

        // aktifkan kembali select
        $('#rms_id').prop('disabled', false).val('');
        $('#employees_id').prop('disabled', false).val('');

        $('#tanggal').val('');

        $('input[name^="berat"]').val('');
        $('input[name^="biji"]').val('');

        $('.feather-field').show();
    });
    
    $('#crudModal').on('hidden.bs.modal', function () {
        $('#rms_id').prop('disabled', false);
        $('#employees_id').prop('disabled', false);

        $('.feather-field').show();
        $('input[name^="berat"]').val('');
        $('input[name^="biji"]').val('');
        $('#item_id').val('');
    });
    
    $(document).on('click', '.btnEdit', function() {

        const id = $(this).closest('tr').data('id');

        fetch(`/gfeathers/${id}`)
            .then(r => r.json())
            .then(data => {

                $('#item_id').val(data.id);
                $('#rms_id').val(data.rms_id).trigger('change').prop('disabled', true);
                $('#employees_id').val(data.employees_id).prop('disabled', true);
                $('#tanggal').val(data.tanggal);

                $('input[name^="berat"]').val('');
                $('input[name^="biji"]').val('');

                $('.feather-field').hide();

                const featherId = data.feathers_id;

                $(`#feather-${featherId}`).show();
                $(`input[name="berat[${featherId}]"]`).val(data.berat);
                $(`input[name="biji[${featherId}]"]`).val(data.biji);

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
                fetch(`/gfeathers/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message || 'Tidak bisa menghapus data', 'error');
                    }
                })
                .catch(() =>
                    Swal.fire('Error',
                        'Gagal menghapus data. Pastikan data tidak terintegrasi dengan data lainnya.',
                        'error')
                );
            }
        });
    });

    $('#exportForm').on('submit', function (e) {

        e.preventDefault();

        const rmId = $('#export_controller').val();
        const type = $('select[name="type"]').val();

        if (!rmId) {
            Swal.fire('Oops', 'Pilih kode bahan baku terlebih dahulu', 'warning');
            return;
        }

        this.action = "{{ route('gfeathers.export', ':id') }}"
            .replace(':id', rmId);

        this.method = 'GET';
        this.target = (type === 'pdf') ? '_blank' : '_self';

        this.submit();
    });

@stop
