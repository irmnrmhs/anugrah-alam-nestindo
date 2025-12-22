@extends('layouts.form')

@php
    $title = 'Kelola Area';
    $singular = 'Area';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode</th>
    <th>Area</th>
    <th>KH</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($areas as $index => $area)
        <tr data-id="{{ $area->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $area->kode }}</td>
            <td>{{ $area->area }}</td>
            <td>
                @if($area->kh)
                    <span class="badge bg-success">Ya</span>
                @else
                    <span class="badge bg-danger">Tidak</span>
                @endif
            </td>
            <td>{{ $area->keterangan }}</td>
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
        <label>Area</label>
        <input type="text" id="area" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>KH</label>
        <select id="kh" class="form-control" required>
            <option value="">-- Pilih --</option>
            <option value="1">Ya</option>
            <option value="0">Tidak</option>
        </select>
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" id="keterangan" class="form-control">
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/areas/${id}` : '/areas';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        kode: $('#kode').val(),
        area: $('#area').val(),
        kh: $('#kh').val(),
        keterangan: $('#keterangan').val()
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
    .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode dan Area tidak duplikat', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/areas/${id}`)
            .then(r => r.json())
            .then(area => {
                $('#item_id').val(area.id);
                $('#kode').val(area.kode);
                $('#area').val(area.area);
                $('#kh').val(area.kh);
                $('#keterangan').val(area.keterangan);
                $('#modalTitle').text('Edit Area');
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
            confirmButtonText: 'Ya, hapus',
            cancelButtonText: 'Batal'
        }).then(result => {
            if (result.isConfirmed) {
                fetch(`/areas/${id}`, {
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
