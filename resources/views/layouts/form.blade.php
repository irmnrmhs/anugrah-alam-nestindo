@extends('adminlte::page')

@section('title', $title ?? 'Kelola Data')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $title ?? 'Kelola Data' }}</h1>
        @if (empty($hideAddButton))
            <button class="btn btn-primary" id="btnAdd">Tambah {{ $singular ?? 'Data' }}</button>
        @endif
        @if (empty($hideImportButton))
            <button class="btn btn-primary" id="btnImport">Import {{ $singular ?? 'Data' }}</button>
        @endif
    </div>
@stop

@section('bulk-actions')
    <button id="btnDeleteSelected" class="btn btn-danger mb-2" style="display:none;">
        Hapus Terpilih
    </button>
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            @yield('bulk-actions')
            
            {{-- Tabel utama --}}
            <table class="table table-bordered table-striped" id="dataTable" data-delete-multiple="{{ $deleteMultipleUrl ?? '' }}">
                <thead>
                    <tr>
                        @yield('table-headers')
                        @if (empty($hideActions))
                            <th>Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @yield('table-body')
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Form --}}
    <div class="modal fade" id="crudModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="crudForm">
                    @csrf
                    <input type="hidden" id="item_id">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Tambah {{ $singular ?? 'Data' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @yield('form-fields')
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    {{-- Modal Import --}}
    <div class="modal fade" id="importModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="importForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Import {{ $singular ?? 'Data' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label>File Excel</label>
                            <input type="file" name="file" id="importFile" class="form-control" required>
                            <small class="text-muted">
                                Format: xls / xlsx |
                                <a href="{{ $templateUrl ?? '#' }}">
                                    Download Template
                                </a>
                            </small>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            Import
                        </button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
    {{-- DataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
    {{-- JS Libraries --}}
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>

    // ==== Multi Delete Universal ====
    function toggleBulkDeleteButton() {
        const selected = $('.row-check:checked').length;
        if (selected > 0) $('#btnDeleteSelected').show();
        else $('#btnDeleteSelected').hide();
    }

    $(document).on('change', '.row-check', toggleBulkDeleteButton);

    $(document).on('change', '#checkAll', function () {
        $('.row-check').prop('checked', $(this).is(':checked'));
        toggleBulkDeleteButton();
    });

    $(document).on('click', '#btnDeleteSelected', function () {
        const ids = $('.row-check:checked').map((i, el) => el.value).get();

        if (ids.length === 0) {
            return Swal.fire('Oops', 'Tidak ada data yang dipilih', 'warning');
        }

        Swal.fire({
            title: 'Hapus data terpilih?',
            text: 'Data yang dihapus tidak bisa dikembalikan.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Ya, hapus'
        }).then(result => {
            if (result.isConfirmed) {

                // URL delete multiple diambil dari attribute
                const url = $('#dataTable').data('delete-multiple');

                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ ids })
                })
                .then(r => r.json())
                .then(res => {
                    if (res.status === 'success') {
                        Swal.fire('Terhapus!', res.message, 'success')
                            .then(() => location.reload());
                    } else {
                        Swal.fire('Gagal', res.message, 'error');
                    }
                }) 
                .catch(() => Swal.fire('Error', 'Gagal menghapus data. Pastikan data tidak terintegrasi dengan data lainnya.', 'error'));
            }
        });
    });


    // ==== Main ====
    $(function() {
        // Init datatable
        $('#dataTable').DataTable({ responsive: true });

        // Modal
        const modal = new bootstrap.Modal('#crudModal');

        $('#btnAdd').click(() => {
            $('#crudForm')[0].reset();
            $('#item_id').val('');
            $('#modalTitle').text('Tambah {{ $singular ?? "Data" }}');
            modal.show();
        });

        // ==== Import ====
        const importModal = new bootstrap.Modal('#importModal');

        $('#btnImport').click(() => {
            $('#importForm')[0].reset();
            importModal.show();
        });

        $('#importForm').submit(function (e) {
            e.preventDefault();

            let formData = new FormData(this);

            fetch("{{ $importUrl ?? '' }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            })
            .then(res => res.json())
            .then(res => {
                if (res.status === 'success') {

                    if (res.errors && res.errors.length > 0) {
                        Swal.fire({
                            title: 'Import Selesai (Dengan Kesalahan)',
                            icon: 'warning',
                            html: `
                                <pre style="
                                    text-align:left;
                                    max-height:300px;
                                    overflow:auto;
                                    white-space:pre-wrap;
                                ">${res.errors.join('\n')}</pre>
                            `
                        }).then(() => location.reload());

                    }
                    else {
                        Swal.fire('Sukses', res.message, 'success')
                            .then(() => location.reload());
                    }

                } else {
                    Swal.fire('Gagal', res.message || 'Import gagal', 'error');
                }
            })
            .catch(() => {
                Swal.fire('Error', 'Terjadi kesalahan saat import', 'error');
            });
        });

        // Submit Form
        $('#crudForm').submit(e => {
            e.preventDefault();
            @yield('form-submit-script')
        });

        // Custom Action from child
        @yield('custom-js')
    });
    </script>
@stop