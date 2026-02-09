<table>
    <tr>
        <td colspan="5"><strong>SURAT KETERANGAN PENGIRIMAN</strong></td>
    </tr>
    <tr>
        <td colspan="5">{{ $dcertificate->company->nama }}</td>
    </tr>
</table>

<br>

<table border="1">
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal Panen</th>
            <th>Berat Panen (kg)</th>
            <th>Tanggal Kirim</th>
            <th>Berat Kirim (kg)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($dcertificate->details as $i => $row)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $row->tgl_panen }}</td>
            <td>{{ $row->berat_panen }}</td>
            <td>{{ $row->tgl_kirim }}</td>
            <td>{{ $row->berat_kirim }}</td>
        </tr>
        @endforeach

        <tr>
            <td colspan="2"><strong>TOTAL</strong></td>
            <td><strong>{{ $dcertificate->details->sum('berat_panen') }}</strong></td>
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ $dcertificate->details->sum('berat_kirim') }}</strong></td>
        </tr>
    </tbody>
</table>
