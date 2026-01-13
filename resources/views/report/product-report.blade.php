<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Product Report</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- optional styling --}}
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 16px;
        }
        .card {
            background: #fff;
            border-radius: 8px;
            padding: 16px;
            max-width: 480px;
            margin: auto;
            box-shadow: 0 2px 8px rgba(0,0,0,.1);
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
    <h2>📦 PRODUCT REPORT</h2>

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

</body>
</html>
