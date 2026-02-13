@extends('exports.form')

@section('title', 'Perendaman')

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
    FORM PERENDAMAN (CCP 1) <br>
    <span class="small"><i>(SOAKING FORM (CCP1 1))</i></span>
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
    <td width="15%">
        Bulan <i>(Month)</i>
    </td>
    <td width="35%">
        : {{ \Carbon\Carbon::parse($soaks->tanggal)->translatedFormat('F') }}
    </td>
    <td width="25%">
        Bagian <i>(Department)</i>
    </td>
    <td width="25%">: {{ $document->department->nama_dept }}</td>
</tr>
<tr>
    <td width="10%">PIC</td>
    <td width="60%">: {{ $document->employee->nama ?? '-' }}</td>
</tr>
@endsection

@section('data')
<thead>
    <tr>
        <th rowspan="2">
            No <br> <i>(No)</i>
        </th>
        <th rowspan="2">
            Tanggal <br> <i>(Date)</i>
        </th>
        <th rowspan="2">
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th rowspan="2">
            Nama BRW / No. Reg <br> <i>(Bird's House Name <br> Registration No.)</i>
        </th>
        <th rowspan="2">
            Grade <br> <i>(Grade)</i>
        </th>
        <th>
            Total <br> <i>(Amount)</i>
        </th>
        <th>
            Waktu Rendam <br> <i>(Soaking Time)</i>
        </th>
        <th>
            Keterangan <br> <i>(Description)</i>
        </th>
        <th rowspan="2">
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
    <tr>
        <th>
            Biji <i>(Piece)</i>
        </th>
        <th>
            Menit <i>(Minute)</i>
        </th>
    </tr>
</thead>
<tbody>
    @php
        $arrival = $soaks->history->identifier->rawMaterial->arrivals->first();
        $rm = $soaks->history->identifier->rawMaterial;
    @endphp
    <tr>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ $soaks->tgl_mulai }}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama }} /
            {{ $arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>
            {{ $rm->kode}}
        </td>
        <td>
            {{ $soaks->history->identifier->grade->grade }}
        </td>
        <td>
            {{ $soaks->biji_masuk }}
        </td>
        <td>
            {{ $soaks->durasi }}
        </td>
        <td>
            {{ empty($soaks->keterangan) ? '-' : $soaks->keterangan }}
        </td>
        <td>
            {{ $soaks->employee->nama }}
        </td>
    </tr>
</tbody>
@endsection