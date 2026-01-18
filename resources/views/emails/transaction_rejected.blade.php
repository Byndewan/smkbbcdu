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
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        }

        .header {
            background: #e11d48;
            padding: 30px;
            text-align: center;
        }

        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 20px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .content {
            padding: 40px;
            color: #334155;
            line-height: 1.6;
        }

        .alert-box {
            background: #fff1f2;
            border-left: 4px solid #e11d48;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
            color: #881337;
            font-size: 14px;
        }

        .details table {
            width: 100%;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .details td {
            padding: 8px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .btn {
            display: inline-block;
            background: #e11d48;
            color: #fff;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            margin-top: 20px;
            font-size: 14px;
        }

        .footer {
            text-align: center;
            padding: 20px;
            color: #94a3b8;
            font-size: 12px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            @if ($type == 'document')
                <h1>⚠️ Dokumen Perlu Revisi</h1>
            @else
                <h1>❌ Pembayaran Ditolak</h1>
            @endif
        </div>

        <div class="content">
            <p>Halo <strong>{{ $transaction->student->name }}</strong>,</p>

            <p>
                @if ($type == 'document')
                    Mohon maaf, verifikasi dokumen kamu untuk tagihan <strong>{{ $transaction->bill->title }}</strong>
                    belum dapat kami terima.
                @else
                    Mohon maaf, bukti pembayaran yang kamu kirim untuk tagihan
                    <strong>{{ $transaction->bill->title }}</strong> tidak dapat kami verifikasi.
                @endif
            </p>

            <div class="alert-box">
                <strong>Catatan Admin:</strong><br>
                {!! nl2br(e($note)) !!}
            </div>

            <p>Silakan login ke dashboard untuk memperbaiki data tersebut secepatnya.</p>

            <div style="text-align: center;">
                <a href="{{ route('student.bills.show', $transaction->du_bill_id) }}" class="btn">
                    @if ($type == 'document')
                        Perbaiki Dokumen Sekarang
                    @else
                        Upload Bukti Baru
                    @endif
                </a>
            </div>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} SMK Budi Bakti Ciwidey.<br>
            Email otomatis, mohon tidak membalas.
        </div>
    </div>
</body>

</html>
