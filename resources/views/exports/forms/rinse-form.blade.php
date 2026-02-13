@extends('exports.form')

@section('title', 'Cabut Bilas')

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
    CATATAN PEMERIKSAAN KEBERSIHAN <br>
    SARANG BURUNG WALET <br>
    SELAMA PROSES <br>
    <span class="small">
        <i>
            (CLEANLINESS INSPECTION RECORDS BIRD'S <br>
            NEST DURING THE PROCESS)
        </i>
    </span>
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
        : {{ \Carbon\Carbon::parse($rinses->tanggal)->translatedFormat('F') }}
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
        <th>
            Tanggal <br> <i>(Date)</i>
        </th>
        <th>
            Nama BRW / No. Reg <br> <i>(Bird's House Name <br> Registration No.)</i>
        </th>
        <th>
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th>
            Grade <br> <i>(Grade)</i>
        </th>
        <th>
            Total <i>(Amount) <br> (pcs)</i>
        </th>
        <th>
            Hasil Cek <i>(Check Result)</i>
        </th>
        <th>
            Ket <i>(Desc.)</i>
        </th>
        <th>
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
</thead>
<tbody>
    @php
        $arrival = $rinses->history->identifier->rawMaterial->arrivals->first();
        $rm = $rinses->history->identifier->rawMaterial;
    @endphp
    <tr>
        <td class="text-center">
            {{ $rinses->tgl_mulai }}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama }} /
            {{ $arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>
            {{ $rm->kode}}
        </td>
        <td>
            {{ $rinses->history->identifier->grade->grade }}
        </td>
        <td>
            {{ $rinses->biji_masuk }}
        </td>
        <td>
            {{ (($rinses->cek) === 1 ? 'Lulus Cek' : 'Tidak Lulus Cek') }}
        </td>
        </td>
        <td>
            {{ empty($rinses->keterangan) ? '-' : $rinses->keterangan }}
        </td>
        <td>
            {{ $rinses->employee->nama }}
        </td>
    </tr>
</tbody>
@endsection