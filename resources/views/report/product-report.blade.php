<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Product Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- QRCode JS -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs@1.0.0/qrcode.min.js"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 16px;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 20px;
            max-width: 430px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 8px;
        }
        #qrcode {
            margin: 12px auto 16px;
            width: 140px;
            height: 140px;
            padding: 25px;
        }
        .row {
            margin-bottom: 8px;
        }
        .label {
            font-size: 12px;
            color: #6b7280;
        }
        .value {
            font-size: 16px;
            font-weight: bold;
        }
        
    </style>
</head>
<body>

<div class="card">
    <h2>PRODUCT IDENTIFIER</h2>

    <div id="qrcode"></div>

    <div class="row">
        <div class="label">Kode Identifier</div>
        <div class="value">{{ $identifier->kode }}</div>
    </div>

    <div class="row">
        <div class="label">Bahan Baku</div>
        <div class="value">{{ $identifier->rawMaterial->kode ?? '-' }}</div>
    </div>

    <div class="row">
        <div class="label">Grade</div>
        <div class="value">{{ $identifier->grade->grade ?? '-' }}</div>
    </div>

    <div class="row">
        <div class="label">Tanggal Grading</div>
        <div class="value">{{ $identifier->tanggal }}</div>
    </div>

    <div class="row">
        <div class="label">Stok Biji</div>
        <div class="value">{{ $identifier->biji }}</div>
    </div>

    <div class="row">
        <div class="label">Stok Berat</div>
        <div class="value">{{ $identifier->berat }} gr</div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        new QRCode(document.getElementById("qrcode"), {
            text: "{{ $identifier->kode }}",
            width: 140,
            height: 140,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });
</script>

</body>
</html>
