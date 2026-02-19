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
    <th>Tanggal Kedatangan</th>
    <th>RBW/Noreg</th>
    <th>Kode Bahan Baku</th>
    <th>Tanggal Keluar</th>
    <th>Biji</th>
    <th>Berat</th>
    <th>Keterangan</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($stocks as $index => $stock)
        <tr data-id="{{ $stock->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $stock->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>
                {{ optional($stock->rawMaterial->arrivals->sortBy('tgl_kedatangan')->first())->tgl_kedatangan ?? '-' }}
            </td>
            <td>
                <!-- {{ (optional(optional($stock->rawMaterial->arrivals->first())->dcertificate)->wbhouse->nama) . " / " . optional(optional($stock->rawMaterial->arrivals->first())->dcertificate)->wbhouse->kode }} -->
                {{ $stock->rawMaterial->rbw }}
            </td>
            <td>{{ $stock->rawMaterial->kode }}</td>
            <td>{{ $stock->tanggal }}</td>
            <td>{{ $stock->biji }}</td>
            <td>{{ $stock->berat }}</td>
            <td>{{ empty($stock->keterangan) ? '-' : $stock->keterangan }}</td>
            <td>{{ $stock->employee->nama }}</td>
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
            <option value="">-- Kode Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Tanggal Keluar</label>
        <input type="date" id="tanggal" class="form-control">
    </div>

    <div class="mb-3">
        <label>Jumlah Stok</label>
        <input type="number" min="1" id="jumlah_stok" class="form-control" required>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="export_controller" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($stocks->pluck('rawMaterial')->unique('id') as $rm)
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
    const rms_id = $('#rms_id').val();
    const tanggal = $('#tanggal').val();
    const jumlah = parseInt($('#jumlah_stok').val());

    // if (!rms_id || jumlah < 1) {
    //    Swal.fire('Error', 'Lengkapi Kode & Jumlah Stok!', 'error');
    //    return;
    // }

    bootstrap.Modal.getInstance(document.getElementById('crudModal')).hide();

    let html = `
    <div class="row mt-3 mb-3">
        <div class="col-md-4">
            <label style="font-size: 10pt">Tanggal Keluar Terakhir</label>
            <input type="text" id="last_out_date" class="form-control" readonly value="${window.last_date}">
        </div>
        <div class="col-md-4">
            <label style="font-size: 10pt">Biji Sisa</label>
            <input type="number" id="biji_sisa" class="form-control" readonly value="${window.biji_sisa}">
        </div>
        <div class="col-md-4">
            <label style="font-size: 10pt">Berat Sisa</label>
            <input type="number" id="berat_sisa" class="form-control" readonly value="${window.berat_sisa}">
        </div>
    </div>`;

    for (let i = 1; i <= jumlah; i++) {
        html += `
        <div class="border rounded p-3 mb-3">
            <h6>Kontainer ${i}</h6>

            <label>Biji</label>
            <input type="number" class="form-control mb-2 c-biji" data-index="${i}" min="0" required>

            <label>Berat</label>
            <input type="number" class="form-control mb-2 c-berat" data-index="${i}" min="0" step="0.01" required>

            <label>Keterangan</label>
            <input type="text" class="form-control mb-2 c-keterangan" data-index="${i}" placeholder="Optional (tidak wajib diisi)">

            <label>Petugas</label>
            <select class="form-control c-petugas" data-index="${i}" required>
                <option value="">-- Pilih Petugas --</option>
                @foreach($employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->nama }} ({{ $employee->nip }})</option>
                @endforeach
            </select>
        </div>
        `;
    }

    $('#secondModalBody').html(html);
    $('#secondModal').modal('show');

    $('#btnSubmitAll').off().on('click', function () {
        let list = [];

        for (let i = 1; i <= jumlah; i++) {
            list.push({
                rms_id,
                tanggal,
                employees_id: $(`.c-petugas[data-index="${i}"]`).val(),
                biji: $(`.c-biji[data-index="${i}"]`).val(),
                berat: $(`.c-berat[data-index="${i}"]`).val(),
                keterangan: $(`.c-keterangan[data-index="${i}"]`).val(),
            });
        }

        fetch('/rmstocks/bulk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: list })
        })
        .then(async res => {
            const data = await res.json();
            if (!res.ok) throw data;
            return data;
        })
        .then(res => {
            Swal.fire('Berhasil', res.message, 'success')
                .then(() => location.reload());
        })
        .catch(err => {
            Swal.fire('Error', err.message, 'error');
        });
    });
@stop

@section('custom-js')
    $('#rms_id').on('change', function () {
        const rms_id = $(this).val();
        if (!rms_id) return;

        fetch(`/raw-material-info/${rms_id}`)
            .then(r => r.json())
            .then(info => {
                window.last_date = info.last_date ?? '-';
                window.biji_sisa = info.biji_sisa ?? 0;
                window.berat_sisa = info.berat_sisa ?? 0;

                $('#biji_sisa').val(window.biji_sisa);
                $('#berat_sisa').val(window.berat_sisa);
                $('#last_out_date').val(window.last_date);
            });
    });

    $(document).on('click', '.btnEdit', function () {
        const id = $(this).closest('tr').data('id');

        fetch(`/rmstocks/${id}`)
            .then(r => r.json())
            .then(rmstock => {

                $('#item_id').val(rmstock.id);

                let html = `
                    <label>Tanggal</label>
                    <input type="date" class="form-control mb-2" id="edit_tgl"
                        value="${rmstock.tanggal}" min="0">

                    <label>Biji</label>
                    <input type="number" class="form-control mb-2" id="edit_biji"
                        value="${rmstock.biji}" min="0">

                    <label>Berat</label>
                    <input type="number" class="form-control mb-2" id="edit_berat"
                        value="${rmstock.berat}" min="0" step="0.01">

                    <label>Keterangan</label>
                    <input type="text" class="form-control mb-2" id="edit_keterangan"
                        value="${rmstock.keterangan ?? ''}">

                    <label>Petugas</label>
                    <select class="form-control" id="edit_petugas">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                ${rmstock.employees_id == "{{ $employee->id }}" ? 'selected' : ''}>
                                {{ $employee->nama }} ({{ $employee->nip }})
                            </option>
                        @endforeach
                    </select>
                `;

                $('#secondModalBody').html(html);
                $('#secondModal').modal('show');

                $('#btnSubmitAll').off().on('click', function () {

                    let payload = {
                        rms_id: rmstock.rms_id,
                        tanggal: $('#edit_tgl').val(),
                        biji: parseInt($('#edit_biji').val()),
                        berat: parseFloat($('#edit_berat').val()),
                        keterangan: $('#edit_keterangan').val() || null,
                        employees_id: parseInt($('#edit_petugas').val())
                    };

                    fetch(`/rmstocks/${rmstock.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(async res => {
                        const data = await res.json();
                        if (!res.ok) throw data;
                        return data;
                    })
                    .then(res => {
                        Swal.fire('Berhasil', res.message, 'success')
                            .then(() => location.reload());
                    })
                    .catch(err => {
                        Swal.fire('Error', err.message, 'error');
                    });
                });
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

    $('#exportForm').on('submit', function (e) {
        e.preventDefault();

        const rmId = $('#export_controller').val();
        const type = $('select[name="type"]').val();

        if (!rmId) {
            Swal.fire('Oops', 'Pilih kode bahan baku terlebih dahulu', 'warning');
            return;
        }

        this.action = "{{ route('rmstocks.export', ':id') }}"
            .replace(':id', rmId);

        this.method = 'GET';
        this.target = (type === 'pdf') ? '_blank' : '_self';

        this.submit();
    });
@stop

@section('content')
@parent

<div class="modal fade" id="secondModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Input Detail Stok</h5>
            </div>

            <div class="modal-body overflow-auto" id="secondModalBody" style="max-height: 70vh;">
                {{-- auto generated --}}
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" id="btnSubmitAll">Simpan Semua</button>
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
            </div>

        </div>
    </div>
</div>
@endsection