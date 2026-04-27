@extends('layouts.form')

@php
    $title = 'Kelola Hasil Uji Bahan Baku';
    $singular = 'Hasil Uji Bahan Baku';
    $deleteMultipleUrl = '/ccp1/delete-multiple';
    // $importUrl = route('ccp1.import');
    // $templateUrl = route('ccp1.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Rumah Burung/No. Registrasi</th>
    <th>Tanggal Pemeriksaan</th>
    <th>Kadar Nitrit Selama Proses</th>
@stop

@section('table-body')
    @foreach($results as $index => $result)
        <tr data-id="{{ $result->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $result->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $result->rawMaterial->kode }}</td>
            <td>{{ $result->tgl }}</td>
            <td>{{ $result->ccp1 }}</td>
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
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach($rms as $rm)
                <option value="{{ $rm->id }}">{{ $rm->kode }}</option>
            @endforeach
        </select>
    </div>
    
    <div class="mb-3">
        <label>Tanggal Pemeriksaan</label>
        <input id="tgl" type="date" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Jumlah Sampel</label>
        <input type="number" min="1" id="jumlah_sampel" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const rms_id = $('#rms_id').val();
    const tgl = $('#tgl').val();
    const jumlah = parseInt($('#jumlah_sampel').val());

    if (!rms_id || jumlah < 1) {
        Swal.fire('Error', 'Lengkapi Kode & Jumlah Sampel.', 'error');
        return;
    }

    bootstrap.Modal.getInstance(document.getElementById('crudModal')).hide();

    let html = '';
    for (let i = 1; i <= jumlah; i++) {
        html += `
            <div class="border rounded p-3 mb-3">
                <h6>Sampel ${i}</h6>
                <label>Kadar Nitrit Selama Proses (CCP1)</label>
                <input type="number" class="form-control mb-2 ccp1" data-index="${i}" step="0.1" min="0" max="999.9">
            </div>
        `;
    }

    $('#secondModalBody').html(html);
    $('#secondModal').modal('show');

    $('#btnSubmitAll').off().on('click', function () {
        let list = [];
        let isAnyInvalid = false;

        const CCP_MIN = 0;
        const CCP_MAX = 30;

        for (let i = 1; i <= jumlah; i++) {
            const ccp1 = parseFloat($(`.ccp1[data-index="${i}"]`).val()) || 0;

            const isValid = ccp1 > CCP_MIN && ccp1 < CCP_MAX;

            if(!isValid){
                isAnyInvalid = true
            }

            list.push({
                rms_id,
                tgl,
                ccp1,
            });
        }

        function submitData(){
            fetch('/ccp1/bulk', {
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
        }

        if (isAnyInvalid) {
            Swal.fire({
                title: 'Ada Sampel Tidak Lulus!',
                text: 'Beberapa data tidak memenuhi standar. Tetap simpan?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal'
            }).then(result => {
                if (result.isConfirmed) {
                    submitData();
                }
            });
        } else {
            submitData();
        }
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function () {
        const id = $(this).closest('tr').data('id');

        fetch(`/ccp1/${id}`)
            .then(r => r.json())
            .then(result => {

                $('#item_id').val(result.id);

                let html = `
                    <label>Kadar Nitrit Selama Proses</label>
                    <input type="number" class="form-control mb-2" id="edit_ccp1"
                        value="${result.ccp1}" step="0.1" min="0" max="999.9">
                `;

                $('#secondModalBody').html(html);
                $('#secondModal').modal('show');

                $('#btnSubmitAll').off().on('click', function () {

                    let payload = {
                        rms_id: result.rms_id,
                        tgl: result.tgl,
                        ccp1: parseFloat($('#edit_ccp1').val()),
                    };

                    fetch(`/ccp1/${result.id}`, {
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
                fetch(`/ccp1/${id}`, {
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
                <h5>Input Detail Sampel</h5>
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