@extends('layouts.form')

@php
    $title = 'Kelola Customer';
    $singular = 'Customer';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Nama</th>
    <th>Alamat</th>
    <th>No. Telp</th>
    <th>Fax</th>
    <th>Negara</th>
@stop

@section('table-body')
    @foreach($customers as $index => $customer)
        <tr data-id="{{ $customer->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $customer->kode }}</td>
            <td>{{ $customer->nama }}</td>
            <td>{{ $customer->alamat }}</td>
            <td>{{ $customer->no_telp }}</td>
            <td>{{ $customer->fax }}</td>
            <td>{{ $customer->negara }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <div class="mb-3">
        <label>Kode</label>
        <input type="text" id="kode" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Nama</label>
        <input type="text" id="nama" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Alamat</label>
        <input type="text" id="alamat" class="form-control">
    </div>
    <div class="mb-3">
        <label>No. Telp</label>
        <input type="text" id="no_telp" class="form-control">
    </div>
    <div class="mb-3">
        <label>Fax</label>
        <input type="text" id="fax" class="form-control">
    </div>
    <div class="mb-3">
        <label>Negara</label>
        <select id="negara" class="form-control" required>
            <option value="">{{$company->negara ?? 'Pilih Negara '}}</option>
                @foreach($countries as $country)
                    <option value="{{ $country ?? '' }}">{{ $country ?? '' }}</option>
                @endforeach
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/customers/${id}` : '/customers';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        nama: $('#nama').val(),
        alamat: $('#alamat').val(),
        no_telp: $('#no_telp').val(),
        fax: $('#fax').val(),
        negara: $('#negara').val()
    };

    fetch(url, {
        method: method,
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data)
    })
    .then(r => r.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success').then(() => location.reload());
        } else {
            Swal.fire('Gagal', res.message || 'Terjadi kesalahan', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal mengirim data. Pastikan kode dan nama tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/customers/${id}`)
            .then(r => r.json())
            .then(customer => {
                $('#item_id').val(customer.id);
                $('#kode').val(customer.kode);
                $('#nama').val(customer.nama);
                $('#alamat').val(customer.alamat);
                $('#no_telp').val(customer.no_telp);
                $('#fax').val(customer.fax);
                $('#negara').val(customer.negara);
                $('#modalTitle').text('Edit Customer');
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
                fetch(`/customers/${id}`, {
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
                });
            }
        });
    });
@stop
