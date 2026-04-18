@extends('layouts.form')

@php
    $title = 'Kelola Data Air Produksi';
    $singular = 'Air Produksi';
    $deleteMultipleUrl = '/waters/delete-multiple';
    // $importUrl = route('waters.import');
    // $templateUrl = route('waters.template');
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Tanggal</th>
    <th>Nitrit</th>
    <th>pH</th>
    <th>Ozon</th>
    <th>Organoleptis</th>
    <th>Hasil Uji</th>
@stop

@section('table-body')
    @foreach($waters as $index => $water)
        <tr data-id="{{ $water->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $water->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $water->tanggal }}</td>
            <td>{{ $water->nitrit }} ppm</td>
            <td>{{ $water->ph }}</td>
            <td>{{ $water->ozone }} ppm</td>
            <td>
                @if($water->organoleptis == 1)
                    Lulus
                @else
                    Tidak Lulus
                @endif
            </td>
            <td>
                @if($water->hasil == 1)
                    <span class="badge bg-success">Lulus Uji</span>
                @else
                    <span class="badge bg-danger">Tidak Lulus Uji</span>
                @endif
            </td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Tanggal</label>
        <input type="date" id="tanggal" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Nitrit</label>
        <input type="number" id="nitrit" step="0.1" min="0" max="999.9" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>PH</label>
        <input type="number" id="ph" min="0" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Ozon</label>
        <input type="number" id="ozone" step="0.01" min="0" max="999.99" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Organoleptis</label>
        <select id="organoleptis" class="form-control" required>
            <option value=1>Lulus</option>
            <option value=0>Tidak Lulus</option>
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/waters/${id}` : '/waters';
    const method = id ? 'PUT' : 'POST';

    const nitrit = parseFloat($('#nitrit').val());
    const ph = parseFloat($('#ph').val());
    const ozone = parseFloat($('#ozone').val());
    const organoleptis = parseInt($('#organoleptis').val());

    const data = {
        _token: '{{ csrf_token() }}',
            tanggal: $('#tanggal').val(),
            nitrit: nitrit,
            ph: ph,
            ozone: ozone,
            organoleptis: organoleptis,
        };

    const isValid =
        nitrit >= 0 && nitrit < 3 &&
        ph >= 6.5 && ph < 8.5 &&
        ozone >= 0 && ozone < 0.3 &&
        organoleptis === 1;

    function submitData() {
        fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(res => res.json())
        .then(res => {
            if (res.status === 'success') {
                Swal.fire('Sukses', res.message, 'success')
                    .then(() => location.reload());
            } else {
                Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
            }
        })
        .catch(() => Swal.fire('Error', 'Gagal menyimpan data.', 'error'));
    }

    if (!isValid) {
        Swal.fire({
            title: 'Data Tidak Lulus Uji!',
            text: 'Data tidak memenuhi standar. Tetap ingin menyimpan?',
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
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/waters/${id}`)
            .then(r => r.json())
            .then(water => {
                $('#item_id').val(water.id);
                $('#tanggal').val(water.tanggal);
                $('#nitrit').val(water.nitrit);
                $('#ph').val(water.ph);
                $('#ozone').val(water.ozone);
                $('#organoleptis').val(water.organoleptis);
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
                fetch(`/waters/${id}`, {
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
                .catch(() => Swal.fire('Error', 'Gagal menghapus data. Pastikan data diisi lengkap.', 'error'));
            }
        });
    });
@stop
