@extends('exports.form')

@section('title', 'Grading Bahan Baku')

@push('styles')
<style>
    
</style>
@endpush

@section('content')

<table class="header">
    <tr>
        <td rowspan="3" width="20%" align="center">
            <img src="{{ public_path('img/Logo.png') }}" width="80" alt="Logo">
        </td>

        <td rowspan="3" width="50%" class="title">
            FORM GRADING<br>
            BAHAN BAKU<br>
            <span class="small">(Grading Form Raw Material)</span>
        </td>

        <td width="15%">
            No. Dokumen
            <i>(Document No.)</i>
        </td>
        <td width="15%">
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
</table>

<br>

<table class="info">
    <tr>
        <td width="10%">Bulan</td>
        <td width="60%">
            : {{ \Carbon\Carbon::parse($identifiers->tanggal)->translatedFormat('F') }}
        </td>
        <td width="15%">Departemen</td>
        <td width="15%">: {{ $document->department->nama_dept }}</td>
    </tr>
    <tr>
        <td width="10%">PIC</td>
        <td width="60%">: {{ $document->employee->nama ?? '-' }}</td>
    </tr>
</table>

<table class="data">
    {{-- isi data --}}
</table>

@endsection
