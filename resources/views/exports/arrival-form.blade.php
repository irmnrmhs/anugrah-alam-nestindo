<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Kedatangan Bahan Baku</title>
    <style>
        @page {
            size: A4;
            margin: 20px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        /* HEADER */
        .header-table {
            border: 1px solid #000;
        }

        .header-table td {
            border: 1px solid #000;
            vertical-align: middle;
            padding: 6px;
        }

        .logo {
            text-align: center;
        }

        .title {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
        }

        .subtitle {
            text-align: center;
            font-style: italic;
            font-size: 12px;
        }

        .doc-table {
            width: 100%;
            border-collapse: collapse;
        }

        .doc-table tr {
            border-bottom: 1px solid #000;
        }

        .doc-table tr:last-child {
            border-bottom: none; /* baris terakhir tidak double */
        }

        .doc-table td {
            padding: 3px;
            font-size: 11px;
            vertical-align: top;
        }

        /* CONTENT */
        .info-table td {
            padding: 4px;
        }

        .main-table th,
        .main-table td {
            border: 1px solid #000;
            padding: 5px;
            text-align: center;
        }

        .text-left {
            text-align: left;
        }
    </style>
</head>
<body>

{{-- ================= HEADER ================= --}}
<table class="header-table">
    <tr>
        <!-- LOGO -->
        <td width="15%" class="logo">
            {{-- <img src="{{ public_path('logo.png') }}" width="80"> --}}
        </td>

        <!-- TITLE -->
        <td width="55%">
            <div class="title">FORM KEDATANGAN BAHAN BAKU</div>
            <div class="subtitle">(RAW MATERIALS ARRIVAL FORM)</div>
        </td>

        <!-- DOCUMENT INFO -->
        <td width="30%">
            <table class="doc-table">
                <tr>
                    <td><strong>No. Dokumen</strong><br><i>(Document No.)</i></td>
                    <td>: AAN/FRM/RM/01/02</td>
                </tr>
                <tr>
                    <td><strong>Rev</strong><br><i>(Revision No.)</i></td>
                    <td>: 01</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong><br><i>(Date)</i></td>
                    <td>: {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->format('d F Y') }}</td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

{{-- ================= INFO ================= --}}
<table class="info-table">
    <tr>
        <td width="10%">Bulan</td>
        <td width="30%">
            : {{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->translatedFormat('F') }}
        </td>
        <td width="10%">PIC</td>
        <td width="50%">: {{ $arrival->employee->nama }}</td>
    </tr>
</table>

<br>

{{-- ================= TABEL UTAMA ================= --}}
<table class="main-table">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Kedatangan</th>
            <th>Nama RBW / No. Reg</th>
            <th>Kode Bahan Baku</th>
            <th colspan="2">Jumlah</th>
            <th>Keterangan</th>
            <th>Paraf PIC</th>
        </tr>
        <tr>
            <th colspan="4"></th>
            <th>Biji</th>
            <th>Gram</th>
            <th colspan="2"></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1</td>
            <td>{{ \Carbon\Carbon::parse($arrival->tgl_kedatangan)->format('d-m-Y') }}</td>
            <td class="text-left">
                {{ $arrival->dcertificate->wbhouse->nama ?? '-' }}
            </td>
            <td>{{ $arrival->kode }}</td>
            <td>-</td>
            <td>-</td>
            <td class="text-left">
                {{ $arrival->kondisi }}<br>
                {{ $arrival->keterangan }}
            </td>
            <td></td>
        </tr>

        @for($i = 0; $i < 5; $i++)
        <tr>
            <td>&nbsp;</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endfor
    </tbody>
</table>

</body>
</html>
