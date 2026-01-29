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
    FORM KEDATANGAN BAHAN BAKU <br>
    <span class="small"><i>(RAW MATERIALS ARRIVAL FORM)</i></span>
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
@php
    $first = $containers->first();
@endphp

<tr>
    <td width="15%">
        Bulan <i>(Month)</i>
    </td>
    <td width="35%">
        : {{ \Carbon\Carbon::parse($first->tanggal)->translatedFormat('F') }}
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
<thead>
    <tr>
        <th rowspan="2">
            No <br> <i>(No)</i>
        </th>
        <th rowspan="2">
            Tanggal Kedatangan <br> <i>(Arrival Date)</i>
        </th>
        <th rowspan="2">
            Nama RBW / No. Reg <br> <i>(Bird's House Name /Registration No.)</i>
        </th>
        <th rowspan="2">
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th colspan="2">
            Jumlah <br> <i>(Amount)</i>
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
    </tr>
</thead>
<tbody>
    @foreach ($containers as $i => $row)
    <tr>
        <td>{{ $i + 1 }}</td>
        <td>
            {{ \Carbon\Carbon::parse($row->arrival->tgl_kedatangan)->format('d-m-Y') }}
        </td>
        <td class="text-left">
            {{ $row->arrival->dcertificate->wbhouse->nama ?? '-' }} / 
            {{ $row->arrival->dcertificate->wbhouse->kode }}
        </td>
        <td>{{ $row->arrival->kode }}</td>
        <td>{{ $row->biji ?? 0 }}</td>
        <td>{{ $row->berat ?? 0 }}</td>
        <td>{{ $row->keterangan ?? '-' }}</td>
        <td>{{ $row->employee->nama }}</td>
    </tr>
    @endforeach
</tbody>
@endsection