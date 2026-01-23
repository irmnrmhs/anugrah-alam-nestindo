<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Pemeriksaan Kendaraan dan Bahan Baku</title>
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        .border td, .border th {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
        .no-border td {
            border: none;
            padding: 2px;
        }
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }
        .small {
            font-size: 11px;
        }
        .checkbox {
            display: inline-block;
            width: 14px;
            height: 14px;
            border: 1px solid #000;
            text-align: center;
            line-height: 14px;
            font-weight: bold;
        }
        .mt {
            margin-top: 10px;
        }
        .signature {
            height: 60px;
        }
    </style>
</head>
<body>

{{-- HEADER --}}
<table class="border">
    <tr>
        <td rowspan="3" width="20%" align="center">
            <strong>PT. AAN</strong>
        </td>
        <td rowspan="3" width="50%" class="title">
            FORM PEMERIKSAAN<br>
            KENDARAAN DAN BAHAN BAKU<br>
            <span class="small">(Checklist of Receiving Raw Material’s Vehicle)</span>
        </td>
        <td width="30%">
            No Dokumen : {{ $arrival->dcertificate->document->no ?? '-' }}
        </td>
    </tr>
    <tr>
        <td>
            Revisi : Rev-{{ str_pad($arrival->dcertificate->document->rev ?? 0, 2, '0', STR_PAD_LEFT) }}
        </td>
    </tr>
    <tr>
        <td>
            Tanggal : {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->translatedFormat('d F Y') }}
        </td>
    </tr>
</table>

{{-- 1. PEMERIKSAAN KENDARAAN --}}
<table class="no-border mt">
    <tr><td><strong>1. Pemeriksaan Kendaraan</strong></td></tr>
</table>

<table class="no-border">
    <tr>
        <td width="30%">Tanggal</td>
        <td>: {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->translatedFormat('d F Y') }}</td>
    </tr>
    <tr>
        <td>Nama Supir</td>
        <td>: {{ $arrival->employee->nama }}</td>
    </tr>
    <tr>
        <td>Merk Mobil</td>
        <td>: {{ $arrival->car->merk }}</td>
    </tr>
    <tr>
        <td>No. Mobil</td>
        <td>: {{ $arrival->car->plat }}</td>
    </tr>
</table>

<table class="no-border mt">
    <tr>
        <td width="40%">Kebersihan Mobil Sudah Bebas Dari :</td>
        <td>
            @php
                $kondisi = strtolower($arrival->kondisi);
            @endphp

            <span class="checkbox">{{ str_contains($kondisi, 'sampah') ? '✓' : '' }}</span> Sampah
            &nbsp;&nbsp;
            <span class="checkbox">{{ str_contains($kondisi, 'oli') ? '✓' : '' }}</span> Ceceran Oli
            &nbsp;&nbsp;
            <span class="checkbox">{{ str_contains($kondisi, 'benda tajam') ? '✓' : '' }}</span> Benda Tajam
        </td>
    </tr>
</table>

{{-- 2. PEMERIKSAAN BAHAN BAKU --}}
<table class="no-border mt">
    <tr><td><strong>2. Pemeriksaan Bahan Baku</strong></td></tr>
</table>

<table class="no-border">
    <tr>
        <td width="30%">Kode Bahan Baku</td>
        <td>: {{ $arrival->kode }}</td>
    </tr>
    <tr>
        <td>Berat (Gram)</td>
        <td>: {{ $arrival->rawMaterial->berat ?? '-' }}</td>
    </tr>
    <tr>
        <td>Nama RBW / No. Reg</td>
        <td>: {{ $arrival->dcertificate->wbhouse->nama ?? '-' }}</td>
    </tr>
    <tr>
        <td>Kadar Air (%)</td>
        <td>: {{ $arrival->rawMaterial->kadar_air ?? '-' }}</td>
    </tr>
</table>

<table class="no-border mt">
    <tr>
        <td width="40%">Surat Keterangan Pengiriman</td>
        <td>
            <span class="checkbox">✓</span> Yes
            &nbsp;&nbsp;
            <span class="checkbox"></span> No
        </td>
    </tr>
</table>

{{-- KETERANGAN --}}
<table class="no-border mt">
    <tr>
        <td>Keterangan :</td>
    </tr>
    <tr>
        <td>{{ $arrival->keterangan ?? '-' }}</td>
    </tr>
</table>

{{-- TANDA TANGAN --}}
<table class="no-border mt">
    <tr>
        <td width="50%" align="center">
            Supir<br><br><br>
            ( {{ $arrival->employee->nama }} )
        </td>
        <td width="50%" align="center">
            Kepala Gudang<br><br><br>
            {{-- ( {{ $arrival->dcertificate->wbhouse->kepala_gudang ?? '................' }} ) --}}
        </td>
    </tr>
</table>

</body>
</html>
