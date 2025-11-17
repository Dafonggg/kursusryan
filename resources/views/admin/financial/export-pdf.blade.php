<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #333;
        }
        
        .header h1 {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .header p {
            font-size: 10px;
            color: #666;
        }
        
        .summary {
            margin-bottom: 15px;
            padding: 10px;
            background-color: #f5f5f5;
            border-radius: 5px;
        }
        
        .summary-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        
        .summary-label {
            display: table-cell;
            width: 40%;
            font-weight: bold;
        }
        
        .summary-value {
            display: table-cell;
            width: 60%;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        
        thead {
            background-color: #333;
            color: #fff;
        }
        
        th {
            padding: 8px 5px;
            text-align: left;
            font-size: 9px;
            font-weight: bold;
            border: 1px solid #ddd;
        }
        
        td {
            padding: 6px 5px;
            font-size: 8px;
            border: 1px solid #ddd;
        }
        
        tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        tbody tr:hover {
            background-color: #f5f5f5;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
        }
        
        .badge-paid {
            background-color: #28a745;
            color: #fff;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-failed {
            background-color: #dc3545;
            color: #fff;
        }
        
        .badge-refunded {
            background-color: #6c757d;
            color: #fff;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Dibuat pada: {{ $generated_at }}</p>
    </div>
    
    <div class="summary">
        <div class="summary-row">
            <div class="summary-label">Total Pendapatan (Lunas):</div>
            <div class="summary-value">Rp {{ number_format($total_income, 0, ',', '.') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Total Pending:</div>
            <div class="summary-value">Rp {{ number_format($total_pending, 0, ',', '.') }}</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Jumlah Pembayaran Lunas:</div>
            <div class="summary-value">{{ $total_paid }} transaksi</div>
        </div>
        <div class="summary-row">
            <div class="summary-label">Jumlah Pembayaran Pending:</div>
            <div class="summary-value">{{ $total_pending_count }} transaksi</div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th style="width: 4%;">ID</th>
                <th style="width: 10%;">Tanggal</th>
                <th style="width: 15%;">Nama Siswa</th>
                <th style="width: 15%;">Email</th>
                <th style="width: 15%;">Kursus</th>
                <th style="width: 10%;" class="text-right">Jumlah</th>
                <th style="width: 8%;">Metode</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 10%;">Tanggal Bayar</th>
                <th style="width: 5%;">Ref</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payments as $payment)
            <tr>
                <td>{{ $payment->id }}</td>
                <td>{{ $payment->created_at->format('d/m/Y') }}</td>
                <td>{{ $payment->enrollment->user->name ?? '-' }}</td>
                <td>{{ $payment->enrollment->user->email ?? '-' }}</td>
                <td>{{ $payment->enrollment->course->title ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                <td>{{ ucfirst($payment->method->value ?? '-') }}</td>
                <td class="text-center">
                    @php
                        $status = strtolower($payment->status->value ?? '');
                        $badgeClass = 'badge-paid';
                        if ($status === 'pending') $badgeClass = 'badge-pending';
                        elseif ($status === 'failed') $badgeClass = 'badge-failed';
                        elseif ($status === 'refunded') $badgeClass = 'badge-refunded';
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ ucfirst($status) }}</span>
                </td>
                <td>{{ $payment->paid_at ? $payment->paid_at->format('d/m/Y') : '-' }}</td>
                <td>{{ $payment->reference ?? '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data pembayaran</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ $generated_at }}</p>
        <p>Total: {{ $payments->count() }} transaksi</p>
    </div>
</body>
</html>

