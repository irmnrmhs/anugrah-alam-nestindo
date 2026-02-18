@extends('layouts.form')

@php
    $title = 'Kelola Data Pengiriman';
    $singular = 'Pengiriman';
    $deleteMultipleUrl = '/dcertificates/delete-multiple';
@endphp

@section('table-headers')
    <th><input type="checkbox" id="checkAll"></th>
    <th>No</th>
    <th>Rumah Burung</th>
    <th>Alamat RBW</th>
    <th>Tanggal SKP</th>
    <th>Nomor SKP</th>
@stop

@section('table-body')
    @foreach($dcertificates as $index => $dcertificate)
        <tr data-id="{{ $dcertificate->id }}">
            <td><input type="checkbox" class="row-check" value="{{ $dcertificate->id }}"></td>
            <td>{{ $index + 1 }}</td>
            <td>{{ $dcertificate->wbhouse->nama }}</td>
            <td>{{ $dcertificate->wbhouse->alamat }}</td>
            <td>{{ $dcertificate->tgl_skp }}</td>
            <td>{{ $dcertificate->no_skp }}</td>
            <td>
                <button class="btn btn-sm btn-warning btnEdit">Edit</button>
                <button class="btn btn-sm btn-danger btnDelete">Hapus</button>
            </td>
        </tr>
    @endforeach
@stop

@section('form-fields')
    <input type="hidden" id="companies_id" name="companies_id" value="1">
    <div class="mb-3">
        <label>Rumah Burung</label>
        <select id="wbhouses_id" class="form-control" required>
            <option value="">-- Pilih Rumah Burung --</option>
            @foreach($wbhouses as $wbhouse)
                <option value="{{ $wbhouse->id }}">{{ $wbhouse->nama }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nomor SKP</label>
        <input type="text" id="no_skp" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Tanggal SKP</label>
        <input type="date" id="tgl_skp" class="form-control" required>
    </div>
@stop

@section('export')
    <div class="mb-3">
        <label>No SKP</label>
        <select name="dcertificate_id" id="export_dcertificate_id" class="form-control" required>
            <option value="">-- Pilih No SKP --</option>
            @foreach ($dcertificates as $dc)
                <option value="{{ $dc->id }}">
                    {{ $dc->no_skp }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-3">
        <label>Format</label>
        <select name="type" class="form-control" required>
            <option value="pdf">-- Pilih Format --</option>
            <option value="pdf">PDF</option>
            <option value="excel">Excel</option>
        </select>
    </div>
@endsection

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/dcertificates/${id}` : '/dcertificates';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        companies_id: $('#companies_id').val(),
        wbhouses_id: $('#wbhouses_id').val(),
        no_skp: $('#no_skp').val(),
        tgl_skp: $('#tgl_skp').val()
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan SKP tidak duplikat.', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function () {
        const id = $(this).closest('tr').data('id');

        fetch(`/dcertificates/${id}`)
            .then(r => r.json())
            .then(dcertificate => {
                $('#item_id').val(dcertificate.id);
                $('#companies_id').val(dcertificate.companies_id);
                $('#wbhouses_id').val(dcertificate.wbhouses_id);
                $('#tgl_skp').val(dcertificate.tgl_skp);
                $('#no_skp').val(dcertificate.no_skp);
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
                fetch(`/dcertificates/${id}`, {
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
        const id   = $('#export_dcertificate_id').val();
        const type = $('select[name="type"]').val();

        if (!id) {
            e.preventDefault();
            Swal.fire('Oops', 'Pilih No SKP terlebih dahulu', 'warning');
            return;
        }

        this.action = "{{ route('dcertificates.export', ':id') }}".replace(':id', id);
        this.method = 'GET';

        this.target = (type === 'pdf') ? '_blank' : '_self';
    });

@stop
