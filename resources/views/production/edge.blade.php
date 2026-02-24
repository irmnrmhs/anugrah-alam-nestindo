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
    <th>Hancuran</th>
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
            <td>{{ empty($edge->hancuran) ? '0' : $edge->hancuran }}</td>
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
            @foreach($rawMaterials as $rm)
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
            <label id="sisa_label" style="font-size: 10pt">Biji Sisa</label>
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
        <label>Tanggal Mulai</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Biji</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select id="export_controller" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($edges->pluck('history.gcolor.rawMaterial')->unique('id') as $rm)
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
    const url = id ? `/edges/${id}` : '/edges';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        histories_id: $('#histories_id').val(),
        employees_id: $('#employees_id').val(),
        tanggal: $('#tanggal').val(),
        biji: $('#biji').val(),
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

        fetch(`/edges-grades/${rmId}`)
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
        const value = $(this).val();

        if (!value) {
            $('#biji_sisa').val('');
            return;
        }

        if (value === 'Hancuran') {

            $('#sisa_label').text('Hancuran Sisa');

            const rmId = $('#raw_material_id').val();

            fetch(`/edges-hancuran-info/${rmId}`)
                .then(r => r.json())
                .then(info => {
                    $('#biji_sisa').val(info.hcr_sisa);
                    $('#last').val(info.last ?? '-');
                })
                .catch(() => {
                    $('#biji_sisa').val('-');
                    $('#last').val('-');
                });

            return;
        }

        $('#sisa_label').text('Biji Sisa');

        fetch(`/edges-info/${value}`)
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
        fetch(`/edges/${id}`)
            .then(r => r.json())
            .then(edge => {
                $('#item_id').val(edge.id);
                $('#histories_id').val(edge.histories_id).trigger('change');
                $('#employees_id').val(edge.employees_id);
                $('#tanggal').val(edge.tanggal);
                $('#biji').val(edge.biji);
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

    $('#exportForm').on('submit', function (e) {
        e.preventDefault();

        const rmId = $('#export_controller').val();
        const type = $('select[name="type"]').val();

        if (!rmId) {
            Swal.fire('Oops', 'Pilih kode bahan baku terlebih dahulu', 'warning');
            return;
        }

        this.action = "{{ route('edges.export', ':id') }}"
            .replace(':id', rmId);

        this.method = 'GET';
        this.target = (type === 'pdf') ? '_blank' : '_self';

        this.submit();
    });
@stop
