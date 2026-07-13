<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Konfirmasi Pembayaran Baru</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="margin-bottom: 8px;">Konfirmasi Pembayaran Baru Masuk</h2>
    <p>User telah mengirim konfirmasi pembayaran baru.</p>

    <p><strong>Email user:</strong> {{ $payment->customer_email }}</p>
    <p><strong>Total:</strong> Rp {{ number_format((float) $payment->total_amount, 0, ',', '.') }}</p>
    <p><strong>Status:</strong> {{ strtoupper($payment->status) }}</p>

    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; margin: 16px 0; border-color: #e5e7eb;">
        <thead style="background: #f3f4f6;">
            <tr>
                <th align="left">Item</th>
                <th align="left">Tipe</th>
                <th align="right">Qty</th>
                <th align="right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach (($payment->items ?? []) as $item)
                @php
                    $qty = (int) ($item['quantity'] ?? 1);
                    $price = (float) ($item['price'] ?? 0);
                @endphp
                <tr>
                    <td>{{ $item['name'] ?? '-' }}</td>
                    <td>{{ ucfirst($item['type'] ?? 'item') }}</td>
                    <td align="right">{{ $qty }}</td>
                    <td align="right">Rp {{ number_format($qty * $price, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>
        <a href="{{ asset('storage/' . $payment->payment_proof_path) }}" target="_blank" rel="noopener noreferrer">Lihat bukti pembayaran</a>
    </p>
    <p>
        <a href="{{ route('admin.payments.index') }}" target="_blank" rel="noopener noreferrer">Buka halaman admin payments</a>
    </p>
</body>
</html>
