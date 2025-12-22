@extends('layouts.form')

@php
    $title = 'Kelola Kategori Grade';
    $hideAddButton = true;
@endphp

@section('content')
<div class="row">
    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Bentuk</strong>
                <button class="btn btn-sm btn-primary" id="btnAddShape">Tambah Bentuk</button>
                <button class="btn btn-outline-secondary btn-sm p-1" type="button" data-bs-toggle="collapse" data-bs-target="#collapseShape" aria-expanded="true" aria-controls="collapseShape">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-striped" id="tableShape">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Jenis Bentuk</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shapes as $i => $s)
                            <tr data-id="{{ $s->id }}">
                                <td>{{ $i+1 }}</td>
                                <td>{{ $s->kode }}</td>
                                <td>{{ $s->jenis_bentuk }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEditShape">Edit</button>
                                    <button class="btn btn-danger btn-sm btnDeleteShape">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Bulu</strong>
                <button class="btn btn-primary btn-sm" id="btnAddFeather">Tambah Bulu</button>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-striped" id="tableFeather">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Jenis Bulu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($feathers as $i => $f)
                            <tr data-id="{{ $f->id }}">
                                <td>{{ $i+1 }}</td>
                                <td>{{ $f->kode }}</td>
                                <td>{{ $f->jenis_bulu }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEditFeather">Edit</button>
                                    <button class="btn btn-danger btn-sm btnDeleteFeather">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <strong>Warna</strong>
                <button class="btn btn-primary btn-sm" id="btnAddColor">Tambah Warna</button>
            </div>
            <div class="card-body p-2">
                <table class="table table-bordered table-striped" id="tableColor">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode</th>
                            <th>Jenis Warna</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($colors as $i => $c)
                            <tr data-id="{{ $c->id }}">
                                <td>{{ $i+1 }}</td>
                                <td>{{ $c->kode }}</td>
                                <td>{{ $c->jenis_warna }}</td>
                                <td>
                                    <button class="btn btn-warning btn-sm btnEditColor">Edit</button>
                                    <button class="btn btn-danger btn-sm btnDeleteColor">Hapus</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="modalType" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="formType">
                @csrf
                <input type="hidden" id="item_id">
                <input type="hidden" id="type_category">

                <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Tambah Data</h5>
                    <button class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label>Kode</label>
                        <input type="text" id="kode" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label id="labelJenis">Jenis</label>
                        <input type="text" id="jenis" class="form-control" required>
                    </div>
                </div>

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
const modal = new bootstrap.Modal('#modalType');

function openModal(type, title) {
    $('#formType')[0].reset();
    $('#item_id').val('');
    $('#type_category').val(type);
    $('#modalTitle').text(title);

    if (type === 'shape') $('#labelJenis').text('Jenis Bentuk');
    if (type === 'feather') $('#labelJenis').text('Jenis Bulu');
    if (type === 'color') $('#labelJenis').text('Jenis Warna');

    modal.show();
}

$('#btnAddShape').click(() => openModal('shape', 'Tambah Shape'));
$('#btnAddFeather').click(() => openModal('feather', 'Tambah Feather'));
$('#btnAddColor').click(() => openModal('color', 'Tambah Color'));

$(document).on('click', '.btnEditShape', function() {
    const id = $(this).closest('tr').data('id');
    fetch(`/shapes/${id}`)
        .then(res => res.json())
        .then(d => {
            $('#item_id').val(d.id);
            $('#type_category').val('shape');
            $('#kode').val(d.kode);
            $('#jenis').val(d.jenis_bentuk);
            $('#labelJenis').text('Jenis Bentuk');
            $('#modalTitle').text('Edit Shape');
            modal.show();
        });
});

$(document).on('click', '.btnEditFeather', function() {
    const id = $(this).closest('tr').data('id');
    fetch(`/feathers/${id}`)
        .then(res => res.json())
        .then(d => {
            $('#item_id').val(d.id);
            $('#type_category').val('feather');
            $('#kode').val(d.kode);
            $('#jenis').val(d.jenis_bulu);
            $('#labelJenis').text('Jenis Bulu');
            $('#modalTitle').text('Edit Feather');
            modal.show();
        });
});

$(document).on('click', '.btnEditColor', function() {
    const id = $(this).closest('tr').data('id');
    fetch(`/colors/${id}`)
        .then(res => res.json())
        .then(d => {
            $('#item_id').val(d.id);
            $('#type_category').val('color');
            $('#kode').val(d.kode);
            $('#jenis').val(d.jenis_warna);
            $('#labelJenis').text('Jenis Warna');
            $('#modalTitle').text('Edit Color');
            modal.show();
        });
});

function deleteItem(url) {
    Swal.fire({
        title: 'Yakin hapus?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, hapus'
    }).then(r => {
        if (!r.isConfirmed) return;

        fetch(url, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(res => res.json())
        .then(out => {
            Swal.fire('Sukses', out.message, 'success')
                .then(() => location.reload());
        })
        .catch(() => Swal.fire('Error', 'Gagal menambahkan data. Pastikan kode dan Area tidak duplikat', 'error'));
    });
}

$(document).on('click', '.btnDeleteShape', function() {
    deleteItem(`/shapes/${$(this).closest('tr').data('id')}`);
});
$(document).on('click', '.btnDeleteFeather', function() {
    deleteItem(`/feathers/${$(this).closest('tr').data('id')}`);
});
$(document).on('click', '.btnDeleteColor', function() {
    deleteItem(`/colors/${$(this).closest('tr').data('id')}`);
});


$('#formType').submit(e => {
    e.preventDefault();

    const id = $('#item_id').val();
    const type = $('#type_category').val();

    let url = '';
    if (type === 'shape') url = id ? `/shapes/${id}` : `/shapes`;
    if (type === 'feather') url = id ? `/feathers/${id}` : `/feathers`;
    if (type === 'color') url = id ? `/colors/${id}` : `/colors`;

    const method = id ? 'PUT' : 'POST';

    const payload = {
        kode: $('#kode').val(),
        jenis_bentuk: type === 'shape' ? $('#jenis').val() : undefined,
        jenis_bulu: type === 'feather' ? $('#jenis').val() : undefined,
        jenis_warna: type === 'color' ? $('#jenis').val() : undefined,
    };

    fetch(url, {
        method,
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(out => {
        Swal.fire('Sukses', out.message, 'success')
            .then(() => location.reload());
    });
});
</script>
@endsection
