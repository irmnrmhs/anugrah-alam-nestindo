@extends('layouts.form')

@php
    $title = 'Kelola Kontainer';
    $singular = 'Kontainer';
    $deleteMultipleUrl = '/containers/delete-multiple';
    // $importUrl = route('containers.import');
    // $templateUrl = route('containers.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode</th>
    <th>Biji</th>
    <th>Berat</th>
    <th>Keterangan</th>
    <th>Petugas</th>
@stop

@section('table-body')
    @foreach($containers as $index => $container)
        <tr data-id="{{ $container->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $container->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $container->arrival->kode }}</td>
            <td>{{ $container->biji }}</td>
            <td>{{ $container->berat }}</td>
            <td>{{ empty($container->keterangan) ? '-' : $container->keterangan }}</td>
            <td>{{ $container->employee->nama }}</td>
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
        <select id="arrivals_id" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach($arrivals as $arrival)
                <option value="{{ $arrival->id }}">{{ $arrival->kode }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Jumlah Kontainer</label>
        <input type="number" min="1" id="jumlah_kontainer" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const arrivals_id = $('#arrivals_id').val();
    const jumlah = parseInt($('#jumlah_kontainer').val());

    if (!arrivals_id || jumlah < 1) {
        Swal.fire('Error', 'Lengkapi Kode & Jumlah Kontainer!', 'error');
        return;
    }

    bootstrap.Modal.getInstance(document.getElementById('crudModal')).hide();

    let html = '';
    for (let i = 1; i <= jumlah; i++) {
        html += `
            <div class="border rounded p-3 mb-3">
                <h6>Kontainer ${i}</h6>

                <label>Biji</label>
                <input type="number" class="form-control mb-2 kont-biji" data-index="${i}" min="0" required>

                <label>Berat</label>
                <input type="number" class="form-control mb-2 kont-berat" data-index="${i}" min="0" step="0.01" required>

                <label>Keterangan</label>
                <input type="text" class="form-control mb-2 kont-keterangan" data-index="${i}" placeholder="Optional (tidak wajib diisi)">

                <label>Petugas</label>
                <select class="form-control kont-petugas" data-index="${i}" required>
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
                arrivals_id,
                biji: $(`.kont-biji[data-index="${i}"]`).val(),
                berat: $(`.kont-berat[data-index="${i}"]`).val(),
                keterangan: $(`.kont-keterangan[data-index="${i}"]`).val(),
                employees_id: $(`.kont-petugas[data-index="${i}"]`).val(),
            });
        }

        fetch('/containers/bulk', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ items: list })
        })
        .then(r => r.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire('Berhasil', res.message, 'success').then(() => location.reload());
            } else {
                Swal.fire('Error', res.message, 'error');
            }
        });
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function () {
        const id = $(this).closest('tr').data('id');

        fetch(`/containers/${id}`)
            .then(r => r.json())
            .then(container => {

                $('#item_id').val(container.id);

                let html = `
                    <label>Biji</label>
                    <input type="number" class="form-control mb-2" id="edit_biji"
                        value="${container.biji}" min="0">

                    <label>Berat</label>
                    <input type="number" class="form-control mb-2" id="edit_berat"
                        value="${container.berat}" min="0" step="0.01">

                    <label>Keterangan</label>
                    <input type="text" class="form-control mb-2" id="edit_keterangan"
                        value="${container.keterangan ?? ''}">

                    <label>Petugas</label>
                    <select class="form-control" id="edit_petugas">
                        <option value="">-- Pilih Petugas --</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}"
                                ${container.employees_id == "{{ $employee->id }}" ? 'selected' : ''}>
                                {{ $employee->nama }} ({{ $employee->nip }})
                            </option>
                        @endforeach
                    </select>
                `;

                $('#secondModalBody').html(html);
                $('#secondModal').modal('show');

                $('#btnSubmitAll').off().on('click', function () {

                    let payload = {
                        arrivals_id: container.arrivals_id, // arrival tidak bisa diubah lewat modal ini
                        biji: parseInt($('#edit_biji').val()),
                        berat: parseFloat($('#edit_berat').val()),
                        keterangan: $('#edit_keterangan').val() || null,
                        employees_id: parseInt($('#edit_petugas').val())
                    };

                    fetch(`/containers/${container.id}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.status === 'success') {
                            Swal.fire('Berhasil', res.message, 'success')
                                .then(() => location.reload());
                        } else {
                            Swal.fire('Error', res.message, 'error');
                        }
                    });
                });
            });
    });

    $(document).on('click', '.btnDelete', function () {
        const id = $(this).closest('tr').data('id');

        Swal.fire({
            title: 'Anda Yakin?',
            text: 'Data tidak dapat dikembalikan',
            icon: 'warning',
            showCancelButton: true
        }).then(res => {
            if (res.isConfirmed) {
                fetch(`/containers/${id}`, {
                    method: 'DELETE',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Error', res.message, 'error');
                    }
                })
                .catch(() => Swal.fire('Error', 'Gagal menghapus data. Pastikan data tidak terintegrasi dengan data lainnya.', 'error'));
            }
        });
    });

@stop

@section('content')
@parent

<div class="modal fade" id="secondModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5>Input Detail Kontainer</h5>
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