@extends('layouts.form')

@php
    $title = 'Kelola Area';
    $singular = 'Area';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Area</th>
    <th>Keterangan</th>
@stop

@section('table-body')
    @foreach($areas as $index => $area)
        <tr data-id="{{ $area->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $area->area }}</td>
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
        <label>Area</label>
        <input type="text" id="area" class="form-control" required>
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
        area: $('#area').val(),
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
    });
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/areas/${id}`)
            .then(r => r.json())
            .then(area => {
                $('#item_id').val(area.id);
                $('#area').val(area.area);
                $('#keterangan').val(keterangan.area);
                $('#modalTitle').text('Edit Area');
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
                });
            }
        });
    });
@stop
