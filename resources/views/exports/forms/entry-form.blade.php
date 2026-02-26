@extends('exports.forms.form')

@section('title', 'Masuk Cetak')

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
    FORM BARANG MASUK CETAK <br>
    <span class="small"><i>(MOLD INCOMING FORM)</i></span>
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
        : {{ \Carbon\Carbon::parse($histories->first()?->edges->first()?->tanggal)->translatedFormat('F') ?? '-' }}
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
        <th rowspan="2">
            Petugas <br> <i>(Officer)</i>
        </th>
    </tr>
    <tr>
        <th>
            Biji <i>(Piece)</i>
        </th>
    </tr>
</thead>
<tbody>
@php $no = 1; @endphp

@foreach($histories as $history)
    @foreach($history->entries as $entry)
        @continue($history->biji == 0)
        <tr>
            <td class="text-center">{{ $no++ }}</td>
            <td class="text-center">{{ $history->tanggal }}</td>
            <td>{{ $entry->history->rm }}</td>
            <td>{{ $entry->history->rbw }}</td>
            <td>{{ $entry->history->grade }}</td>
            <td>{{ $entry->biji ?? 0 }}</td>
            <td>{{ $entry->employee?->nama ?? '-' }}</td>
        </tr>
    @endforeach
@endforeach
</tbody>
@endsection