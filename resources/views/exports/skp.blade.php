<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            margin: 20px 30px;
        }

        .company {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
        }

        .company-desc {
            text-align: center;
            font-size: 11px;
            margin-bottom: 10px;
        }

        .box-right {
            position: absolute;
            right: 30px;
            top: 25px;
            border: 1px solid #000;
            padding: 5px 10px;
            font-size: 11px;
        }

        .title {
            text-align: center;
            margin-top: 10px;
            font-weight: bold;
            font-size: 15px;
        }

        .subtitle {
            text-align: center;
            font-size: 11px;
            font-style: italic;
            margin-bottom: 20px;
        }

        table.info {
            width: 100%;
        }

        table.info td {
            padding: 6px 0;
            vertical-align: top;
        }

        .label {
            width: 220px;
            font-weight: bold;
        }

        .en {
            font-size: 10px;
            font-style: italic;
            color: #444;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table.data-table th, table.data-table td {
            border: 1px solid black;
            padding: 5px;
            text-align: center;
        }

        .footer-sign {
            margin-top: 40px;
            width: 100%;
            text-align: right;
        }

        .signature {
            margin-top: 50px;
        }
    </style>

</head>

<body>

    <div class="box-right">
        {{ $document->no }} <br>
        Rev-{{ $document->rev_formatted }}
    </div>

    <p class="company">{{ $dcertificate->company->nama }}</p>
    <p class="company-desc">
        {{ $dcertificate->company->alamat }}
    </p>

    <p class="title">SURAT KETERANGAN PENGIRIMAN</p>
    <p class="subtitle">Delivery Certificate</p>

    <!-- Informasi -->
    <table class="info">
        <tr>
            <td class="label">Nama/ No Registrasi Rumah Walet <br><span class="en">Name / Bird's House Registration Number</span></td>
            <td>: {{ $dcertificate->wbhouse->nama . '/' . $dcertificate->wbhouse->kode }}</td>
        </tr>

        <tr>
            <td class="label">Alamat Rumah Walet <br><span class="en">Bird’s House Address</span></td>
            <td>: {{ $dcertificate->wbhouse->alamat }}</td>
        </tr>

        <tr>
            <td class="label">Tujuan IKH <br><span class="en">IKH Destination Number</span></td>
            <td>: {{ $dcertificate->company->nama }}</td>
        </tr>

        <tr>
            <td class="label">Nomor Registrasi IKH <br><span class="en">IKH Registration Number</span></td>
            <td>: {{ $dcertificate->company->ikh }}</td>
        </tr>

        <tr>
            <td class="label">Alamat IKH <br><span class="en">IKH Address</span></td>
            <td>: {{ $dcertificate->company->alamat }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal, Bulan, Tahun <br><span class="en">Date, Month, Year</span></td>
            <td>: {{ $dcertificate->tgl_skp }}</td>
        </tr>

        <tr>
            <td class="label">Nomor SKP / KH-14 <br><span class="en">Delivery Certificate / KH-14</span></td>
            <td>: {{ $dcertificate->no_skp }}</td>
        </tr>
    </table>

    <!-- Data Tabel -->
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">No</th>
                <th rowspan="2">Tanggal Panen <br><span class="en">Date of Harvesting</span></th>
                <th rowspan="2">Berat Panen (kg) <br><span class="en">Weight of Harvesting (kg)</span></th>
                <th colspan="2">Pengiriman ke IKH <br><span class="en">Delivery to IKH</span></th>
            </tr>
            <tr>
                <th>Tanggal Kirim <br><span class="en">Date of Delivery</span></th>
                <th>Berat Kirim (kg) <br><span class="en">Weight of Delivery (kg)</span></th>
            </tr>
        </thead>

        <tbody>
            @foreach($dcertificate->details as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->tgl_panen }}</td>
                    <td>{{ number_format($row->berat_panen, 2) }}</td>
                    <td>{{ $row->tgl_kirim }}</td>
                    <td>{{ number_format($row->berat_kirim, 2) }}</td>
                </tr>
            @endforeach

            @for ($n = 0; $n < 5; $n++)
                <tr>
                    <td>&nbsp;</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endfor

            <tr>
                <td colspan="2" style="text-align: right; font-weight: bold;">TOTAL</td>
                <td>{{ number_format($dcertificate->details->sum('berat_panen'), 2) }}</td>
                <td style="text-align: right; font-weight: bold;">TOTAL</td>
                <td>{{ number_format($dcertificate->details->sum('berat_kirim'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer-sign">
        Pemilik/ Penanggungjawab Rumah Walet <br>
        <span class="en">Owner / Person in Charge of Bird’s House</span>

        <div class="signature"></div>

        ({{ '................................' }})
    </div>

</body>
</html>
