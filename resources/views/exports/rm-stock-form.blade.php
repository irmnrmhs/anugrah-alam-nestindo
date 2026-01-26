@extends('exports.form')

@section('title', 'Kedatangan Bahan Baku')

@push('styles')
<style>
    
</style>
@endpush

@section('header')
<tr>
    <td rowspan="3" width="20%" align="center">
        <img src="{{ public_path('img/Logo.png') }}" width="80" alt="Logo">
    </td>

    <td rowspan="3" width="50%" class="title">
        FORM STOK BAHAN BAKU <br>
        <span class="small">(RAW MATERIALS STOCK FORM)</span>
    </td>

    <td width="15%">
        No. Dokumen
        <i>(Document No.)</i>
    </td>
    <td width="15%">
        : {{ $document->no ?? '-' }}
    </td>
</tr>

<tr>
    <td>
        Revisi
        <i>(Revision)</i>
    </td>
    <td>
        : Rev-{{ $document->getRevFormattedAttribute() }}
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

<br>

@section('info')
<tr>
    <td width="10%">Bulan</td>
    <td width="60%">
        : {{ \Carbon\Carbon::parse($stocks->tanggal)->translatedFormat('F') }}
    </td>
    <td width="15%">Departemen</td>
    <td width="15%">: {{ $document->department->nama_dept }}</td>
</tr>
<tr>
    <td width="10%">PIC</td>
    <td width="60%">: {{ $document->employee->nama ?? '-' }}</td>
</tr>
@endsection

@section('data')
<thead>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Tanggal Kedatangan</th>
        <th rowspan="2">Nama BRW / No. Reg</th>
        <th rowspan="2">Kode Bahan Baku</th>
        <th rowspan="2">Kadar Air (%)</th>
        <th colspan="2">Jumlah Barang Masuk</th>
        <th rowspan="2">Tanggal Keluar</th>
        <th colspan="2">Jumlah Barang Keluar</th>
        <th rowspan="2">Keterangan</th>
        <th rowspan="2">Paraf PIC</th>
    </tr>
    <tr>
        <th>Biji</th>
        <th>Gram</th>
        <th>Biji</th>
        <th>Gram</th>
    </tr>
</thead>
<tbody>
    <tr>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ $stocks->rawMaterial->tgl_masuk ?? '-' }}
        </td>
        <td>
            {{ $stocks->rawMaterial->nama ?? '-' }} /
            {{ $stocks->rawMaterial->no_reg ?? '-' }}
        </td>
        <td class="text-center">
            {{ $stocks->rawMaterial->kode }}
        </td>
        <td class="text-center">
            {{ $stocks->rawMaterial->kadar_air ?? '-' }}%
        </td>
        <td class="text-right">
            {{ $stocks->rawMaterial->biji_masuk ?? '-' }}
        </td>
        <td class="text-right">
            {{ $stocks->rawMaterial->berat_masuk ?? '-' }}
        </td>
        <td class="text-center">
            {{ $stocks->tgl_keluar }}
        </td>
        <td class="text-right">
            {{ $stocks->biji_keluar }}
        </td>
        <td class="text-right">
            {{ $stocks->berat_keluar }}
        </td>
        <td>
            {{ $stocks->keterangan ?? '-' }}
        </td>
        <td class="text-center">-</td>
    </tr>
</tbody>
@endsection

<br><br>

<table class="no-border">
    <tr>
        <td width="50%" class="text-center">
            Dibuat oleh,<br><br><br>
            ( {{ $stocks->employee->nama }} )
        </td>
        <td width="50%" class="text-center">
            Disetujui oleh,<br><br><br>
            ( ....................... )
        </td>
    </tr>
</table>