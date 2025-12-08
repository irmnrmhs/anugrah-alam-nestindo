@php
    $title = 'Tracker';
    $singular = 'Tracker';
    $hideAddButton = true;
    $hideActions = true;
@endphp

@extends('layouts.form')

@php
    $title = 'Tracker';
    $singular = 'Tracker';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Produk</th>
    <th>Asal</th>
    <th>Tujuan</th>
    <th>Total Biji</th>
    <th>Total Berat</th>
    <th>Sisa Biji</th>
    <th>Sisa Berat</th>
@stop

@section('table-body')
    @foreach($histories as $index => $history)
        <tr data-id="{{ $history->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $history->identifier->kode }}</td>
            {{-- <td>{{ $history->asal }}</td>
            <td>{{ $history->tujuan }}</td> --}}
            <td>
                @if($history->asal === 'PR01GB')
                    <span>Grading Bahan Baku</span>
                @elseif ($history->asal === 'PR02SK')
                    <span>Sesek Kaki</span>
                @elseif ($history->asal === 'PR03PC')
                    <span>Pencucian</span>
                @elseif ($history->asal === 'PR04IK')
                    <span>Inspeksi dan Koreksi</span>
                @elseif ($history->asal === 'PR05PB')
                    <span>Pencabutan Bulu</span>
                @elseif ($history->asal === 'PR06PR')
                    <span>Perendaman</span>
                @elseif ($history->asal === 'PR07CB')
                    <span>Cabut Bilas</span>
                @elseif ($history->asal === 'PR08MC')
                    <span>Masuk Cetak</span>
                @elseif ($history->asal === 'PR09KC')
                    <span>Keluar Cetak</span>
                @elseif ($history->asal === 'PR10PK')
                    <span>Pengeringan</span>
                @elseif ($history->asal === 'PR11GP')
                    <span>Grading Produk Jadi</span>
                @elseif ($history->asal === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->asal === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td>
            <td>
                @if($history->tujuan === 'PR01GB')
                    <span>Grading Bahan Baku</span>
                @elseif ($history->tujuan === 'PR02SK')
                    <span>Sesek Kaki</span>
                @elseif ($history->tujuan === 'PR03PC')
                    <span>Pencucian</span>
                @elseif ($history->tujuan === 'PR04IK')
                    <span>Inspeksi dan Koreksi</span>
                @elseif ($history->tujuan === 'PR05PB')
                    <span>Pencabutan Bulu</span>
                @elseif ($history->tujuan === 'PR06PR')
                    <span>Perendaman</span>
                @elseif ($history->tujuan === 'PR07CB')
                    <span>Cabut Bilas</span>
                @elseif ($history->tujuan === 'PR08MC')
                    <span>Masuk Cetak</span>
                @elseif ($history->tujuan === 'PR09KC')
                    <span>Keluar Cetak</span>
                @elseif ($history->tujuan === 'PR10PK')
                    <span>Pengeringan</span>
                @elseif ($history->tujuan === 'PR11GP')
                    <span>Grading Produk Jadi</span>
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td>
            <td>{{ $history->biji }}</td>
            <td>{{ $history->berat }}</td>
            <td>{{ $history->sisa_biji_sesek }}</td>
            <td>{{ $history->sisa_berat_sesek }}</td>
        </tr>
    @endforeach
@stop