@extends('layouts.form')

@php
    $title = 'Kelola Area & Rumah Burung';
    $hideAddButton = true;
@endphp

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Area</strong>

                <button class="btn btn-sm btn-primary" id="btnAddArea">
                    Tambah Area
                </button>

                <button class="btn btn-outline-secondary btn-sm p-1 toggle-icon"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseArea"
                    aria-expanded="true">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="collapse show" id="collapseArea">
            <div class="card-body p-2">
                <table class="table table-bordered table-striped" id="tableArea">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Area</th>
                            <th>KH</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($areas as $i => $area)
                            <tr data-id="{{ $area->id }}">
                                <td>{{ $i+1 }}</td>
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
                                    <button class="btn btn-warning btn-sm btnEditArea">Edit</button>
                                    <button class="btn btn-danger btn-sm btnDeleteArea">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-12">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Rumah Burung</strong>

                <button class="btn btn-sm btn-primary" id="btnAddWBHouse">
                    Tambah Rumah Burung
                </button>

                <button class="btn btn-outline-secondary btn-sm p-1 toggle-icon"
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseWBHouse"
                    aria-expanded="true">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>

        <div class="collapse show" id="collapseWBHouse">
            <div class="card-body p-2">
                <table class="table table-bordered table-striped" id="tableWBHouse">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nomor Registrasi</th>
                            <th>Nama Rumah Burung</th>
                            <th>Alamat</th>
                            <th>Area</th>
                            <th>Kapasitas</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($wbhouses as $i => $wb)
                            <tr data-id="{{ $wb->id }}">
                                <td>{{ $i+1 }}</td>
                                <td>{{ $wb->kode }}</td>
                                <td>{{ $wb->nama }}</td>
                                <td>{{ $wb->alamat }}</td>
                                <td>{{ $wb->area->area }}</td>
                                <td>{{ $wb->kapasitas }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEditWBHouse">Edit</button>
                                    <button class="btn btn-danger btn-sm btnDeleteWBHouse">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="crudModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formCRUD">
                @csrf
                <input type="hidden" id="item_id">
                <input type="hidden" id="type_category">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Data</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="modalBody"></div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary" type="submit">Simpan</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection

@section('js')
@parent
<script>
const modal = new bootstrap.Modal('#crudModal');

function openModal(type, title, data = null) {
    $('#btnAddArea').on('click', function () {
        openModal('area', 'Tambah Area');
    });

    $('#btnAddWBHouse').on('click', function () {
        openModal('wbhouse', 'Tambah Rumah Burung');
    });
    
    $('#formCRUD')[0].reset();
    $('#item_id').val(data?.id ?? '');
    $('#type_category').val(type);
    $('#modalTitle').text(title);

    let html = '';

    if (type === 'area') {
        html = `
        <div class="mb-3">
            <label>Kode</label>
            <input type="text" id="kode" class="form-control" required value="${data?.kode ?? ''}">
        </div>
        <div class="mb-3">
            <label>Area</label>
            <input type="text" id="area" class="form-control" required value="${data?.area ?? ''}">
        </div>
        <div class="mb-3">
            <label>KH</label>
            <select id="kh" class="form-control">
                <option value="1" ${data?.kh ? 'selected' : ''}>Ya</option>
                <option value="0" ${!data?.kh ? 'selected' : ''}>Tidak</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Keterangan</label>
            <input type="text" id="keterangan" class="form-control" value="${data?.keterangan ?? ''}">
        </div>`;
    }

    if (type === 'wbhouse') {
        html = `
        <div class="mb-3">
            <label>Nomor Registrasi</label>
            <input type="text" id="kode" class="form-control" required value="${data?.kode ?? ''}">
        </div>
        <div class="mb-3">
            <label>Nama Rumah Burung</label>
            <input type="text" id="nama" class="form-control" required value="${data?.nama ?? ''}">
        </div>
        <div class="mb-3">
            <label>Alamat</label>
            <input type="text" id="alamat" class="form-control" value="${data?.alamat ?? ''}">
        </div>
        <div class="mb-3">
            <label>Area</label>
            <select id="areas_id" class="form-control" required>
                <option value="">-- Pilih Area --</option>
                @foreach($areas as $a)
                    <option value="{{ $a->id }}" ${data?.areas_id == {{ $a->id }} ? 'selected' : ''}>{{ $a->area }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Kapasitas</label>
            <input type="number" step="0.01" id="kapasitas" class="form-control" value="${data?.kapasitas ?? ''}">
        </div>`;
    }

    $('#modalBody').html(html);
    modal.show();
}

$(document).on('click', '.btnEditArea', function () {
    const id = $(this).closest('tr').data('id');
    fetch(`/areas/${id}`)
        .then(r => r.json())
        .then(data => openModal('area', 'Edit Area', data));
});

$(document).on('click', '.btnEditWBHouse', function () {
    const id = $(this).closest('tr').data('id');
    fetch(`/wbhouses/${id}`)
        .then(r => r.json())
        .then(data => openModal('wbhouse', 'Edit Rumah Burung', data));
});

function deleteItem(url) {
    Swal.fire({
        title: 'Hapus data ini?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then(res => {
        if (!res.isConfirmed) return;
        fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(out => Swal.fire('Sukses', out.message, 'success')
            .then(() => location.reload()));
    });
}

$(document).on('click', '.btnDeleteArea', function () {
    deleteItem(`/areas/${$(this).closest('tr').data('id')}`);
});

$(document).on('click', '.btnDeleteWBHouse', function () {
    deleteItem(`/wbhouses/${$(this).closest('tr').data('id')}`);
});

$('#formCRUD').submit(e => {
    e.preventDefault();

    const type = $('#type_category').val();
    const id   = $('#item_id').val();

    let url = type === 'area'
        ? (id ? `/areas/${id}` : '/areas')
        : (id ? `/wbhouses/${id}` : '/wbhouses');

    const method = id ? 'PUT' : 'POST';

    let payload = {};

    if (type === 'area') {
        payload = {
            kode: $('#kode').val(),
            area: $('#area').val(),
            kh: $('#kh').val(),
            keterangan: $('#keterangan').val(),
        };
    } else {
        payload = {
            kode: $('#kode').val(),
            nama: $('#nama').val(),
            alamat: $('#alamat').val(),
            areas_id: $('#areas_id').val(),
            kapasitas: $('#kapasitas').val(),
        };
    }

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(payload)
    })
    .then(r => r.json())
    .then(out => Swal.fire('Sukses', out.message, 'success')
        .then(() => location.reload()));
});

$('.collapse').each(function () {
    const collapseId = '#' + $(this).attr('id');

    $(collapseId).on('shown.bs.collapse', function () {
        $(`[data-bs-target="${collapseId}"] i`)
            .removeClass('fa-plus')
            .addClass('fa-minus');
    });

    $(collapseId).on('hidden.bs.collapse', function () {
        $(`[data-bs-target="${collapseId}"] i`)
            .removeClass('fa-minus')
            .addClass('fa-plus');
    });

    if (!$(collapseId).hasClass('show')) {
        $(`[data-bs-target="${collapseId}"] i`)
            .removeClass('fa-minus')
            .addClass('fa-plus');
    }
});
</script>
@endsection