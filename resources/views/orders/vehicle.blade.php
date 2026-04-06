@extends('layouts.form')

@php
    $title = 'Kelola Data Ceklis Kendaraan';
    $singular = 'Ceklis Kendaraan';
    $deleteMultipleUrl = '/vehicles/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Invoice</th>
    <th>Mobil</th>
    <th>Petugas</th>
    <th>Berat</th>
    <th>Koli</th>
    <th>Kondisi Box</th>
    <th>Kondisi Kemasan</th>
@stop

@section('table-body')
    @foreach($vehicles as $index => $vehicle)
        <tr data-id="{{ $vehicle->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $vehicle->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $vehicle->export->inv }}</td>
            <td>{{ $vehicle->car->merk . '/' . $vehicle->car->plat }}</td>
            <td>{{ $vehicle->employee->nama }}</td>
            <td>{{ $vehicle->berat }}</td>
            <td>-</td>
            <td>{{ $vehicle->kondisi_box }}</td>
            <td>{{ $vehicle->kemasan }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Invoice</label>
        <select id="exports_id" class="form-control" required>
            <option value="">-- Pilih Invoice --</option>
            @foreach($exports as $export)
                <option value="{{ $export->id }}">{{ $export->inv }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Mobil</label>
        <select id="cars_id" class="form-control" required>
            <option value="">-- Pilih Mobil --</option>
            @foreach($cars as $car)
                <option value="{{ $car->id }}">{{ $car->merk . ' / ' . $car->plat }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Berat</label>
        <input type="number" id="berat" min="0" step="0.01" class="form-control" required>
    </div>
    <div class="mb-3">
        <hr>
        <label>Kondisi Bak Kendaraan</label>
        <div>
            <label>
                <input type="checkbox" id="kondisi_all"> Semua Kondisi Terpenuhi
            </label>
        </div>
        <div>
            <label><input type="checkbox" class="kondisi-item" value="1"> Bebas Bau </label><br>
            <label><input type="checkbox" class="kondisi-item" value="2"> Alas tidak basah </label><br>
            <label><input type="checkbox" class="kondisi-item" value="3"> Bebas serpihan kayu </label><br>
            <label><input type="checkbox" class="kondisi-item" value="4"> Bebas Kotoran </label><br>
            <label><input type="checkbox" class="kondisi-item" value="5"> Dilengkapi desinfeksi </label><br>
            <label><input type="checkbox" class="kondisi-item" value="6"> Angkut Produk Halal </label>
        </div>
    </div>
    <div class="mb-3">
        <hr>
        <label>Kondisi Kemasan</label>
        <div>
            <label><input type="checkbox" id="kemasan_all"> Semua Kondisi Terpenuhi</label>
        </div>
        <div>
            <label><input type="checkbox" class="kemasan-item" value="1"> Kondisi kemasan baik </label><br>
            <label><input type="checkbox" class="kemasan-item" value="2"> Dilengkapi spray kemasan luar dengan Oxonia Active 0.1% / Alkohol 75% </label><br>
        </div>
    </div>
    <div class="mb-3">
        <label>Pemeriksa</label>
        <select id="emp_id" class="form-control" required>
            <option value="">-- Pilih Pemeriksa --</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}">{{ $emp->nama }} ({{ $emp->nip }})</option>
            @endforeach
        </select>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>Kode Bahan Baku</label>
        <select name="arrivals_id" id="export_vhc" class="form-control" required>
            <option value="">-- Pilih Kode Bahan Baku --</option>
            @foreach ($exports as $export)
                <option value="{{ $export->id }}">
                    {{ $export->inv }}
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
    function getKondisiValue() {
        let list = [];
        $('.kondisi-item:checked').each(function() {
            list.push($(this).val());
        });

        return list.length > 0 ? 'Bebas dari ' + list.join(', ') : '';
    }

    function getKemasanValue() {
        let list = [];
        $('.kemasan-item:checked').each(function() {
            list.push($(this).val());
        });

        return list.length > 0 ? 'Kondisi kemasan ' + list.join(', ') : '';
    }

    const id = $('#item_id').val();
    const url = id ? `/vehicles/${id}` : '/vehicles';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        exports_id: $('#exports_id').val(),
        cars_id: $('#cars_id').val(),
        emp_id: $('#emp_id').val(),
        berat: $('#berat').val(),
        kondisi_box: getKondisiValue(),
        kemasan: getKemasanValue(),
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kondisi diisi dan kode tidak duplikat.', 'error'));
@stop

@section('custom-js')
    function syncKondisiAll() {
        const total = $('.kondisi-item').length;
        const checked = $('.kondisi-item:checked').length;

        $('#kondisi_all').prop('checked', total > 0 && total === checked);
    }

    function syncKemasanAll() {
        const total = $('.kemasan-item').length;
        const checked = $('.kemasan-item:checked').length;

        $('#kemasan_all').prop('checked', total > 0 && total === checked);
    }

    $(document).on('change', '#kondisi_all', function() {
        const checked = $(this).is(':checked');
        $('.kondisi-item').prop('checked', checked);
    });

    $(document).on('change', '#kemasan_all', function() {
        const checked = $(this).is(':checked');
        $('.kemasan-item').prop('checked', checked);
    });

    $(document).on('change', '.kondisi-item', function() {
        syncKondisiAll();
        const allChecked = $('.kondisi-item:checked').length === $('.kondisi-item').length;
        $('#kondisi_all').prop('checked', allChecked);
    });

    $(document).on('change', '.kemasan-item', function() {
        syncKemasanAll();
        const allChecked = $('.kemasan-item:checked').length === $('.kemasan-item').length;
        $('#kemasan_all').prop('checked', allChecked);
    });

    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/vehicles/${id}`)
            .then(r => r.json())
            .then(data => {
                $('#item_id').val(data.id);
                $('#exports_id').val(data.exports_id);
                $('#cars_id').val(data.cars_id);
                $('#emp_id').val(data.emp_id);
                $('#berat').val(data.berat);
                
                $('.kondisi-item').prop('checked', false);
                $('#kondisi_all').prop('checked', false);

                $('.kemasan-item').prop('checked', false);
                $('#kemasan_all').prop('checked', false);

                if (data.kondisi_box) {
                    const kondisiList = data.kondisi_box
                        .toLowerCase()
                        .replace('bebas dari ', '')
                        .split(', ');

                    kondisiList.forEach(function(k) {
                        $('.kondisi-item[value="'+k.trim()+'"]').prop('checked', true);
                    });
                }

                if (data.kemasan) {
                    const kemasanList = data.kemasan
                        .toLowerCase()
                        .replace('kondisi kemasan ', '')
                        .split(', ');

                    kemasanList.forEach(function(k) {
                        $('.kemasan-item[value="'+k.trim()+'"]').prop('checked', true);
                    });
                }

                syncKondisiAll();
                syncKemasanAll();

                $('#modalTitle').text('Edit Ceklis Kendaraan');
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
                fetch(`/vehicles/${id}`, {
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

    {{-- $('#exportForm').on('submit', function (e) {
        const id   = $('#export_vhc').val();
        const type = $('select[name="type"]').val();

        if (!id) {
            e.preventDefault();
            Swal.fire('Oops', 'Pilih Kode terlebih dahulu', 'warning');
            return;
        }

        this.action = "{{ route('vehicles.export', ':id') }}".replace(':id', id);
        this.method = 'GET';

        this.target = (type === 'pdf') ? '_blank' : '_self';
    }); --}}
@stop
