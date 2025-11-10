@extends('adminlte::page')

@section('title', 'Profil Perusahaan')

@section('content_header')
    <h1>Profil Perusahaan</h1>
@stop

@section('content')
<div class="card">
    <div class="card-body">
        <form id="companyForm">
            @csrf
            <div class="mb-3">
                <label for="ikh">IKH</label>
                <input type="text" id="ikh" name="ikh" class="form-control" 
                       value="{{ $company->ikh ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="nama">Nama Perusahaan</label>
                <input type="text" id="nama" name="nama" class="form-control" 
                       value="{{ $company->nama ?? '' }}" required>
            </div>

            <div class="mb-3">
                <label for="alamat">Alamat</label>
                <textarea id="alamat" name="alamat" class="form-control">{{ $company->alamat ?? '' }}</textarea>
            </div>

            <div class="mb-3">
                <label for="telp">Telepon</label>
                <input type="text" id="telp" name="telp" class="form-control" 
                       value="{{ $company->telp ?? '' }}">
            </div>

            <div class="mb-3">
                <label for="fax">Fax</label>
                <input type="text" id="fax" name="fax" class="form-control" 
                       value="{{ $company->fax ?? '' }}">
            </div>

            <div class="mb-3">
                <label for="negara">Negara</label>
                <input type="text" id="negara" name="negara" class="form-control" 
                       value="{{ $company->negara ?? '' }}">
            </div>

            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.getElementById('companyForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        ikh: document.getElementById('ikh').value,
        nama: document.getElementById('nama').value,
        alamat: document.getElementById('alamat').value,
        telp: document.getElementById('telp').value,
        fax: document.getElementById('fax').value,
        negara: document.getElementById('negara').value,
        _token: '{{ csrf_token() }}'
    };

    fetch('/company', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
            Swal.fire('Sukses', res.message, 'success');
        } else {
            Swal.fire('Gagal', 'Terjadi kesalahan saat menyimpan.', 'error');
        }
    })
    .catch(() => Swal.fire('Error', 'Gagal mengirim data ke server.', 'error'));
});
</script>
@stop
