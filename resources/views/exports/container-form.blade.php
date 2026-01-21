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

        .header-table {
            border: 1px solid #000;
        }

        .header-table td {
            border: 1px solid #000;
            vertical-align: middle;
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
            border-bottom: none;
        }

        .doc-table td {
            padding: 3px;
            font-size: 11px;
            vertical-align: top;
        }

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

<table class="header-table">
    <tr>
        <td width="15%" class="logo">
            {{-- <img src="{{ public_path('logo.png') }}" width="80"> --}}
        </td>

        <td width="55%">
            <div class="title">FORM KEDATANGAN BAHAN BAKU</div>
            <div class="subtitle">(RAW MATERIALS ARRIVAL FORM)</div>
        </td>

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
                    <td>: {{ \Carbon\Carbon::parse($container->arrival->tgl_kedatangan)->format('d F Y') }}</td>
                </tr>
                <tr>
                    <td><strong>Bagian</strong><br><i>(Department)</i></td>
                    <td><strong>: Bahan Baku</strong><br><i>(Raw Material)</i></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<br>

<table class="info-table">
    <tr>
        <td width="10%">Bulan</td>
        <td width="30%">
            : {{ \Carbon\Carbon::parse($container->arrival->tgl_kedatangan)->translatedFormat('F') }}
        </td>
        <td width="10%">PIC</td>
        <td width="50%">: {{ $container->arrival->employee->nama }}</td>
    </tr>
</table>

<br>

<table class="main-table">
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
        @foreach ($container as $i => $row)
            <tr>
                <td>1</td>
                <td>{{ \Carbon\Carbon::parse($container->arrival->tgl_kedatangan)->format('d-m-Y') }}</td>
                <td class="text-left">
                    {{ $container->arrival->dcertificate->wbhouse->nama ?? '-' }}
                </td>
                <td>{{ $container->arrival->kode }}</td>
                <td>{{ empty($container->biji) ? 0 : $container->biji }}</td>
                <td>{{ empty($container->berat) ? 0 : $container->berat}}</td>
                <td>{{ empty($container->keterangan) ? '-' : $container->keterangan }}</td>
                <td>{{ $container->arrival->employee->nama }}</td>
            </tr>
        @endforeach

        {{-- @foreach ($container as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>1</td>
                <td>{{ \Carbon\Carbon::parse($row->arrival->tgl_kedatangan)->format('d-m-Y') }}</td>
                <td class="text-left">
                    {{ $row->arrival->dcertificate->wbhouse->nama ?? '-' }}
                </td>
                <td>{{ $row->arrival->kode }}</td>
                <td>{{ $row->biji }}</td>
                <td>{{ $row->berat }}</td>
                <td>{{ $row->keterangan }}</td>
                <td>{{ $row->arrival->employee->nama }}</td>
            </tr>
        @endforeach --}}

        {{-- @for($i = 0; $i < 5; $i++)
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
        @endfor --}}
    </tbody>
</table>

</body>
</html>
