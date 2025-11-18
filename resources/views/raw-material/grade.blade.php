@extends('layouts.form')

@php
    $title = 'Kelola Data Grade';
    $singular = 'Grade';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kategori</th>
    <th>Grade</th>
    <th>Jenis Bentuk</th>
    <th>Jenis Bulu</th>
    <th>Jenis Warna</th>
    <th>Status</th>
@stop

@section('table-body')
    @foreach($grades as $index => $grade)
        <tr data-id="{{ $grade->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $grade->category->kategori }}</td>
            <td>{{ $grade->grade }}</td>
            <td>{{ $grade->shape->jenis_bentuk }}</td>
            <td>{{ $grade->feather->jenis_bulu }}</td>
            <td>{{ $grade->color->jenis_warna }}</td>
            <td>
                @if($grade->status)
                    <span class="badge bg-success">Aktif</span>
                @else
                    <span class="badge bg-danger">Non Aktif</span>
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
        <label>Kategori</label>
        <select id="categories_id" class="form-control" required>
            <option value="">-- Pilih Kategori --</option>
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->kategori }}</option>
            @endforeach
        </select>
    </div>
    {{-- <div class="mb-3">
        <label>Grade</label>
        <input type="text" id="grade" class="form-control" required>
    </div> --}}
    <div class="mb-3">
        <label>Jenis Bentuk</label>
        <select id="shapes_id" class="form-control" required>
            <option value="">-- Pilih Jenis Bentuk --</option>
            @foreach($shapes as $shape)
                <option value="{{ $shape->id }}">{{ $shape->jenis_bentuk }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Jenis Bulu</label>
        <select id="feathers_id" class="form-control" required>
            <option value="">-- Pilih Jenis Bulu --</option>
            @foreach($feathers as $feather)
                <option value="{{ $feather->id }}">{{ $feather->jenis_bulu }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Jenis Warna</label>
        <select id="colors_id" class="form-control" required>
            <option value="">-- Pilih Jenis Warna --</option>
            @foreach($colors as $color)
                <option value="{{ $color->id }}">{{ $color->jenis_warna }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select id="status" class="form-control" required>
            <option value="1">Aktif</option>
            <option value="0">Non Aktif</option>
        </select>
    </div>
@stop

@section('form-submit-script')
    const id = $('#item_id').val();
    const url = id ? `/grades/${id}` : '/grades';
    const method = id ? 'PUT' : 'POST';

    const data = {
        _token: '{{ csrf_token() }}',
        categories_id: $('#categories_id').val(),
        {{-- grade: $('#grade').val(), --}}
        shapes_id: $('#shapes_id').val(),
        feathers_id: $('#feathers_id').val(),
        colors_id: $('#colors_id').val(),
        status: $('#status').val()
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
    .catch(() => Swal.fire('Error', 'Gagal mengirim data', 'error'));
@stop

@section('custom-js')
    $(document).on('click', '.btnEdit', function() {
        const id = $(this).closest('tr').data('id');
        fetch(`/grades/${id}`)
            .then(r => r.json())
            .then(grade => {
                $('#item_id').val(grade.id);
                $('#categories_id').val(grade.categories_id);
                $('#grade').val(grade.grade);
                $('#shapes_id').val(grade.shapes_id);
                $('#feathers_id').val(grade.feathers_id);
                $('#colors_id').val(grade.colors_id);
                $('#status').val(grade.status);
                $('#modalTitle').text('Edit Grade');
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
                fetch(`/grades/${id}`, {
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
