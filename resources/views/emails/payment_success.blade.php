<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            margin-top: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }

        .content {
            padding: 30px 20px;
            color: #333333;
            line-height: 1.6;
        }

        .table-info {
            width: 100%;
            margin-top: 20px;
            margin-bottom: 20px;
            border-collapse: collapse;
        }

        .table-info td {
            padding: 10px 0;
            border-bottom: 1px solid #eeeeee;
        }

        .table-info td:last-child {
            text-align: right;
            font-weight: bold;
        }

        .total-row td {
            border-top: 2px solid #333;
            border-bottom: none;
            font-size: 18px;
            color: #0d6efd;
            padding-top: 15px;
        }

        .btn {
            display: inline-block;
            background-color: #0d6efd;
            color: #ffffff;
            text-decoration: none;
            padding: 12px 25px;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 20px;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h1 style="margin:0; font-size: 24px;">Pembayaran Diterima! 🎉</h1>
            <p style="margin:5px 0 0; opacity: 0.9;">Terima kasih telah melakukan pembayaran.</p>
        </div>

        <div class="content">
            <p>Hai, <strong>{{ $transaction->student->name ?? 'Siswa' }}</strong> 👋</p>
            <p>Pembayaran kamu telah kami terima dan terverifikasi oleh sistem. Berikut adalah rincian transaksinya:</p>

            <table class="table-info">
                <tr>
                    <td>No. Invoice</td>
                    <td>#{{ $transaction->trx_code }}</td>
                </tr>
                <tr>
                    <td>Tanggal</td>
                    <td>{{ date('d M Y, H:i', strtotime($transaction->created_at)) }}</td>
                </tr>
                <tr>
                    <td>Metode Bayar</td>
                    <td>{{ strtoupper($transaction->payment_method ?? 'MANUAL') }}</td>
                </tr>
                <tr>
                    <td>Status</td>
                    <td style="color: green;">{{ strtoupper($transaction->status ?? '-') }}</td>
                </tr>

                <tr class="total-row">
                    <td>Total Bayar</td>
                    <td>Rp {{ number_format($transaction->total_amount, 0, ',', '.') }}</td>
                </tr>
            </table>

            <p style="font-size: 13px; color: #666;">
                *Mohon simpan email ini sebagai bukti pembayaran yang sah.
            </p>

            <div style="text-align: center;">
                <a href="{{ route('student.history') }}" class="btn">Lihat Riwayat</a>
            </div>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} BBC Pay System. All rights reserved.</p>
            <p>Sekolah BBC, Jl. Pendidikan No. 1, Kota Bandung</p>
        </div>
    </div>

</body>

</html>
