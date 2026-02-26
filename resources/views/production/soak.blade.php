@extends('layouts.form')

@php
    $title = 'Kelola Data Perendaman';
    $singular = 'Perendaman';
    $deleteMultipleUrl = '/soaks/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tanggal</th>
    <th>Nama RBW / No. Reg</th>
    <th>Kode Bahan Baku</th>
    <th>Kode Grade</th>
    <th>Jumlah Biji</th>
    <th>Waktu Rendam</th>
    <th>Shift</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($soaks as $index => $soak)
        <tr data-id="{{ $soak->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $soak->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $soak->tanggal }}</td>
            <td>{{ $soak->history->rbw }}</td>
            <td>{{ $soak->history->rm }}</td>
            <td>{{ $soak->history->gcolor->grade }}</td>
            <td>{{ $soak->biji }}</td>
            <td>{{ $soak->durasi }}</td>
            <td>{{ $soak->shift }}</td>
            <td>{{ empty($soak->keterangan) ? '-' : $soak->keterangan }}</td>
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
        <select id="raw_material_id" class="form-control" required>
            <option value="">-- Pilih Kode --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">
                    {{ $rm->kode }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Grade</label>
        <select id="histories_id" class="form-control" required>
            <option value="">-- Pilih Grade --</option>
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
        <label>Durasi (Menit)</label>
        <input type="number" id="durasi" step="1" min="0" max="999" class="form-control">
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
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="export_controller" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($soaks->pluck('history.gcolor.rawMaterial')->unique('id') as $rm)
                <option value="{{ $rm->id }}">
                    {{ $rm->kode }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Shift</label>
        <select name="shift" class="form-control" required>
            <option value="">-- Pilih Shift --</option>
            <option value="1">1</option>
            <option value="2">2</option>
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
    const url = id ? `/soaks/${id}` : '/soaks';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        histories_id: $('#histories_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
        durasi: $('#durasi').val(),
        shift: $('#shift').val(),
        keterangan: $('#keterangan').val()
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
    $('#raw_material_id').on('change', function () {
        const rmId = $(this).val();

        $('#histories_id').html('<option value="">Loading...</option>');

        if (!rmId) {
            $('#histories_id').html('<option value="">-- Pilih Grade --</option>');
            return;
        }

        fetch(`/soaks-grades/${rmId}`)
        .then(r => r.json())
        .then(data => {
            let options = `
                <option value="">-- Pilih Grade --</option>
                <option value="Hancuran">Hancuran</option>
            `;

            data.forEach(history => {
                options += `
                    <option value="${history.id}">
                        ${history.gcolor.grade}
                    </option>
                `;
            });

            $('#histories_id').html(options);
        });
    });

    $('#histories_id').on('change', function () {
        const historyId = $(this).val();

        if (!historyId) return;

        fetch(`/soaks-info/${historyId}`)
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
        fetch(`/soaks/${id}`)
            .then(r => r.json())
            .then(soak => {
                $('#item_id').val(soak.id);
                $('#histories_id').val(soak.histories_id).trigger('change');
                $('#employees_id').val(soak.employees_id);
                $('#tanggal').val(soak.tanggal);
                $('#biji').val(soak.biji);
                $('#durasi').val(soak.durasi);
                $('#shift').val(soak.shift);
                $('#keterangan').val(soak.keterangan);
                $('#modalTitle').text('Edit Perendaman');
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
                fetch(`/soaks/${id}`, {
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

        this.action = "{{ route('soaks.export', ':id') }}"
            .replace(':id', rmId);

        this.method = 'GET';
        this.target = (type === 'pdf') ? '_blank' : '_self';

        this.submit();
    });
@stop
