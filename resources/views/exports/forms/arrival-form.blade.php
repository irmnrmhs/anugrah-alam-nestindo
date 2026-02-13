@extends('exports.forms.form')

@section('title', 'Pemeriksaan Kendaraaan dan Bahan Baku')

@push('styles')
<style>
    .checkbox {
    display: inline-block;
    width: 14px;
    height: 14px;
    border: 1px solid #000;
    position: relative;
    vertical-align: middle;
}

.checkbox.checked::after {
    content: '';
    position: absolute;
    left: 3px;
    top: 1px;
    width: 5px;
    height: 9px;
    border: solid #000;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
}
</style>
@endpush

@section('header')
<tr>
    <td rowspan="3" width="20%" align="center">
        <img src="{{ public_path('img/Logo.png') }}" width="80" alt="Logo">
    </td>

    <td rowspan="3" width="40%" class="title">
        FORM PEMERIKSAAN<br>
        KENDARAAN DAN BAHAN BAKU<br>
        <span class="small"><i>(Checklist of Receiving Raw Material’s Vehicle)</i></span>
    </td>

    <td width="20%">
        No. Dokumen
        <i>(Document No.)</i>
    </td>
    <td width="20%">
        : {{ $document->no ?? '-' }}
    </td>
</tr>

<tr>
    <td>
        Revisi
        <i>(Revision)</i>
    </td>
    <td>
        : {{ $document->getRevFormattedAttribute() }}
    </td>
</tr>

<tr>
    <td>
        Tanggal
        <i>(Date)</i>
    </td>
    <td>
        : {{ \Carbon\Carbon::parse($document->tgl)->translatedFormat('d F Y') }}
    </td>
</tr>
@endsection

@section('data')
<table class="no-border mt">
    <tr>
        <td>
            <strong>1. Pemeriksaan Kendaraan</strong> <br>
            <i>(Vehicle's Inspection)</i>
        </td>
    </tr>
</table>

<table class="no-border">
    <tr>
        <td width="30%">Tanggal <br> <i>(Date)</i></td>
        <td>: {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>Nama Supir <br> <i>(Driver's Name)</i></td>
        <td>: {{ $arrival->employee->nama }}</td>
    </tr>
    <tr>
        <td>Merk Mobil <br> <i>(Vehicle's Brand)</i></td>
        <td>: {{ $arrival->car->merk }}</td>
    </tr>
    <tr>
        <td>No. Mobil <br> <i>(Vehicle's Plate Number)</i></td>
        <td>: {{ $arrival->car->plat }}</td>
    </tr>
</table>

<table class="no-border mt">
    <tr>
        <td width="40%">Kebersihan Mobil Sudah Bebas Dari : <br>
            <i>(Car Cleanliness is Free From)</i>
        </td>
        <td>
            @php
                $kondisi = strtolower($arrival->kondisi);
            @endphp

            <span class="checkbox {{ str_contains($kondisi, 'sampah') ? 'checked' : '' }}"></span> Sampah
            &nbsp;&nbsp;
            <span class="checkbox {{ str_contains($kondisi, 'oli') ? 'checked' : '' }}"></span> Ceceran Oli
            &nbsp;&nbsp;
            <span class="checkbox {{ str_contains($kondisi, 'benda tajam') ? 'checked' : '' }}"></span> Benda Tajam
        </td>
    </tr>
</table>

{{-- 2. PEMERIKSAAN BAHAN BAKU --}}
<table class="no-border mt">
    <tr><td><strong>2. Pemeriksaan Bahan Baku</strong><br>
        <i>(Raw Material Inspection)</i>
    </td></tr>
</table>

<table class="no-border">
    <tr>
        <td width="30%">
            Kode Bahan Baku <br>
            <i>(Raw Material Code)</i>
        </td>
        <td>: {{ $arrival->kode }}</td>
    </tr>
    <tr>
        <td>Berat (Gram) <br>
            <i>(Weight)</i>
        </td>
        <td>: {{ $arrival->dcertificate->total_berat ?? '-' }}</td>
    </tr>
    <tr>
        <td>Nama RBW / No. Reg <br>
            <i>(Bird's House Name / Registration No.)</i>
        </td>
        <td>: {{ $arrival->dcertificate->wbhouse->nama ?? '-' }} / {{ $arrival->dcertificate->wbhouse->kode }}</td>
    </tr>
    <tr>
        <td>
            Kadar Air (%) <br>
            <i>(Moisture Content)</i>
        </td>
        <td>: {{ empty($arrival->rawMaterial->rmResults->avg('kadar_air')) ? '-' : $arrival->rawMaterial->rmResults->avg('kadar_air') }}%</td>
    </tr>
    <tr>
        <td>
            Keutuhan Seal Carton <br>
            <i>(Carton Seal Integrity)</i>
        </td>
        <td>
            @php
                $hasSeal = str_contains($kondisi, 'seal');
            @endphp

            <span class="checkbox {{ $hasSeal ? 'checked' : '' }}"></span> Yes
            &nbsp;&nbsp;
            <span class="checkbox {{ !$hasSeal ? 'checked' : '' }}"></span> No

        </td>
    </tr>
</table>

@php
    $hasCertificate = !is_null($arrival->dcertificates_id);
@endphp

<table class="no-border mt">
    <tr>
        <td width="40%">
            Surat Keterangan Pengiriman <br>
            <i>(Delivery Certificate)</i>
        </td>
        <td>
            <span class="checkbox {{ $hasCertificate ? 'checked' : '' }}"></span> Yes
            &nbsp;&nbsp;
            <span class="checkbox {{ !$hasCertificate ? 'checked' : '' }}"></span> No
        </td>
    </tr>
</table>

<table class="no-border mt">
    <tr>
        <td>
            Keterangan : <br>
            <i>(Description)</i>
        </td>
        <td>{{ $arrival->keterangan ?? '-' }}</td>
    </tr>
</table>

<table class="no-border mt">
    <tr>
        <td width="50%" align="center">
            Supir <br> <i>(Driver)</i><br><br><br><br>
            ( {{ $arrival->employee->nama }} )
        </td>
        <td width="50%" align="center">
            Kepala Gudang <br> <i>(Head of Warehouse)</i><br><br><br><br>
            ( {{ $document->employee->nama }} )
        </td>
    </tr>
</table>
@endsection