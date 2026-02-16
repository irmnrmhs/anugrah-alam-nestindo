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
    <th>Kode Produk</th>
    <th>Asal</th>
    <th>Tujuan</th>
    <th>Biji Awal</th>
    <th>Berat Awal</th>
    <th>Biji Sisa</th>
    <th>Berat Sisa</th>
@stop

@section('table-body')
    @foreach($histories as $index => $history)
        <tr data-id="{{ $history->id }}">
            <td>{{ $index + 1 }}</td>
            <td>{{ $history->gcolor->kode }}</td>
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
            <td>
                @if ($history->tujuan === 'PR02SK')
                    {{ $history->sisa_berat_sesek }}
                @elseif ($history->tujuan === 'PR03PC')
                    {{ $history->sisa_berat_cuci }}
                @elseif ($history->tujuan === 'PR04IK')
                    {{ $history->sisa_berat_koreksi }}
                @elseif ($history->tujuan === 'PR05PB')
                    {{ $history->sisa_berat_cabut }}
                @elseif ($history->tujuan === 'PR06PR')
                    {{ $history->sisa_berat_rendam }}
                @elseif ($history->tujuan === 'PR07CB')
                    {{ $history->sisa_berat_bilas }}
                @elseif ($history->tujuan === 'PR08MC')
                    {{ $history->sisa_berat_entry }}
                @elseif ($history->tujuan === 'PR09KC')
                    {{ $history->sisa_berat_keluar }}
                @elseif ($history->tujuan === 'PR10PK')
                    {{ $history->sisa_berat_kering }}
                @elseif ($history->tujuan === 'PR11GP')
                    {{ $history->sisa_berat_produk }}
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td>
            {{-- <td>
                @if ($history->tujuan === 'PR02SK')
                    {{ $history->diproses_biji_sesek }}
                @elseif ($history->tujuan === 'PR03PC')
                    {{ $history->diproses_biji_cuci }}
                @elseif ($history->tujuan === 'PR04IK')
                    {{ $history->total_biji_koreksi }}
                @elseif ($history->tujuan === 'PR05PB')
                    {{ $history->total_biji_cabut }}
                @elseif ($history->tujuan === 'PR06PR')
                    {{ $history->total_biji_rendam }}
                @elseif ($history->tujuan === 'PR07CB')
                    {{ $history->total_biji_bilas }}
                @elseif ($history->tujuan === 'PR08MC')
                    {{ $history->total_biji_entry }}
                @elseif ($history->tujuan === 'PR09KC')
                    {{ $history->total_biji_keluar }}
                @elseif ($history->tujuan === 'PR10PK')
                    {{ $history->total_biji_kering }}
                @elseif ($history->tujuan === 'PR11GP')
                    {{ $history->total_biji_produk }}
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td>
            <td>
                @if ($history->tujuan === 'PR02SK')
                    {{ $history->total_berat_sesek }}
                @elseif ($history->tujuan === 'PR03PC')
                    {{ $history->total_berat_cuci }}
                @elseif ($history->tujuan === 'PR04IK')
                    {{ $history->total_berat_koreksi }}
                @elseif ($history->tujuan === 'PR05PB')
                    {{ $history->total_berat_cabut }}
                @elseif ($history->tujuan === 'PR06PR')
                    {{ $history->total_berat_rendam }}
                @elseif ($history->tujuan === 'PR07CB')
                    {{ $history->total_berat_bilas }}
                @elseif ($history->tujuan === 'PR08MC')
                    {{ $history->total_berat_entry }}
                @elseif ($history->tujuan === 'PR09KC')
                    {{ $history->total_berat_keluar }}
                @elseif ($history->tujuan === 'PR10PK')
                    {{ $history->total_berat_kering }}
                @elseif ($history->tujuan === 'PR11GP')
                    {{ $history->total_berat_produk }}
                @elseif ($history->tujuan === 'PR12PK')
                    <span>Stok Produk Jadi</span>
                @elseif ($history->tujuan === 'PR13GP')
                    <span>Steaming</span>
                @endif
            </td> --}}
        </tr>
    @endforeach
@stop