@extends('layouts.form')

@php
    $title = 'Kelola Hasil Uji Bahan Baku';
    $singular = 'Hasil Uji Bahan Baku';
    $deleteMultipleUrl = '/rm-alums/delete-multiple';
    // $importUrl = route('rm-alums.import');
    // $templateUrl = route('rm-alums.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Rumah Burung/No. Registrasi</th>
    <th>Kadar Aluminium</th>
@stop

@section('table-body')
    @foreach($results as $index => $result)
        <tr data-id="{{ $result->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $result->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $result->rawMaterial->kode }}</td>
            <td>{{ $result->kadar_aluminium }}</td>
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
        <label>Jumlah Sampel</label>
        <input type="number" min="1" id="jumlah_sampel" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const rms_id = $('#rms_id').val();
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
                <label>Kadar Aluminium</label>
                <input type="number" class="form-control mb-2 kadar-aluminium" data-index="${i}" step="0.1" min="0" max="999.9">
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
                kadar_aluminium: $(`.kadar-aluminium[data-index="${i}"]`).val(),
            });
        }

        fetch('/rm-alums/bulk', {
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

        fetch(`/rm-alums/${id}`)
            .then(r => r.json())
            .then(result => {

                $('#item_id').val(result.id);

                let html = `
                    <label>Kadar Aluminium</label>
                    <input type="number" class="form-control mb-2" id="edit_aluminium"
                        value="${result.kadar_aluminium}" step="0.1" min="0" max="999.9">
                `;

                $('#secondModalBody').html(html);
                $('#secondModal').modal('show');

                $('#btnSubmitAll').off().on('click', function () {

                    let payload = {
                        rms_id: result.rms_id,
                        kadar_aluminium: parseFloat($('#edit_aluminium').val()),
                    };

                    fetch(`/rm-alums/${result.id}`, {
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
                fetch(`/rm-alums/${id}`, {
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