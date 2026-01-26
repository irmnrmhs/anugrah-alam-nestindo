@extends('exports.form')

@section('title', 'Pemeriksaan Kendaraaan dan Bahan Baku')

@push('styles')
<style>
    
</style>
@endpush

@section('header')
<table class="header">
    <tr>
        <td rowspan="3" width="15%" align="center">
            <img src="{{ public_path('img/Logo.png') }}" width="80" alt="Logo">
        </td>

        <td rowspan="3" width="50%" class="title">
            FORM PEMERIKSAAN<br>
            KENDARAAN DAN BAHAN BAKU<br>
            <span class="small">(Checklist of Receiving Raw Material’s Vehicle)</span>
        </td>

        <td width="15%">
            No. Dokumen <br>
            <i>(Document No.)</i>
        </td>
        <td width="20%">
            : {{ $document->no ?? '-' }}
        </td>
    </tr>

    <tr>
        <td>
            Revisi <br>
            <i>(Revision)</i>
        </td>
        <td>
            : Rev-{{ $document->getRevFormattedAttribute() }}
        </td>
    </tr>

    <tr>
        <td>
            Tanggal <br>
            <i>(Date)</i>
        </td>
        <td>
            : {{ \Carbon\Carbon::parse($document->tgl)->translatedFormat('d F Y') }}
        </td>
    </tr>
</table>
@endsection

@section('data')
<table class="data">
    <table class="no-border mt">
        <tr><td><strong>1. Pemeriksaan Kendaraan</strong></td></tr>
    </table>

    <table class="no-border">
        <tr>
            <td width="30%">Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td>Nama Supir</td>
            <td>: {{ $arrival->employee->nama }}</td>
        </tr>
        <tr>
            <td>Merk Mobil</td>
            <td>: {{ $arrival->car->merk }}</td>
        </tr>
        <tr>
            <td>No. Mobil</td>
            <td>: {{ $arrival->car->plat }}</td>
        </tr>
    </table>

    <table class="no-border mt">
        <tr>
            <td width="40%">Kebersihan Mobil Sudah Bebas Dari :</td>
            <td>
                @php
                    $kondisi = strtolower($arrival->kondisi);
                @endphp

                <span class="checkbox">{{ str_contains($kondisi, 'sampah') ? 'v' : '' }}</span> Sampah
                &nbsp;&nbsp;
                <span class="checkbox">{{ str_contains($kondisi, 'oli') ? 'v' : '' }}</span> Ceceran Oli
                &nbsp;&nbsp;
                <span class="checkbox">{{ str_contains($kondisi, 'benda tajam') ? 'v' : '' }}</span> Benda Tajam
            </td>
        </tr>
    </table>

    {{-- 2. PEMERIKSAAN BAHAN BAKU --}}
    <table class="no-border mt">
        <tr><td><strong>2. Pemeriksaan Bahan Baku</strong></td></tr>
    </table>

    <table class="no-border">
        <tr>
            <td width="30%">Kode Bahan Baku</td>
            <td>: {{ $arrival->kode }}</td>
        </tr>
        <tr>
            <td>Berat (Gram)</td>
            <td>: {{ $arrival->rawMaterial->berat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama RBW / No. Reg</td>
            <td>: {{ $arrival->dcertificate->wbhouse->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kadar Air (%)</td>
            <td>: {{ $arrival->rawMaterial->kadar_air ?? '-' }}</td>
        </tr>
    </table>

    <table class="no-border mt">
        <tr>
            <td width="40%">Surat Keterangan Pengiriman</td>
            <td>
                <span class="checkbox">v</span> Yes
                &nbsp;&nbsp;
                <span class="checkbox"></span> No
            </td>
        </tr>
    </table>

    <table class="no-border mt">
        <tr>
            <td>Keterangan :</td>
        </tr>
        <tr>
            <td>{{ $arrival->keterangan ?? '-' }}</td>
        </tr>
    </table>
</table>
@endsection

@section('other')
    <table class="no-border mt">
        <tr>
            <td width="50%" align="center">
                Supir<br><br><br>
                ( {{ $arrival->employee->nama }} )
            </td>
            <td width="50%" align="center">
                Kepala Gudang<br><br><br>
            </td>
        </tr>
    </table>
@endsection
