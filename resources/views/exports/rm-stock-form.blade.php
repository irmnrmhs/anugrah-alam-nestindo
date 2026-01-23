<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Stock Bahan Baku</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11px;
        }

        .title {
            text-align: center;
            font-weight: bold;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        th {
            text-align: center;
            font-weight: bold;
        }

        .no-border td {
            border: none;
            padding: 2px 4px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .small {
            font-size: 10px;
        }

        .signature {
            height: 60px;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <table class="no-border">
        <tr>
            <td width="70%">
                <div class="title">FORM STOCK BAHAN BAKU</div>
                <div class="subtitle">(RAW MATERIALS STOCK FORM)</div>
            </td>
            <td width="30%">
                <table>
                    <tr>
                        <td>No. Dokumen</td>
                        <td>: AAN/FRM/RM/01/03</td>
                    </tr>
                    <tr>
                        <td>Rev</td>
                        <td>: 01</td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td>: {{ \Carbon\Carbon::parse($stocks->tgl_keluar)->format('d F Y') }}</td>
                    </tr>
                    <tr>
                        <td>Bagian</td>
                        <td>: Bahan Baku</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

    <br>

    {{-- INFO --}}
    <table class="no-border">
        <tr>
            <td width="50%">Bulan : {{ \Carbon\Carbon::parse($stocks->tgl_keluar)->translatedFormat('F') }}</td>
            <td width="50%">PIC : {{ $stocks->employee->nama }}</td>
        </tr>
    </table>

    <br>

    {{-- TABLE MAIN --}}
    <table>
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal Kedatangan</th>
                <th rowspan="2">Nama BRW / No. Reg</th>
                <th rowspan="2">Kode Bahan Baku</th>
                <th rowspan="2">Kadar Air (%)</th>
                <th colspan="2">Jumlah Barang Masuk</th>
                <th rowspan="2">Tanggal Keluar</th>
                <th colspan="2">Jumlah Barang Keluar</th>
                <th rowspan="2">Keterangan</th>
                <th rowspan="2">Paraf PIC</th>
            </tr>
            <tr>
                <th>Biji</th>
                <th>Gram</th>
                <th>Biji</th>
                <th>Gram</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-center">1</td>
                <td class="text-center">
                    {{ $stocks->rawMaterial->tgl_masuk ?? '-' }}
                </td>
                <td>
                    {{ $stocks->rawMaterial->nama ?? '-' }} /
                    {{ $stocks->rawMaterial->no_reg ?? '-' }}
                </td>
                <td class="text-center">
                    {{ $stocks->rawMaterial->kode }}
                </td>
                <td class="text-center">
                    {{ $stocks->rawMaterial->kadar_air ?? '-' }}%
                </td>
                <td class="text-right">
                    {{ $stocks->rawMaterial->biji_masuk ?? '-' }}
                </td>
                <td class="text-right">
                    {{ $stocks->rawMaterial->berat_masuk ?? '-' }}
                </td>
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
    </table>

    <br><br>

    {{-- SIGNATURE --}}
    <table class="no-border">
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
    </table>

</body>
</html>
