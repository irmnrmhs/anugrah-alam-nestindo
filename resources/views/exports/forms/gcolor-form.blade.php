@extends('exports.forms.form')

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
        FORM GRADING BAHAN BAKU <br>
        <span class="small">(RAW MATERIALS GRADING FORM)</span>
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
<thead>
<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Tanggal</th>
    <th rowspan="2">Nama BRW / No. Reg</th>
    <th rowspan="2">Kode Bahan Baku</th>
    <th colspan="2">P</th>
    <th colspan="2">PB</th>
    <th colspan="2">PG</th>
    <th rowspan="2">Jenis Bulu</th>
    <th rowspan="2">Hancuran</th>
    <th rowspan="2">Petugas</th>
</tr>
<tr>
    <th>Biji</th><th>Gram</th>
    <th>Biji</th><th>Gram</th>
    <th>Biji</th><th>Gram</th>
</tr>
</thead>
<tbody>
    <td class="text-center">{{ $loop->iteration }}</td>
    
</tbody>

@endsection