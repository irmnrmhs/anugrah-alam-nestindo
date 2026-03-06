@extends('layouts.form')

@php
    $title = 'Kelola Steam';
    $singular = 'Steam';
    $deleteMultipleUrl = '/steams/delete-multiple';
    $hideImportButton = true;
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Grade Produk Jadi</th>
    <th>Kode Batch</th>
    <th>Tipe Sarang</th>
    <th>Tipe Penambahan Air</th>
    <th>Tipe Sumber Panas</th>
    <th>Tanggal Pemanasan</th>
    <th>Petugas Pemanas</th>
    <th>Level Air</th>
    <th>Suhu Awal</th>
@stop

@section('table-body')
    @foreach($steams as $index => $steam)
        <tr data-id="{{ $steam->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $steam->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $steam->product->kode }}</td>
            <td>{{ $steam->batch }}</td>
            <td>{{ $steam->nest->type }}</td>
            <td>{{ $steam->penambahan ? 'Otomatis' : 'Manual' }}</td>
            <td>{{ $steam->sumber_panas ? 'Listrik' : 'Gas' }}</td>
            <td>{{ $steam->tgl_pemanasan }}</td>
            <td>{{ $steam->petugas }}</td>
            <td>{{ $steam->lv_air ? 'Sesuai Standar' : 'Tidak Sesuai' }}</td>
            <td>{{ $steam->suhu_awal }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Grade Produk Jadi</label>
        <select id="products_id" class="form-control" required>
            <option value="">-- Pilih Grade Produk Jadi --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}">{{ $product->kode }}</option>
            @endforeach
        </select>
    </div>
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
            <option value="1">Otomatis</option>
            <option value="0">Manual</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Tipe Sumber Panas</label>
        <select id="sumber_panas" class="form-control" required>
            <option value="1">Listrik</option>
            <option value="0">Gas</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Pemanasan</label>
        <input type="date" id="tgl_pemanasan" class="form-control" required>
    </div>
    {{-- <div class="mb-3">
        <label>Petugas Pemanas</label>
        <select id="officers_id" class="form-control" required>
            <option value="">-- Pilih Petugas Pemanas --</option>
            @foreach($officers as $officer)
                <option value="{{ $officer->id }}">{{ $officer->employee->nama }}</option>
            @endforeach
            <option value="Semua">Semua</option>
        </select>
    </div> --}}
    <div class="mb-3">
        <label>Petugas</label>
        <div>
            <label>
                <input type="checkbox" id="officer_all"> Semua Petugas
            </label>
            <hr>
        </div>
        <div>
            @foreach ( $officers as $officer)
                <label><input type="checkbox" class="officer-item" value={{ $officer->employee->nama }}> {{ $officer->employee->nama }} </label><br>
            @endforeach
        </div>
    </div>
    <div class="mb-3">
        <label>Level Air</label>
        <select id="lv_air" class="form-control" required>
            <option value="1">Sesuai Standar</option>
            <option value="0">Tidak Sesuai Standar</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Suhu Awal</label>
        <input type="number" id="suhu_awal" step="0.01" min="0" max="999.99" class="form-control" required>
    </div>
@stop

@section('form-submit-script')
    function getOfficerValue() {
        let list = [];
        $('.officer-item:checked').each(function() {
            list.push($(this).val());
        });

        return list.length > 0 ? list.join(', ') : '';
    }

    const id = $('#item_id').val();
    const url = id ? `/steams/${id}` : '/steams';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        products_id: $('#products_id').val(),
        nests_id: $('#nests_id').val(),
        petugas: getOfficerValue(),
        penambahan: $('#penambahan').val(),
        sumber_panas: $('#sumber_panas').val(),
        tgl_pemanasan: $('#tgl_pemanasan').val(),
        lv_air: $('#lv_air').val(),
        suhu_awal: $('#suhu_awal').val(),
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan data diisi lengkap', 'error'));
@stop

@section('custom-js')
    function syncOfficerAll() {
        const total = $('.officer-item').length;
        const checked = $('.officer-item:checked').length;

        $('#officer_all').prop('checked', total > 0 && total === checked);
    }

    $(document).on('change', '#officer_all', function() {
        const checked = $(this).is(':checked');
        $('.officer-item').prop('checked', checked);
    });

    $(document).on('change', '.officer-item', function() {
        syncOfficerAll();
        const allChecked = $('.officer-item:checked').length === $('.officer-item').length;
        $('#officer_all').prop('checked', allChecked);
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/steams/${id}`)
            .then(r => r.json())
            .then(steam => {
                $('#item_id').val(steam.id);
                $('#products_id').val(steam.products_id);
                $('#nests_id').val(steam.nests_id);
                $('#petugas').val(steam.petugas);
                $('#penambahan').val(steam.penambahan);
                $('#sumber_panas').val(steam.sumber_panas);
                $('#tgl_pemanasan').val(steam.tgl_pemanasan);
                $('#lv_air').val(steam.lv_air);
                $('#suhu_awal').val(steam.suhu_awal);

                $('.officer-item').prop('checked', false);
                $('#officer_all').prop('checked', false);

                if (steam.petugas) {
                    const officerList = steam.petugas.split(', ');

                    officerList.forEach(function(k) {
                        $('.officer-item[value="'+k.trim()+'"]').prop('checked', true);
                    });
                }

                syncOfficerAll();

                $('#modalTitle').text('Edit Karyawan');
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
                fetch(`/steams/${id}`, {
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
