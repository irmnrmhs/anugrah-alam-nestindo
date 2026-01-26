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
    @php
        $first = $containers->first();
    @endphp
    
    <tr>
        <td width="10%">Bulan</td>
        <td width="60%">
            : {{ \Carbon\Carbon::parse($first->tanggal)->translatedFormat('F') }}
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
    <thead>
        <tr>
            <th rowspan="2">No</th>
            <th rowspan="2">Tanggal Kedatangan</th>
            <th rowspan="2">Nama RBW / No. Reg</th>
            <th rowspan="2">Kode Bahan Baku</th>
            <th colspan="2">Jumlah</th>
            <th rowspan="2">Keterangan</th>
            <th rowspan="2">Petugas</th>
        </tr>
        <tr>
            <th>Biji</th>
            <th>Gram</th>
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
                {{ $row->arrival->dcertificate->wbhouse->nama ?? '-' }}
            </td>
            <td>{{ $row->arrival->kode }}</td>
            <td>{{ $row->biji ?? 0 }}</td>
            <td>{{ $row->berat ?? 0 }}</td>
            <td>{{ $row->keterangan ?? '-' }}</td>
            <td>{{ $row->employee->nama }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

@endsection
