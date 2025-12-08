@extends('layouts.form')

@php
    $title = 'Kelola Data Kedatangan';
    $singular = 'Kedatangan';
    $deleteMultipleUrl = '/arrivals/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Kode Bahan Baku</th>
    <th>Tanggal Kedatangan</th>
    <th>No. SKP</th>
    <th>Mobil</th>
    <th>Supir</th>
    {{-- <th>Kadar Air</th> --}}
    {{-- <th>Penerima</th> --}}
    <th>Kondisi</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($arrivals as $index => $arrival)
        <tr data-id="{{ $arrival->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $arrival->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $arrival->kode }}</td>
            <td>{{ $arrival->tgl_kedatangan }}</td>
            <td>{{ $arrival->dcertificate->no_skp }}</td>
            <td>{{ $arrival->car->merk . ' - ' . $arrival->car->plat }}</td>
            <td>{{ $arrival->employee->nama }}</td>
            {{-- <td>{{ $arrival->employee->nama }}</td> --}}
            <td>{{ $arrival->kondisi }}</td>
            <td>{{ $arrival->keterangan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Nomor SKP</label>
        <select id="dcertificates_id" class="form-control" required>
            <option value="">-- Pilih SKP --</option>
            @foreach($dcertificates as $dcertificate)
                <option value="{{ $dcertificate->id }}">{{ $dcertificate->no_skp }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Tanggal Kedatangan</label>
        <input type="date" id="tgl_kedatangan" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Mobil</label>
        <select id="cars_id" class="form-control" required>
            <option value="">-- Pilih Mobil --</option>
            @foreach($cars as $car)
                <option value="{{ $car->id }}">{{ $car->merk . ' - ' . $car->plat }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Supir</label>
        <select id="employees_id" class="form-control" required>
            <option value="">-- Pilih Supir --</option>
            @foreach($employees as $employee)
                <option value="{{ $employee->id }}">{{ $employee->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Kondisi</label>
        <div>
            <label>
                <input type="checkbox" id="kondisi_all"> Semua Kondisi Terpenuhi
            </label>
            <hr>
        </div>
        <div>
            <label><input type="checkbox" class="kondisi-item" value="sampah"> Bebas dari sampah </label><br>
            <label><input type="checkbox" class="kondisi-item" value="ceceran oli"> Bebas dari ceceran oli </label><br>
            <label><input type="checkbox" class="kondisi-item" value="benda tajam"> Bebas dari benda tajam </label><br>
            <label><input type="checkbox" class="kondisi-item" value="kondisi seal dalam keadaan utuh"> Seal utuh </label>
        </div>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    function getKondisiValue() {
        let list = [];
        $('.kondisi-item:checked').each(function() {
            list.push($(this).val());
        });

        return list.length > 0 ? 'Bebas dari ' + list.join(', ') : '';
    }

    const id = $('#item_id').val();
    const url = id ? `/arrivals/${id}` : '/arrivals';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        <!-- kode: $('#kode').val(), -->
        dcertificates_id: $('#dcertificates_id').val(),
        cars_id: $('#cars_id').val(),
        employees_id: $('#employees_id').val(),
        tgl_kedatangan: $('#tgl_kedatangan').val(),
        kondisi: getKondisiValue(),
        keterangan: $('#keterangan').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan!', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan SKP belum digunakan.', 'error'));
@stop

@section('custom-js')
    // Jika checklist "Semua Kondisi"
    $(document).on('change', '#kondisi_all', function() {
        const checked = $(this).is(':checked');
        $('.kondisi-item').prop('checked', checked);
    });

    // Jika ada perubahan di checkbox individu,
    // master menjadi on bila SEMUA individu tercentang
    $(document).on('change', '.kondisi-item', function() {
        const allChecked = $('.kondisi-item:checked').length === $('.kondisi-item').length;
        $('#kondisi_all').prop('checked', allChecked);
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/arrivals/${id}`)
            .then(r => r.json())
            .then(arrival => {
                $('#item_id').val(arrival.id);
                <!-- $('#kode').val(arrival.kode); -->
                $('#dcertificates_id').val(arrival.dcertificates_id);
                $('#cars_id').val(arrival.cars_id);
                $('#employees_id').val(arrival.employees_id);
                $('#tgl_kedatangan').val(arrival.tgl_kedatangan);
                $('#keterangan').val(arrival.keterangan);
                
                $('.kondisi-item').prop('checked', false);
                if (arrival.kondisi) {
                    const kondisiList = arrival.kondisi.toLowerCase().replace('bebas dari ', '').split(', ');
                    kondisiList.forEach(function(k) {
                        $('.kondisi-item[value="'+k.trim().toLowerCase()+'"]').prop('checked', true);

                    });
                }

                $('#modalTitle').text('Edit Kedatangan');
                new bootstrap.Modal('#crudModal').show();
            });
    });

    $(document).on('click', '.btnDelete', function() {
        const id = $(this).closest('tr').data('id');
        Swal.fire({
            title: 'Yakin hapus?',
            text: 'Data tidak bisa dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/arrivals/${id}`, {
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
