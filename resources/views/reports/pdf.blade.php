<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan Aqlaya Cake</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 20px;
            margin-bottom: 5px;
            color: #1a1a1a;
        }
        .header p {
            font-size: 10px;
            color: #666;
        }
        .period {
            text-align: center;
            margin-bottom: 20px;
            font-size: 12px;
            font-weight: bold;
            color: #444;
        }
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 25px;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            padding: 12px;
            background: #f5f5f5;
            border: 1px solid #ddd;
            text-align: center;
        }
        .summary-item .label {
            font-size: 9px;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .summary-item .value {
            font-size: 16px;
            font-weight: bold;
            color: #1a1a1a;
        }
        .section-title {
            font-size: 13px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
            color: #1a1a1a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table thead {
            background: #f5f5f5;
        }
        table th {
            padding: 8px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            border: 1px solid #ddd;
            color: #333;
        }
        table td {
            padding: 7px 8px;
            border: 1px solid #ddd;
            font-size: 10px;
        }
        table tbody tr:nth-child(even) {
            background: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9px;
            color: #666;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>AQLAYA CAKE</h1>
        <p>Laporan Transaksi Penjualan</p>
    </div>

    <div class="period">
        Periode: {{ \Carbon\Carbon::parse($startDate)->translatedFormat('d F Y') }} - {{ \Carbon\Carbon::parse($endDate)->translatedFormat('d F Y') }}
    </div>

    <div class="summary">
        <div class="summary-item">
            <div class="label">Total Transaksi</div>
            <div class="value">{{ $summary['orders'] }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Total Pendapatan</div>
            <div class="value">Rp{{ number_format($summary['revenue'], 0, ',', '.') }}</div>
        </div>
        <div class="summary-item">
            <div class="label">Rata-rata per Transaksi</div>
            <div class="value">Rp{{ number_format($summary['average'], 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="section-title">Produk Terlaris</div>
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 50%;">Nama Produk</th>
                <th style="width: 20%;" class="text-center">Jumlah Terjual</th>
                <th style="width: 25%;" class="text-right">Total Penjualan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $index => $product)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $product->product_name }}</td>
                    <td class="text-center">{{ $product->total_qty }}</td>
                    <td class="text-right">Rp{{ number_format($product->total_sales, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada data produk</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="section-title">Pendapatan Harian</div>
    <table>
        <thead>
            <tr>
                <th style="width: 10%;">No</th>
                <th style="width: 50%;">Tanggal</th>
                <th style="width: 40%;" class="text-right">Pendapatan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($dailyRevenue as $index => $daily)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($daily->paid_date)->translatedFormat('d F Y') }}</td>
                    <td class="text-right">Rp{{ number_format($daily->total_revenue, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Tidak ada data pendapatan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if(isset($orders) && $orders->count() > 0)
        <div class="page-break"></div>
        
        <div class="section-title">Detail Transaksi</div>
        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Kode Order</th>
                    <th style="width: 25%;">Customer</th>
                    <th style="width: 20%;">Tanggal</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%;" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $index => $order)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>{{ $order->order_code }}</td>
                        <td>{{ $order->customer_name }}</td>
                        <td>{{ $order->paid_at->translatedFormat('d/m/Y H:i') }}</td>
                        <td>{{ $order->statusLabel() }}</td>
                        <td class="text-right">Rp{{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->translatedFormat('d F Y, H:i') }} WIB</p>
        <p>© {{ date('Y') }} Aqlaya Cake - Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
</html>
