<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Stok Bahan Baku Keluar</title>
    <style>
        @page {
            size: A4;
            margin: 20px;
        }

        body {
            font-family: "Times New Roman", serif;
            font-size: 11px;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
        }

        .subtitle {
            font-size: 11px;
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th,
        table td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: middle;
        }

        table th {
            background-color: #f0f0f0;
        }

        .footer {
            margin-top: 40px;
            width: 100%;
        }

        .ttd {
            width: 30%;
            text-align: center;
            float: right;
        }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="text-center">
        <div class="title">FORM STOK BAHAN BAKU KELUAR</div>
        <div class="subtitle">
            Dicetak pada: {{ now()->format('d-m-Y') }}
        </div>
    </div>

    {{-- TABEL DATA --}}
    <table>
        <thead>
            <tr class="text-center">
                <th width="5%">No</th>
                <th>Kode Bahan Baku</th>
                <th>Nama Petugas</th>
                <th>Tanggal Keluar</th>
                <th>Biji Keluar</th>
                <th>Berat Keluar</th>
                <th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($stocks as $index => $stock)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $stock->rawMaterial->kode }}</td>
                    <td>{{ $stock->employee->nama }}</td>
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($stock->tgl_keluar)->format('d-m-Y') }}
                    </td>
                    <td class="text-right">{{ number_format($stock->biji_keluar) }}</td>
                    <td class="text-right">{{ number_format($stock->berat_keluar, 2) }}</td>
                    <td>{{ $stock->keterangan ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- FOOTER TANDA TANGAN --}}
    <div class="footer">
        <div class="ttd">
            <p>Mengetahui,</p>
            <br><br><br>
            <p><strong>(___________________)</strong></p>
            <p>Penanggung Jawab</p>
        </div>
    </div>

</body>
</html>
