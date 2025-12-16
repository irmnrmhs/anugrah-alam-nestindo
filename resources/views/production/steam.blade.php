@extends('layouts.form')

@php
    $title = 'Kelola Steam';
    $singular = 'Steam';
    $deleteMultipleUrl = '/steams/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tipe Sarang</th>
    <th>Tipe Penambahan Air</th>
    <th>Tipe Sumber Panas</th>
    <th>Tanggal Pemanasan</th>
    <th>Petugas Pemanas</th>
    <th>Level Air</th>
    <th>Suhu Awal</th>
    <th>Kode Batch</th>
    <th>Grade</th>
    <th>Keping</th>
    <th>Berat</th>
    <th>Suhu Preheating</th>
    <th>Waktu Preheating</th>
    <th>Suhu Total</th>
    <th>Waktu Total</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($steams as $index => $steam)
        <tr data-id="{{ $steam->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $steam->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $steam->nest->type }}</td>
            <td>{{ $steam->penambahan }}</td>
            <td>{{ $steam->sumber_panas }}</td>
            <td>{{ $steam->tgl_pemanasan }}</td>
            <td>{{ $steam->officer->employee->nama }}</td>
            <td>{{ $steam->standar }}</td>
            <td>{{ $steam->suhu_awal }}</td>
            <td>{{ $steam->kode }}</td>
            <td>{{ $steam->fproduct->product->grade->grade }}</td>
            <td>{{ $steam->biji }}</td>
            <td>{{ $steam->berat }}</td>
            <td>{{ $steam->suhu }}</td>
            <td>{{ $steam->waktu }}</td>
            <td>{{ $steam->suhu_total }}</td>
            <td>{{ $steam->waktu_total }}</td>
            <td>{{ $steam->jml_tray }}</td>
            <td>{{ $steam->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Tipe Sarang Walet</label>
        <select id="nests_id" class="form-control" required>
            <option value="">-- Pilih Tipe Sarang Walet --</option>
            @foreach($nests as $nest)
                <option value="{{ $nest->id }}">{{ $nest->type }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tipe Penambahan Air</label>
        <select id="penambahan" class="form-control" required>
            <option value="0">Manual</option>
            <option value="1">Otomatis</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Tipe Sumber Panas</label>
        <select id="sumber_panas" class="form-control" required>
            <option value="0">Gas</option>
            <option value="1">Listrik</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Pemanasan</label>
        <input type="date" id="tgl_pemanasan" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Petugas Pemanas</label>
        <select id="officers_id" class="form-control" required>
            <option value="">-- Pilih Petugas Pemanas --</option>
            @foreach($officers as $officer)
                <option value="{{ $officer->id }}">{{ $officer->employee->nama }}</option>
            @endforeach
            <option value="Semua">Semua</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Level Air</label>
        <select id="standar" class="form-control" required>
            <option value="1">Sesuai Standar</option>
            <option value="0">Tidak Sesuai Standar</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Suhu Awal</label>
        <input type="number" id="suhu_awal" step="0.01" min="0" max="999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Grade</label>
        <select id="fproducts_id" class="form-control" required>
            <option value="">-- Pilih Grade --</option>
            @foreach($fproducts as $fproduct)
                <option value="{{ $fproduct->id }}">{{ $fproduct->product->grade->grade }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Keping</label>
        <input type="number" id="biji" step="1" min="0" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" step="0.001" min="0" max="99999.99" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Jumlah Putaran</label>
        <input type="number" min="1" id="jumlah_putaran" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const nests_id = $('#nests_id').val();
    const penambahan = $('#penambahan').val();
    const sumber_panas = $('#sumber_panas').val();
    const tgl_pemanasan = $('#tgl_pemanasan').val();
    const officers_id = $('#officers_id').val();
    const standar = $('#standar').val();
    const suhu_awal = $('#suhu_awal').val();
    const fproducts_id = $('#fproducts_id').val();
    const biji = $('#biji').val();
    const berat = $('#berat').val();
    const jumlah = parseInt($('#jumlah_putaran').val());

    bootstrap.Modal.getInstance(document.getElementById('crudModal')).hide();

    let html = '';
    for (let i = 1; i <= jumlah; i++) {
        html += `
            <div class="border rounded p-3 mb-3">
                <h6>Putaran ${i}</h6>

                <label>Suhu Preheating</label>
                <input type="number" class="form-control mb-2 suhu" data-index="${i}" step="0.01" min="0" max="999.99">

                <label>Waktu Preheating</label>
                <input type="time" class="form-control mb-2 waktu" data-index="${i}">

                <label>Suhu Total</label>
                <input type="number" class="form-control mb-2 suhu-total" data-index="${i}" step="0.01" min="0" max="999.99">

                <label>Waktu Total</label>
                <input type="time" class="form-control mb-2 waktu-total" data-index="${i}">

                <label>Keterangan</label>
                <input type="text" class="form-control mb-2 keterangan" data-index="${i}">

                <label>Jumlah Tray</label>
                <input type="number" class="form-control mb-2 jumlah-tray" data-index="${i}" step="1" min="0" max="6">
            </div>
        `;
    }

    $('#secondModalBody').html(html);
    $('#secondModal').modal('show');

    $('#btnSubmitAll').off().on('click', function () {
        let list = [];

        for (let i = 1; i <= jumlah; i++) {
            list.push({
                nests_id,
                penambahan,
                sumber_panas,
                tgl_pemanasan,
                officers_id,
                standar,
                suhu_awal,
                fproducts_id,
                biji,
                berat,
                suhu: $(`.suhu[data-index="${i}"]`).val(),
                waktu: $(`.waktu[data-index="${i}"]`).val(),
                suhu_total: $(`.suhu-total[data-index="${i}"]`).val(),
                waktu_total: $(`.waktu-total[data-index="${i}"]`).val(),
                keterangan: $(`.keterangan[data-index="${i}"]`).val(),
                jumlah_tray: $(`.jumlah-tray[data-index="${i}"]`).val(),
            });
        }

        fetch('/steams/bulk', {
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

        fetch(`/steams/${id}`)
            .then(r => r.json())
            .then(result => {

                $('#item_id').val(result.id);

                let html = `
                    <label>Suhu Preheating</label>
                    <input type="number" class="form-control mb-2" id="edit_suhu"
                        value="${result.suhu}" step="0.01" min="0" max="999.99">

                    <label>Waktu Preheating</label>
                    <input type="time" class="form-control mb-2" id="edit_waktu"
                        value="${result.waktu}">

                    <label>Suhu Total</label>
                    <input type="number" class="form-control mb-2" id="edit_suhu_total"
                        value="${result.suhu_total}" step="0.01" min="0" max="999.99">

                    <label>Waktu Total</label>
                    <input type="time" class="form-control mb-2" id="edit_waktu_total"
                        value="${result.waktu_total}">

                    <label>Keterangan</label>
                    <input type="text" class="form-control mb-2" id="edit_keterangan"
                        value="${result.keterangan}">

                    <label>Jumlah Tray</label>
                    <input type="number" class="form-control mb-2" id="edit_jml_tray"
                        value="${result.jml_tray}" min="0" max="6">

                `;

                $('#secondModalBody').html(html);
                $('#secondModal').modal('show');

                $('#btnSubmitAll').off().on('click', function () {

                    let payload = {
                        nests_id: result.nests_id,
                        penambahan: result.penambahan,
                        sumber_panas: result.sumber_panas,
                        tgl_pemanasan: result.tgl_pemanasan,
                        officers_id: result.officers_id,
                        standar: result.standar,
                        suhu_awal: result.suhu_awal,
                        fproducts_id: result.fproducts_id,
                        biji: result.biji,
                        berat: result.berat,
                        suhu: parseFloat($('#edit_suhu').val()),
                        waktu: result.waktu,
                        suhu_total: parseFloat($('#edit_suhu_total').val()),
                        waktu_total: result.waktu_total,
                        keterangan: result.keterangan,
                        jml_tray: result.jml_tray
                    };

                    fetch(`/steams/${result.id}`, {
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
            title: 'Hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true
        }).then(res => {
            if (res.isConfirmed) {
                fetch(`/steams/${id}`, {
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
                <h5>Input Detail Steam</h5>
            </div>

            <div class="modal-body overflow-auto" id="secondModalBody" style="max-height: 70vh;">
                {{-- auto generated --}}
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-primary" id="btnSubmitAll">Simpan Semua</button>
            </div>

        </div>
    </div>
</div>
@endsection