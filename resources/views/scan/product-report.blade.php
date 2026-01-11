<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Scan Barcode</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #111827;
            color: white;
            text-align: center;
            padding: 16px;
        }
        #reader {
            width: 100%;
            max-width: 400px;
            margin: auto;
        }
        button {
            margin: 12px;
            padding: 10px 18px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>📷 Scan Barcode Produk</h2>

<button onclick="startScan()">Mulai Scan</button>

<div id="reader"></div>

<script>
    let scanner;

    function startScan() {
        if (scanner) return;

        scanner = new Html5QrcodeScanner(
            "reader",
            {
                fps: 10,
                qrbox: { width: 250, height: 120 },
                formatsToSupport: [
                    Html5QrcodeSupportedFormats.CODE_128,
                    Html5QrcodeSupportedFormats.QR_CODE
                ]
            },
            false
        );

        scanner.render(
            (decodedText) => {
                scanner.clear();
                window.location.href = `/scan/${decodedText}`;
            },
            (error) => {
                // ignore scan error
            }
        );
    }
</script>

</body>
</html>
