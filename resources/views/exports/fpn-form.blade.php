@extends('exports.form')

@section('title', 'Kadar Nitrit Produk Jadi')

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
    CATATAN PEMERIKSAAN <br> KADAR NITRIT PRODUK JADI <br>
    <span class="small"><i>(Inspection Record Nitrite Content of <br>Finished Product)</i></span>
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
    <td width="40%">
        Tanggal pemeriksaan <i>(Inspection Date)</i>
    </td>
    <td width="60%">
        {{-- : {{ \Carbon\Carbon::parse($bbs->tgl)->translatedFormat('F') }} --}}
        : {{ $fps->tgl }}
    </td>
</tr>
@php
    $arrival = $fps->product->product->history->identifier->rawMaterial->arrivals->first();
@endphp
<tr>
    <td width="10%">Sampel</td>
    <td width="60%">:
        {{ $arrival->dcertificate->wbhouse->nama }} /
        {{ $arrival->dcertificate->wbhouse->kode }}
    </td>
</tr>
@endsection

@section('data')
    
@endsection