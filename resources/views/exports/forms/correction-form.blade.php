@extends('exports.form')

@section('title', 'Inspeksi dan Koreksi')

@push('styles')
<style>
    
</style>
@endpush

@section('header')
<tr>
<td rowspan="3" width="20%" align="center">
    <img src="{{ public_path('img/Logo.png') }}" width="80" alt="Logo">
</td>

<td rowspan="3" width="40%" class="title">
    CATATAN INSPEKSI DAN KOREKSI <br>
    SARANG BURUNG WALET <br>
    <span class="small"><i>(INSPECTION AND CORRECTION RECORDS <br> BIRD'S NEST)</i></span>
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

<br>

@section('info')
<tr>
    <td width="10%">
        Bulan <i>(Month)</i>
    </td>
    <td width="35%">
        : {{ \Carbon\Carbon::parse($corrections->tanggal)->translatedFormat('F') }}
    </td>
</tr>
<tr>
    <td width="10%">
        Bagian <i>(Department)</i>
    </td>
    <td width="25%">: {{ $document->department->nama_dept }}</td>
</tr>
@endsection

@section('data')
<thead>
    <tr>
        <th >
            Tanggal <br> <i>(Date)</i>
        </th>
        <th >
            Nama BRW / No. Reg <br> <i>(Bird's House Name <br> Registration No.)</i>
        </th>
        <th >
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th >
            Grade <br> <i>(Grade)</i>
        </th>
        <th>
            Total <i> <br> (Amount) <br> (pcs)</i>
        </th>
        <th>
            Hasil Cek <i>(Check <br> Result)</i>
        </th>
        <th>
            Keterangan <br> <i>(Desc.)</i>
        </th>
        <th >
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
</thead>
<tbody>
    @php
        $arrival = $corrections->history->identifier->rawMaterial->arrivals->first();
        $rm = $corrections->history->identifier->rawMaterial;
    @endphp
    <tr>
        <td class="text-center">
            {{ $corrections->tanggal }}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama }} /
            {{ $arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>
            {{ $rm->kode}}
        </td>
        <td>
            {{ $corrections->history->identifier->grade->grade }}
        </td>
        <td>
            {{ $corrections->biji }}
        </td>
        <td>
            {{ (($corrections->cek) === 1 ? 'Lulus Cek' : 'Tidak Lulus Cek') }}
        </td>
        <td>
            {{ empty($corrections->keterangan) ? '-' : $corrections->keterangan }}
        </td>
        <td>
            {{ $corrections->employee->nama }}
        </td>
    </tr>
</tbody>
<table>
    <tr>
        <td>Standar Lulus Cek</td>
        <td>: Bersih dari cemaran fisik dengan jarak 20 -30 cm secara visual.</td>
    </tr>
    <tr>
        <td>
            <i>Standard Passed Check</i>
        </td>
        <td>
            <i>: Clean from physical contaminants with a visual distance of 20-30 ст.</i>
        </td>
    </tr>
</table>
@endsection