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
        : {{ \Carbon\Carbon::parse($stocks->tanggal)->translatedFormat('F') }}
    </td>
    <td width="25%">
        Departemen <i>(Department)</i>
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
        <th rowspan="2">
            No <br> <i>(No)</i>
        </th>
        <th rowspan="2">
            Tanggal Kedatangan <br> <i>(Arrival Date)</i>
        </th>
        <th rowspan="2">
            Nama BRW / No. Reg <br> <i>(Bird's Houte Name (Bird's House Namе Registration No.))</i>
        </th>
        <th rowspan="2">
            Kode Bahan Baku <br> <i>(Raw Material Code)</i>
        </th>
        <th rowspan="2">
            Kadar Air (%) <br> <i>(Moisture Content)</i>
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
    @php
        $arrival = $stocks->rawMaterial->arrivals->first();
    @endphp
    <tr>
        <td class="text-center">1</td>
        <td class="text-center">
            {{ $arrival->tgl_kedatangan ?? '-' }}
        </td>
        <td>
            {{ $arrival->dcertificate->wbhouse->nama ?? '-' }} /
            {{ $arrival->dcertificate->wbhouse->kode ?? '-' }}
        </td>
        <td class="text-center">
            {{ $stocks->rawMaterial->kode }}
        </td>
        <td class="text-center">
            {{ $stocks->rawMaterial->kadar_air ?? '-' }}%
        </td>
        <td class="text-right">
            {{ $stocks->rawMaterial->biji ?? '-' }}
        </td>
        <td class="text-right">
            {{ $stocks->rawMaterial->berat ?? '-' }}
        </td>
        {{-- terpisah looping --}}
        <td class="text-center">
            {{ $stocks->tgl_keluar }}
        </td>
        <td class="text-right">
            {{ $stocks->biji_keluar }}
        </td>
        <td class="text-right">
            {{ $stocks->berat_keluar }}
        </td>
        <td>
            {{ $stocks->keterangan ?? '-' }}
        </td>
        <td class="text-center">-</td>
    </tr>
</tbody>
@endsection

<br><br>

@section('other')
<tr>
    <td width="50%" class="text-center">
        Dibuat oleh,<br><br><br>
        ( {{ $stocks->employee->nama }} )
    </td>
    <td width="50%" class="text-center">
        Disetujui oleh,<br><br><br>
        ( ....................... )
    </td>
</tr>
@endsection