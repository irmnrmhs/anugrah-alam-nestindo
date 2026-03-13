@extends('layouts.form')

@php
    $title = 'Kelola Grading Warna';
    $singular = 'Grading Warna';
    $deleteMultipleUrl = '/gcolors/delete-multiple';
    // $importUrl = route('gcolors.import');
    // $templateUrl = route('gcolors.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tanggal</th>
    <th>RBW/Noreg</th>
    <th>Grade</th>
    <th>Kode Bahan Baku</th>
    <th>Jenis Bulu</th>
    <th>Jenis Warna</th>
    <th>Berat</th>
    <th>Biji</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($colors as $index => $color)
        <tr data-id="{{ $color->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $color->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $color->tanggal }}</td>
            <td>{{ $color->rawMaterial->kode }}</td>
            <td>{{ $color->grade }}</td>
            <td>
                {{ (optional(optional($color->rawMaterial->arrivals->first())->dcertificate)->wbhouse->nama) . " / " . optional(optional($color->rawMaterial->arrivals->first())->dcertificate)->wbhouse->kode }}
            </td>
            <td>{{ $color->feather->jenis_bulu }}</td>
            <td>{{ $color->color->jenis_warna }}</td>
            <td>{{ $color->berat }}</td>
            <td>{{ $color->biji }}</td>
            <td>{{ $color->employee->nama }}</td>
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
                <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->nip }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Mulai</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mt-3">
    <div class="border rounded p-3">
        <h6 class="mb-3 fw-bold">Grade Warna</h6>
            @foreach($featherList as $feather)
                <div class="border rounded p-3 mb-4 feather-group" id="feather-group-{{ $feather->id }}">
                    <h6 class="fw-bold">{{ $feather->jenis_bulu }}</h6>

                    <div class="row">
                        @foreach($colorList as $color)
                            <div class="col-md-6 mb-3 combination-field" id="combination-{{ $feather->id }}-{{ $color->id }}">
                                <label class="small fw-semibold">
                                    {{ $color->jenis_warna }}
                                </label>

                                <div class="row">
                                    <div class="col-md-6">
                                        <input type="number"
                                            name="data[{{ $feather->id }}][{{ $color->id }}][biji]"
                                            class="form-control form-control-sm"
                                            placeholder="Biji">
                                    </div>
                                    <div class="col-md-6">
                                        <input type="number"
                                            name="data[{{ $feather->id }}][{{ $color->id }}][berat]"
                                            class="form-control form-control-sm"
                                            placeholder="Berat">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        {{-- <div class="mb-3">
            <label>Hancuran</label>
            <input type="number" id="berat" class="form-control" required>
        </div> --}}
    </div>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="export_controller" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($colors->pluck('rawMaterial')->unique('id') as $rm)
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
    const url = id ? `/gcolors/${id}` : '/gcolors';
    const method = id ? 'PUT' : 'POST';

    const formData = {};

    $('input[name^="data"]').each(function () {
        const name = $(this).attr('name');
        const value = $(this).val();

        if (!value) return;

        const match = name.match(/data\[(\d+)\]\[(\d+)\]\[(\w+)\]/);

        if (!match) return;

        const featherId = match[1];
        const colorId = match[2];
        const field = match[3];

        if (!formData[featherId]) formData[featherId] = {};
        if (!formData[featherId][colorId]) formData[featherId][colorId] = {};

        formData[featherId][colorId][field] = value;
    });

    const data = {
        _token: '{{ csrf_token() }}',
        rms_id: $('#rms_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        grade: $('#grade').val(),
        other: $('#other').val(),
        data: formData,
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

        Promise.all([
            fetch(`/raw-material-info-gc/${id}`).then(r => r.json()),
            fetch(`/gcolor-feathers/${id}`).then(r => r.json())
        ])
        .then(([info, feathers]) => {

            $('#biji_sisa').val(info.biji_sisa);
            $('#berat_sisa').val(info.berat_sisa);
            $('#last').val(info.last_date ?? '-');

            $('.feather-group input').prop('disabled', true);

            feathers.forEach(f => {
                $(`#feather-group-${f.id} input`).prop('disabled', false);
            });

        })
        .catch(() => {
            $('#biji_sisa').val('-');
            $('#berat_sisa').val('-');
            $('#last').val('-');
            $('.feather-group input').prop('disabled', true);
        });
    });

    $(document).on('click', '.btnTambah', function() {

        $('#item_id').val('');

        $('#rms_id').prop('disabled', false).val('');
        $('#employees_id').prop('disabled', false).val('');
        $('#tanggal').val('');
        $('#grade').val('');

        $('input[name^="data"]').val('');

        $('.combination-field').show();
        $('.feather-group').show();
    });

    $('#crudModal').on('hidden.bs.modal', function () {

        $('#rms_id').prop('disabled', false);
        $('#employees_id').prop('disabled', false);
        $('#tanggal').val('');
        $('#grade').val('');
        $('#item_id').val('');

        $('input[name^="data"]').val('');

        $('.feather-group').show();
        $('.combination-field').show();
    });

    $(document).on('click', '.btnEdit', function() {

        const id = $(this).closest('tr').data('id');

        fetch(`/gcolors/${id}`)
            .then(r => r.json())
            .then(data => {

                $('#item_id').val(data.id);

                $('#rms_id')
                    .val(data.rms_id)
                    .trigger('change')
                    .prop('disabled', true);

                $('#employees_id')
                    .val(data.employees_id)
                    .prop('disabled', true);

                $('#tanggal').val(data.tanggal);
                $('#grade').val(data.grade);

                // kosongkan semua input
                $('input[name^="data"]').val('');

                // hide semua kombinasi
                $('.combination-field').hide();

                // hide semua feather group
                $('.feather-group').hide();

                // ambil kombinasi yang sesuai
                const featherId = data.feathers_id;
                const colorId = data.colors_id;

                // tampilkan feather group yang sesuai
                $(`#feather-group-${featherId}`).show();

                // tampilkan kombinasi yang sesuai
                const target = `#combination-${featherId}-${colorId}`;
                $(target).show();

                // set value
                $(`input[name="data[${featherId}][${colorId}][berat]"]`)
                    .val(data.berat);

                $(`input[name="data[${featherId}][${colorId}][biji]"]`)
                    .val(data.biji);

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
                fetch(`/gcolors/${id}`, {
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

        this.action = "{{ route('gcolors.export', ':id') }}"
            .replace(':id', rmId);

        this.method = 'GET';
        this.target = (type === 'pdf') ? '_blank' : '_self';

        this.submit();
    });

@stop