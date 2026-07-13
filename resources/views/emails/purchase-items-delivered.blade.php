<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Pembelian Disetujui</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="margin-bottom: 8px;">Pembayaran Anda Sudah Diterima</h2>
    <p>Halo,</p>
    <p>Pembayaran untuk pesanan berikut sudah disetujui oleh admin JEM Production.</p>

    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse; width: 100%; margin: 16px 0; border-color: #e5e7eb;">
        <thead style="background: #f3f4f6;">
            <tr>
                <th align="left">Item</th>
                <th align="left">Tipe</th>
                <th align="right">Qty</th>
                <th align="right">Harga</th>
                <th align="right">Subtotal</th>
                <th align="left">Link Item</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                @php
                    $qty = (int) ($item['quantity'] ?? 1);
                    $price = (float) ($item['price'] ?? 0);
                    $subtotal = $qty * $price;
                @endphp
                <tr>
                    <td>{{ $item['name'] ?? '-' }}</td>
                    <td>{{ ucfirst($item['type'] ?? 'item') }}</td>
                    <td align="right">{{ $qty }}</td>
                    <td align="right">Rp {{ number_format($price, 0, ',', '.') }}</td>
                    <td align="right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                    <td>
                        @if (!empty($item['delivery_link']))
                            <a href="{{ $item['delivery_link'] }}" target="_blank" rel="noopener noreferrer">Buka Link</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p><strong>Total:</strong> Rp {{ number_format($totalAmount, 0, ',', '.') }}</p>
    <p>Item digital Anda telah kami proses dan kirim ke email ini: <strong>{{ $customerEmail }}</strong>.</p>
    <p>Terima kasih sudah berbelanja di JEM Production.</p>
</body>
</html>
