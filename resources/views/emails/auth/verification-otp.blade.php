<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode Verifikasi Email</title>
</head>
<body style="margin:0;background:#f8fafc;font-family:Arial,sans-serif;color:#0f172a">
    <div style="max-width:560px;margin:0 auto;padding:32px 16px">
        <div style="background:#ffffff;border:1px solid #e2e8f0;border-radius:12px;padding:28px">
            <h1 style="margin:0 0 8px;font-size:22px;color:#111827">Verifikasi Email</h1>
            <p style="margin:0 0 20px;color:#475569;font-size:14px;line-height:1.6">
                Halo {{ $user->name }}, gunakan kode OTP berikut untuk memverifikasi email akun Disporapar Anda.
            </p>

            <div style="background:#eef2ff;border:1px solid #c7d2fe;border-radius:10px;text-align:center;padding:20px;margin:20px 0">
                <p style="margin:0 0 8px;color:#64748b;font-size:12px;text-transform:uppercase;letter-spacing:.08em">Kode OTP</p>
                <p style="margin:0;font-size:34px;letter-spacing:.22em;font-weight:700;color:#4f46e5">{{ $otp }}</p>
            </div>

            <p style="margin:0;color:#475569;font-size:14px;line-height:1.6">
                Kode ini berlaku sampai {{ $expiresAt->format('d M Y H:i') }}. Abaikan email ini jika Anda tidak membuat akun.
            </p>
        </div>
    </div>
</body>
</html>
