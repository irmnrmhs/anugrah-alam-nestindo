@extends('exports.form')

@section('title', 'Pengeringan')

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
    FORM PENGERINGAN <br>
    <span class="small"><i>(DRYING FORM)</i></span>
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
        : {{ \Carbon\Carbon::parse($dries->tanggal)->translatedFormat('F') }}
    </td>
    <td width="25%">
        Bagian <i>(Department)</i>
    </td>
    <td width="25%">
        : {{ $document->department->nama_dept }}
        <i>{{ $document->department->nama_eng }}</i>
    </td>
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
            Total <i>(Amount)</i>
        </th>
        <th>
            Masuk <i>(In)</i>
        </th>
        <th>
            Keluar <i>(Out)</i>
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
            Waktu <i>(Time)</i>
        </th>
        <th>
            Waktu <i>(Time)</i>
        </th>
    </tr>
</thead>
<tbody>
    @php
        $arrival = $dries->history->identifier->rawMaterial->arrivals->first();
        $rm = $dries->history->identifier->rawMaterial;
    @endphp
    <tr>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ $dries->tgl_mulai }}
        </td>
        <td>
            {{ $rm->kode}}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama }} /
            {{ $arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>
            {{ $dries->history->identifier->grade->grade }}
        </td>
        <td>
            {{ $dries->biji_masuk }}
        </td>
        <td>
            {{ $dries->waktu_masuk }}
        </td>
        <td>
            {{ $dries->waktu_keluar }}
        </td>
        <td>
            {{ $dries->employee->nama }}
        </td>
    </tr>
</tbody>
@endsection