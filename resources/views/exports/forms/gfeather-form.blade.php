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

@php
    $arrival = $rm->arrivals->first();

    // Group feather berdasarkan tanggal + employee
    $grouped = $feathers->groupBy(function ($item) {
        return $item->tanggal . '-' . $item->employees_id;
    });
@endphp

<thead>
<tr>
    <th rowspan="2">No</th>
    <th rowspan="2">Tanggal</th>
    <th rowspan="2">Nama BRW / No. Reg</th>
    <th rowspan="2">Kode Bahan Baku</th>
    <th rowspan="2">Mangkok (MK)</th>
    <th rowspan="2">Oval (OVL)</th>
    <th rowspan="2">Sudut (SDT)</th>
    <th rowspan="2">Patahan (PTH)</th>
    <th rowspan="2">Hancuran (HCR)</th>
    <th colspan="2">BRP</th>
    <th colspan="2">BS</th>
    <th colspan="2">BB</th>
    <th rowspan="2">Petugas</th>
</tr>
<tr>
    <th>Biji</th><th>Gram</th>
    <th>Biji</th><th>Gram</th>
    <th>Biji</th><th>Gram</th>
</tr>
</thead>

<tbody>
@foreach($grouped as $key => $items)

    @php
        $first = $items->first();

        $brp = $items->firstWhere('feather.kode', 'BRP');
        $bs  = $items->firstWhere('feather.kode', 'BS');
        $bb  = $items->firstWhere('feather.kode', 'BB');

        $shapeItems = $shapes
            ->where('tanggal', $first->tanggal);

        $mk  = $shapeItems->firstWhere('shape.kode', 'MK');
        $ovl = $shapeItems->firstWhere('shape.kode', 'OVL');
        $sdt = $shapeItems->firstWhere('shape.kode', 'SDT');
        $pth = $shapeItems->firstWhere('shape.kode', 'PTH');
        $hcr = $shapeItems->firstWhere('shape.kode', 'HCR');
    @endphp

<tr>
    <td class="text-center">{{ $loop->iteration }}</td>

    <td class="text-center">
        {{ $first->tanggal }}
    </td>

    <td>
        {{ $arrival->dcertificate->wbhouse->nama ?? '-' }} /
        {{ $arrival->dcertificate->wbhouse->kode ?? '-' }}
    </td>

    <td class="text-center">
        {{ $rm->kode }}
    </td>

    {{-- SHAPE --}}
    <td class="text-right">{{ $mk->berat  ?? '-' }}</td>
    <td class="text-right">{{ $ovl->berat ?? '-' }}</td>
    <td class="text-right">{{ $sdt->berat ?? '-' }}</td>
    <td class="text-right">{{ $pth->berat ?? '-' }}</td>
    <td class="text-right">{{ $hcr->berat ?? '-' }}</td>

    {{-- BRP --}}
    <td class="text-right">{{ $brp->biji ?? '-' }}</td>
    <td class="text-right">{{ $brp->berat ?? '-' }}</td>

    {{-- BS --}}
    <td class="text-right">{{ $bs->biji ?? '-' }}</td>
    <td class="text-right">{{ $bs->berat ?? '-' }}</td>

    {{-- BB --}}
    <td class="text-right">{{ $bb->biji ?? '-' }}</td>
    <td class="text-right">{{ $bb->berat ?? '-' }}</td>

    <td class="text-center">
        {{ $first->employee->nama ?? '-' }}
    </td>
</tr>

@endforeach
</tbody>
@endsection