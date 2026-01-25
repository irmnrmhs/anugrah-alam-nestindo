<html lang="eng">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <div class="header-table">
            <table>
                <tr>
                    <td rowspan="3" class="header-logo">
                        <img src="{{ asset('img/Logo.png') }}" alt="logo">
                    </td>
                    <td rowspan="3" class="title">
                        @yield('title') <br>
                        <span class="title-eng">(@yield('subtitle'))</span>
                    </td>
                    <td>No Dokumen</td>
                    <td>: @yield('no')</td>
                </tr>
                <tr>
                    <td>Revisi</td>
                    <td>: @yield('rev')</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>: @yield('date')</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>