@extends('adminlte::page')

@section('title', $title ?? 'Kelola Data')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>{{ $title ?? 'Kelola Data' }}</h1>
        <button class="btn btn-primary" id="btnAdd">Tambah {{ $singular ?? 'Data' }}</button>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- Tabel utama --}}
            <table class="table table-bordered table-striped" id="dataTable">
                <thead>
                    <tr>
                        @yield('table-headers')
                        <th>Aksi</th>
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
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
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
    $(function() {
        // === Init Table ===
        $('#dataTable').DataTable({ responsive: true });

        // === Modal ===
        const modal = new bootstrap.Modal('#crudModal');

        $('#btnAdd').click(() => {
            $('#crudForm')[0].reset();
            $('#item_id').val('');
            $('#modalTitle').text('Tambah {{ $singular ?? "Data" }}');
            modal.show();
        });

        // === Submit Form ===
        $('#crudForm').submit(e => {
            e.preventDefault();
            @yield('form-submit-script')
        });

        // === Custom Action per halaman (edit/delete) ===
        @yield('custom-js')
    });
    </script>
@stop
