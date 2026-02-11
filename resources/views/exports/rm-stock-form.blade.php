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

    <td rowspan="3" width="40%" class="title">
        FORM STOK BAHAN BAKU <br>
        <span class="small">(RAW MATERIALS STOCK FORM)</span>
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
    <td width="15%">
        Bulan <i>(Month)</i>
    </td>
    <td width="35%">
        : {{ now()->translatedFormat('F') }}
    </td>
    <td width="25%">    
        Bagian <i>(Department)</i>
    </td>
    <td width="25%">
        : {{ $document->department->nama_dept }} 
        <i>({{ $document->department->nama_eng }})</i>
    </td>
</tr>
<tr>
    <td width="10%">PIC</td>
    <td width="60%">: {{ $document->employee->nama ?? '-' }}</td>
</tr>
@endsection

@section('data')
@php
    $arrival = $rm->arrivals->first();
@endphp

<thead>
    <tr>
        <th rowspan="2">
            No <br> <i>(No)</i>
        </th>
        <th rowspan="2">
            Tanggal Kedatangan <br> <i>(Arrival Date)</i>
        </th>
        <th rowspan="2">
            Nama BRW / No. Reg <br> <i>(Bird's House Name <br> Registration No.)</i>
        </th>
        <th rowspan="2">
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th rowspan="2">
            Kadar Air (%) <br> <i>(Moisture <br> Content)</i>
        </th>
        <th colspan="2">
            Jumlah Barang Masuk <br> <i>(Quantity of Incoming)</i>
        </th>
        <th rowspan="2">
            Tanggal Keluar <br> <i>Exit Date</i>
        </th>
        <th colspan="2">
            Jumlah Barang Keluar <br> <i>(Quantity of Outgoing)</i>
        </th>
        <th rowspan="2">
            Keterangan <br> <i>(Description)</i>
        </th>
        <th rowspan="2">
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
    <tr>
        <th>
            Biji <br> <i>(Piece)</i>
        </th>
        <th>
            Gram <br> <i>(Gram)</i>
        </th>
        <th>
            Biji <br> <i>(Piece)</i>
        </th>
        <th>
            Gram <br> <i>(Gram)</i>
        </th>
    </tr>
</thead>
<tbody>
@foreach($stocks as $index => $stock)
    <tr>
        <td class="text-center">{{ $index + 1 }}</td>

        <td class="text-center">
            {{ $arrival->tgl_kedatangan ?? '-' }}
        </td>

        <td>
            {{ $arrival->dcertificate->wbhouse->nama ?? '-' }} /
            {{ $arrival->dcertificate->wbhouse->kode ?? '-' }}
        </td>

        <td class="text-center">
            {{ $rm->kode }}
        </td>

        <td class="text-center">
            {{ $rm->kadar_air ?? '-' }}%
        </td>

        <td class="text-right">
            {{ $stock->biji_keluar ?? '-' }}
        </td>

        <td class="text-right">
            {{ $stock->berat_keluar ?? '-' }}
        </td>

        <td class="text-center">
            {{ $stock->tgl_keluar }}
        </td>

        <td class="text-right">
            {{ $stock->biji_keluar }}
        </td>

        <td class="text-right">
            {{ $stock->berat_keluar }}
        </td>

        <td>
            {{ $stock->keterangan ?? '-' }}
        </td>

        <td class="text-center">
            {{ $stock->employee->nama ?? '-' }}
        </td>
    </tr>
    @endforeach
</tbody>
@endsection

<br><br>

@section('other')   
    <tr>
        <td width="50%" class="text-center">
            Dibuat oleh,<br><br><br>
            ( {{ $lastStock?->employee?->nama ?? '-' }} )
        </td>
        <td width="50%" class="text-center">
            Disetujui oleh,<br><br><br>
            ( ....................... )
        </td>
    </tr>
@endsection