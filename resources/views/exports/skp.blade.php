<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        td { padding: 4px; vertical-align: top; }
        .header { text-align: center; margin-bottom: 20px; }
        .table-bordered td { border: 1px solid #000; }
        .title { font-size: 18px; font-weight: bold; margin-bottom: 0; }
    </style>
</head>
<body>

    <div class="header">
        <p class="title">PT. ANUGRAH ALAM NESTINDO</p>
        <P>Kp Bolenglang RT 002 RW 013, Sukasari Cilaku, Kab. Cianjur, Jawa Barat</P>
        <p></p>

        <h2 style="text-decoration-line: underline">Surat Keterangan Pengiriman</h2>
        <h4 style="font-style: italic">Delivery Certificate</h4>
    </div>

    <table>
        <tr>
            <td width="30%">
                Nama/ No Registrasi Rumah Walet<br>
                Name/ Bird's House Registration Number
            </td>
            <td>: {{ $dcertificate->wbhouse->nama . "/" . $dcertificate->wbhouse->kode }}</td>
        </tr>
        <tr>
            <td>Alamat Rumah Walet</td>
            <td>: {{ $dcertificate->wbhouse->alamat }}</td>
        </tr>
        <tr>
            <td>Tujuan IKH</td>
            <td>: {{ $dcertificate->company->nama }}</td>
        </tr>
        <tr>
            <td>Nomor Registrasi IКН</td>
            <td>: {{ $dcertificate->company->ikh }}</td>
        </tr>
        <tr>
            <td>Alamat IKH</td>
            <td>: {{ $dcertificate->company->alamat }}</td>
        </tr>
        <tr>
            <td>Tanggal, Bulan, Tahun</td>
            <td>: {{ $dcertificate->tgl_skp }}</td>
        </tr>
        <tr>
            <td>Nomor SKP / KH-14</td>
            <td>: {{ $dcertificate->no_skp }}</td>
        </tr>
    </table>

    <br><br><br>

    <table>
        <tr>
            <td width="60%"></td>
            <td>
                Pemilik/ Penanggungjawab Rumah Walet,<br>
                Owner/ Person in Charge of Bird's House<br><br><br><br><br>
                   (.................)<br>
                ________________________
            </td>
        </tr>
    </table>

</body>
</html>