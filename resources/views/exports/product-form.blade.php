@extends('exports.form')

@section('title', 'Produk Jadi')

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
    FORM GRADING <br>
    <span class="small"><i>(GRADING FORM)</i></span>
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
        : {{ \Carbon\Carbon::parse($products->tanggal)->translatedFormat('F') }}
    </td>
    <td width="25%">
        Bagian <i>(Department)</i>
    </td>
    <td width="25%">
        : {{ $document->department->nama_dept }}
        ({{ $document->department->nama_eng }})
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
        <th>
            No <br> <i>(No)</i>
        </th>
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
            Kode Proses <br> <i>(Process Code)</i>
        </th>
        <th>
            Grade <br> <i>(Grade)</i>
        </th>
        <th>
            Biji <i>(Piece)</i>
        </th>
        <th>
            Gram <i>(Gram)</i>
        </th>
        <th>
            Keterangan <i>(Keterangan)</i>
        </th>
        <th>
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
</thead>
<tbody>
    @php
        $arrival = $products->history->identifier->rawMaterial->arrivals->first();
        $rm = $products->history->identifier->rawMaterial;
    @endphp
    <tr>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ $products->tgl_mulai }}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama }} /
            {{ $arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>
            {{ $rm->kode}}
        </td>
        <td>
            {{-- {{ $rm->kd_proses}} --}}
        </td>
        <td>
            {{ $products->history->identifier->grade->grade }}
        </td>
        <td>
            {{ $products->biji }}
        </td>
        <td>
            {{ $products->berat }}
        </td>
        <td>
            {{ empty($products->keterangan) ? '-' : $products->keterangan }}
        </td>
        <td>
            {{ $products->employee->nama }}
        </td>
    </tr>
</tbody>
@endsection