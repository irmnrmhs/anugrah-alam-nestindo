<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />

    <style>
        body {
            font-family: "Bahnschrift SemiBold", sans-serif;
            font-size: 11px;
            margin: 20px 35px;
        }

        /* HEADER */
        .company {
            text-align: center;
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
        }

        .company-desc {
            text-align: center;
            font-size: 10px;
            margin-top: 2px;
            margin-bottom: 8px;
        }

        .box-right {
            position: absolute;
            right: 35px;
            top: 20px;
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 9px;
            line-height: 1.4;
            text-align: left;
        }

        /* TITLE */
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 13px;
            margin-top: 60px;
            text-decoration: underline;
        }

        .subtitle {
            text-align: center;
            font-size: 10px;
            font-style: italic;
            margin-bottom: 12px;
        }

        /* INFO TABLE */
        table.info {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
            margin-top: 25px;
        }

        table.info td {
            padding: 3px 0;
            vertical-align: top;
        }

        .label {
            width: 200px;
            font-weight: bold;
        }

        .colon {
            width: 10px;
        }

        .en {
            font-size: 9px;
            font-style: italic;
            color: #555;
        }

        /* DATA TABLE */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.data-table th,
        table.data-table td {
            border: 1px solid #000;
            padding: 2px;
            text-align: center;
            font-size: 10px;
        }

        table.data-table th {
            font-weight: bold;
        }

        table.data-table tbody td {
            height: 18px;
        }

        /* FOOTER */
        .footer-sign {
            margin-top: 35px;
            width: 100%;
            text-align: right;
            font-size: 10px;
        }

        .signature {
            margin-top: 45px;
        }
    </style>
</head>

<body>
    <table>
        <tr>
            <td width="15%">
                <img src="{{ public_path('img/Logo.png') }}" width="80">
            </td>
            <td width="55%">
                <div class="company">PT. {{ $dcertificate->company->nama }}</div>
                <div class="company-desc">{{ $dcertificate->company->alamat }}</div>
            </td>
            <td width="30%">
                <div class="box-right">
                    {{ $document->no }}<br>
                    Rev-{{ $document->rev_formatted }}
                </div>
            </td>
        </tr>
    </table>
    <div class="title">SURAT KETERANGAN PENGIRIMAN</div>
    <div class="subtitle">Delivery Certificate</div>

    <table class="info">
        <tr>
            <td class="label">Nama/ No Registrasi Rumah Walet<br><span class="en">Name/ Bird's House Registration Number</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->wbhouse->nama }} / {{ $dcertificate->wbhouse->kode }}</td>
        </tr>

        <tr>
            <td class="label">Alamat Rumah Walet<br><span class="en">Bird’s House Address</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->wbhouse->alamat }}</td>
        </tr>

        <tr>
            <td class="label">Tujuan IKH<br><span class="en">IKH Destination Number</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->company->nama }}</td>
        </tr>

        <tr>
            <td class="label">Nomor Registrasi IKH<br><span class="en">IKH Registration Number</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->company->ikh }}</td>
        </tr>

        <tr>
            <td class="label">Alamat IKH<br><span class="en">IKH Address</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->company->alamat }}</td>
        </tr>

        <tr>
            <td class="label">Tanggal, Bulan, Tahun<br><span class="en">Date, Month, Year</span></td>
            <td class="colon">:</td>
            <td>{{ \Carbon\Carbon::parse($dcertificate->tgl_skp)->translatedFormat('d F Y') }}</td>
        </tr>

        <tr>
            <td class="label">Nomor SKP / KH-14<br><span class="en">Delivery Certificate / KH-14</span></td>
            <td class="colon">:</td>
            <td>{{ $dcertificate->no_skp }}</td>
        </tr>
    </table>

    <!-- DATA TABLE -->
    <table class="data-table">
        <thead>
            <tr>
                <th rowspan="2">No.</th>
                <th rowspan="2">Tanggal Panen<br><span class="en">Date of Harvesting</span></th>
                <th rowspan="2">Berat Panen (kg)<br><span class="en">Weight of Harvesting (kg)</span></th>
                <th colspan="2">Pengiriman ke IKH<br><span class="en">Delivery to IKH</span></th>
            </tr>
            <tr>
                <th>Tanggal Kirim<br><span class="en">Date of Delivery</span></th>
                <th>Berat Kirim (kg)<br><span class="en">Weight of Delivery (kg)</span></th>
            </tr>
        </thead>
        <tbody>
            @php
                $maxRows = 10;
                $dataCount = $dcertificate->details->count();
                $emptyRows = max(0, $maxRows - $dataCount);
            @endphp
            @foreach($dcertificate->details as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->tgl_panen }}</td>
                <td>{{ number_format($row->berat_panen, 2) }}</td>
                <td>{{ $row->tgl_kirim }}</td>
                <td>{{ number_format($row->berat_kirim, 2) }}</td>
            </tr>
            @endforeach

            @for ($i = 0; $i < $emptyRows; $i++)
            <tr>
                <td>&nbsp;</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @endfor

            <tr>
                <td colspan="2" style="text-align:right;font-weight:bold;">TOTAL</td>
                <td>{{ number_format($dcertificate->details->sum('berat_panen'), 2) }}</td>
                <td style="text-align:right;font-weight:bold;">TOTAL</td>
                <td>{{ number_format($dcertificate->details->sum('berat_kirim'), 2) }}</td>
            </tr>
        </tbody>
    </table>

    <!-- SIGN -->
    <div class="footer-sign">
        Pemilik / Penanggungjawab Rumah Walet<br>
        <span class="en">Owner / Person in Charge of Bird’s House</span>

        <div class="signature"></div>

        ({{ $dcertificate->wbhouse->owner ?? '.........................' }})
    </div>

</body>
</html>
