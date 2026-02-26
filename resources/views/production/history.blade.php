@php
    $title = 'Tracker';
    $singular = 'Tracker';
    $hideAddButton = true;
    $hideActions = true;
    $hideImportButton = true;
@endphp

@extends('layouts.form')

@php
    $title = 'Tracker';
    $singular = 'Tracker';
@endphp

@section('table-headers')
    <th>No</th>
    <th>Kode Bahan Baku</th>
    <th>Grade</th>
    <th>Asal</th>
    <th>Tujuan</th>
    <th>Biji Awal</th>
    <th>Biji Sisa</th>
@stop

@section('table-body')
    @foreach($histories as $index => $history)
        <tr data-id="{{ $history->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $history->gcolor->rawMaterial->kode }}</td>
            <td>{{ $history->gcolor->grade }}</td>
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
            <td>
                @if ($history->tujuan === 'PR02SK')
                    {{ $history->sisa_biji_sesek }}
                @elseif ($history->tujuan === 'PR03PC')
                    {{ $history->sisa_biji_cuci }}
                @elseif ($history->tujuan === 'PR04IK')
                    {{ $history->sisa_biji_koreksi }}
                @elseif ($history->tujuan === 'PR05PB')
                    {{ $history->sisa_biji_cabut }}
                @elseif ($history->tujuan === 'PR06PR')
                    {{ $history->sisa_biji_rendam }}
                @elseif ($history->tujuan === 'PR07CB')
                    {{ $history->sisa_biji_bilas }}
                @elseif ($history->tujuan === 'PR08MC')
                    {{ $history->sisa_biji_entry }}
                @elseif ($history->tujuan === 'PR09KC')
                    {{ $history->sisa_biji_keluar }}
                @elseif ($history->tujuan === 'PR10PK')
                    {{ $history->sisa_biji_kering }}
                @elseif ($history->tujuan === 'PR11GP')
                    {{ $history->sisa_biji_produk }}
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td>
            {{-- <td>
                @if ($history->tujuan === 'PR02SK')
                    0
                @elseif ($history->tujuan === 'PR03PC')
                    0
                @elseif ($history->tujuan === 'PR04IK')
                    0
                @elseif ($history->tujuan === 'PR05PB')
                    0
                @elseif ($history->tujuan === 'PR06PR')
                    0
                @elseif ($history->tujuan === 'PR07CB')
                    0
                @elseif ($history->tujuan === 'PR08MC')
                    0
                @elseif ($history->tujuan === 'PR09KC')
                    0
                @elseif ($history->tujuan === 'PR10PK')
                    0
                @elseif ($history->tujuan === 'PR11GP')
                    {{ $history->sisa_berat_produk }}
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td> --}}
        </tr>
    @endforeach
@stop