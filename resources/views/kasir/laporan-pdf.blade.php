<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan - Kashy</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            padding: 50px;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            background: white;
        }
        
        /* Kop Surat */
        .kop {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 15px;
        }
        .kop h1 {
            font-size: 24pt;
            font-weight: bold;
            margin-bottom: 5px;
            letter-spacing: 3px;
        }
        .kop h3 {
            font-size: 14pt;
            font-weight: normal;
            margin-bottom: 3px;
        }
        .kop p {
            font-size: 10pt;
        }
        
        /* Judul Laporan */
        .judul {
            text-align: center;
            margin-bottom: 20px;
        }
        .judul h2 {
            font-size: 16pt;
            font-weight: bold;
            text-decoration: underline;
            margin-bottom: 5px;
        }
        .judul p {
            font-size: 11pt;
        }
        
        /* Info Laporan */
        .info-laporan {
            width: 100%;
            margin-bottom: 25px;
            border-collapse: collapse;
        }
        .info-laporan td {
            padding: 4px 0;
            font-size: 11pt;
        }
        .info-laporan td:first-child {
            width: 130px;
            font-weight: bold;
        }
        
        /* Tabel Umum */
        .table-data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        .table-data th {
            background: #f0f0f0;
            border: 1px solid #000;
            padding: 8px 10px;
            font-size: 11pt;
            font-weight: bold;
            text-align: center;
        }
        .table-data td {
            border: 1px solid #000;
            padding: 6px 10px;
            font-size: 11pt;
        }
        .table-data .text-left {
            text-align: left;
        }
        .table-data .text-right {
            text-align: right;
        }
        .table-data .text-center {
            text-align: center;
        }
        
        /* Bagian Total */
        .total-box {
            margin-top: 20px;
            text-align: right;
        }
        .total-box table {
            width: 300px;
            margin-left: auto;
            border-collapse: collapse;
        }
        .total-box td {
            padding: 5px;
            font-size: 11pt;
        }
        .total-box .grandtotal {
            font-weight: bold;
            font-size: 12pt;
        }
        
        /* Footer */
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 9pt;
            border-top: 1px solid #ccc;
            padding-top: 15px;
        }
        
        /* Tanda Tangan */
        .ttd {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        .ttd-box {
            text-align: center;
            width: 200px;
        }
        .ttd-box p {
            margin-bottom: 60px;
        }
        
        hr {
            margin: 15px 0;
        }
    </style>
</head>
<body>

    <!-- KOP SURAT -->
    <div class="kop">
        <h1>KASHY</h1>
        <h3>Kashy App - Sistem Kasir Modern</h3>
        <p>Jl. Raya No. 123, Kota Contoh, Indonesia</p>
        <p>Email: info@kashy.com | Telp: (021) 1234-5678</p>
    </div>

    <!-- JUDUL LAPORAN -->
    <div class="judul">
        <h2>LAPORAN PENJUALAN HARIAN</h2>
        <p>Periode : {{ $date_formatted }}</p>
    </div>

    <!-- INFO LAPORAN -->
    <table class="info-laporan">
        <tr>
            <td>Kasir</td>
            <td>: {{ $user->name }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>: {{ $date_formatted }}</td>
        </tr>
        <tr>
            <td>Total Transaksi</td>
            <td>: {{ $total_transaksi }} transaksi</td>
        </tr>
        <tr>
            <td>Total Penjualan</td>
            <td>: <strong>Rp {{ number_format($total_penjualan, 0, ',', '.') }}</strong></td>
        </tr>
    </table>

    <!-- PENJUALAN PER KATEGORI -->
    <h3 style="margin-bottom: 10px; margin-top: 20px;">A. Penjualan per Kategori</h3>
    <table class="table-data">
        <thead>
            <tr>
                <th width="70%">Kategori</th>
                <th width="30%">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualan_per_kategori as $item)
            <tr>
                <td>{{ $item->category_name }}</td>
                <td class="text-right">Rp {{ number_format($item->total, 0, ',', '.') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="2" class="text-center">Belum ada data penjualan</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- PRODUK TERLARIS -->
    <h3 style="margin-bottom: 10px; margin-top: 20px;">B. Produk Terlaris</h3>
    <table class="table-data">
        <thead>
            <tr>
                <th width="10%">No</th>
                <th width="70%">Kategori</th>
                <th width="20%">Terjual</th>
            </tr>
        </thead>
        <tbody>
            @forelse($produk_terlaris as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $item->category_name }}</td>
                <td class="text-center">{{ $item->total_terjual }} pcs</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data produk terjual</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- METODE PEMBAYARAN -->
    <h3 style="margin-bottom: 10px; margin-top: 20px;">C. Metode Pembayaran</h3>
    <table class="table-data">
        <thead>
            <tr>
                <th width="40%">Metode</th>
                <th width="30%">Jumlah Transaksi</th>
                <th width="30%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @forelse($metode_pembayaran as $item)
            <tr>
                <td>{{ $item['name'] }}</td>
                <td class="text-center">{{ $item['total'] }}</td>
                <td class="text-center">{{ $item['percentage'] }}%</td>
            </tr>
            @empty
            <tr>
                <td colspan="3" class="text-center">Belum ada data pembayaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TANDA TANGAN -->
    <div class="ttd">
        <div class="ttd-box">
            <p>Mengetahui,<br>Pemilik Toko</p>
            <br><br><br>
            <p><u>(_________________)</u></p>
        </div>
        <div class="ttd-box">
            <p>Dicetak oleh,<br>Kasir</p>
            <br><br><br>
            <p><u>({{ $user->name }})</u></p>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}<br>
        Laporan ini adalah dokumen resmi dari Kashy App.
    </div>

</body>
</html>