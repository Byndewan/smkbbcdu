<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f6f9fc;
            margin: 0;
            padding: 40px 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: #be123c;
            padding: 40px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 800;
        }

        .content {
            padding: 40px;
            color: #334155;
            line-height: 1.6;
        }

        .amount-box {
            background: #f8fafc;
            border: 1px dashed #cbd5e1;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }

        .amount {
            font-size: 32px;
            font-weight: 800;
            color: #0f172a;
            margin: 5px 0;
        }

        .details table {
            width: 100%;
            font-size: 14px;
        }

        .details td {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 12px;
            background: #f8fafc;
        }

        .btn {
            display: inline-block;
            background: #be123c;
            color: #fff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Pembayaran Berhasil! 🎉</h1>
        </div>
        <div class="content">
            <p>Halo <strong>{{ $transaction->student->name }}</strong>,</p>
            <p>Terima kasih! Pembayaran kamu telah kami terima dan diverifikasi oleh sistem.</p>

            <div class="amount-box">
                <span style="font-size: 12px; text-transform: uppercase; color: #64748b; font-weight: bold;">Total
                    Dibayar</span>
                <div class="amount">Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</div>
                <div style="color: #22c55e; font-weight: bold; font-size: 14px;">✅ LUNAS</div>
            </div>

            <div class="details">
                <table cellspacing="0">
                    <tr>
                        <td style="color: #64748b;">No. Referensi</td>
                        <td style="text-align: right; font-weight: bold;">{{ $transaction->trx_code }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tanggal</td>
                        <td style="text-align: right; font-weight: bold;">
                            {{ $transaction->updated_at->format('d M Y, H:i') }}</td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Metode</td>
                        <td style="text-align: right; font-weight: bold;">{{ strtoupper($transaction->payment_method) }}
                        </td>
                    </tr>
                    <tr>
                        <td style="color: #64748b;">Tagihan</td>
                        <td style="text-align: right; font-weight: bold;">{{ $transaction->bill->title }}</td>
                    </tr>
                </table>
            </div>

            <div style="text-align: center;">
                <a href="{{ route('student.bills.invoice', $transaction->du_bill_id) }}" class="btn">Lihat
                    Kwitansi</a>
            </div>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} SMK Budi Bakti Ciwidey. <br>
            Email otomatis, mohon tidak membalas.
        </div>
    </div>
</body>

</html>
