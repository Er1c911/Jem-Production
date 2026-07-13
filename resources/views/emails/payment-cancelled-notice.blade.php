<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran Ditolak</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="margin-bottom: 8px;">Konfirmasi Pembayaran Anda Ditolak</h2>
    <p>Halo,</p>
    <p>Maaf, konfirmasi pembayaran Anda belum dapat kami terima.</p>

    <p><strong>Alasan admin:</strong></p>
    <p style="background: #fef2f2; border: 1px solid #fecaca; padding: 12px; border-radius: 8px;">
        {{ $cancelReason }}
    </p>

    <p><strong>Total pembayaran:</strong> Rp {{ number_format((float) $payment->total_amount, 0, ',', '.') }}</p>

    <p>Silakan lakukan pembayaran/konfirmasi ulang dengan data yang sesuai. Jika butuh bantuan, balas email ini atau hubungi admin JEM Production.</p>
</body>
</html>
