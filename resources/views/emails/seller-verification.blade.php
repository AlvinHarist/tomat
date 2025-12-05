<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Toko ToMaT</title>
    <style>
        /* Reset CSS dasar untuk kompabilitas email client */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Gaya Tombol Hover (hanya bekerja di client modern) */
        .button-link:hover { background-color: #45a049 !important; }
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0; padding: 0;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    
                    <tr>
                        <td align="center" style="background-color: #21BD38; padding: 30px 0;">
                            <h1 style="color: #ffffff; font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; margin: 0;">ToMaT</h1>
                            <p style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; margin: 5px 0 0;">Toko Online Terpercaya</p>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding: 40px 30px; font-family: Arial, sans-serif; color: #333333;">
                            <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px;">Halo, {{ $sellerName }}! 👋</h2>
                            
                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 20px;">
                                Selamat! Toko <strong>{{ $storeName }}</strong> Anda telah diverifikasi oleh admin <strong>ToMaT</strong>. 
                            </p>
                            
                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 20px;">
                                Langkah terakhir untuk mulai berjualan adalah memverifikasi alamat email Anda dengan mengklik tombol di bawah ini:
                            </p>

                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $verificationUrl }}" class="button-link" style="background-color: #21BD38; color: #ffffff; display: inline-block; font-size: 16px; font-weight: bold; padding: 15px 30px; text-decoration: none; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">Verifikasi Toko Saya</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; line-height: 22px; margin: 30px 0 0; color: #666;">
                                Setelah verifikasi, Anda dapat login dan mulai mengelola produk toko Anda.
                            </p>

                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="padding: 0 30px 30px; font-family: Arial, sans-serif;">
                            <p style="font-size: 12px; color: #999999; line-height: 18px; margin: 0;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                                <a href="{{ $verificationUrl }}" style="color: #21BD38; word-break: break-all;">{{ $verificationUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="background-color: #f9f9f9; padding: 20px; border-top: 1px solid #eeeeee;">
                            <p style="font-family: Arial, sans-serif; font-size: 12px; color: #999999; margin: 0;">
                                &copy; 2025 ToMaT Inc. Semua Hak Dilindungi.<br>
                                Jalan Teknologi No. 123, Jakarta, Indonesia
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
</html>
