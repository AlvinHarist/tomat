<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pemberitahuan Penolakan Verifikasi - ToMaT</title>
    <style>
        /* Reset CSS dasar untuk kompabilitas email client */
        body, table, td, a { -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%; }
        table, td { mso-table-lspace: 0pt; mso-table-rspace: 0pt; }
        img { -ms-interpolation-mode: bicubic; }
        img { border: 0; height: auto; line-height: 100%; outline: none; text-decoration: none; }
        table { border-collapse: collapse !important; }
        body { height: 100% !important; margin: 0 !important; padding: 0 !important; width: 100% !important; }

        /* Gaya Tombol Hover */
        .button-link:hover { background-color: #e64a19 !important; }
    </style>
</head>
<body style="background-color: #f4f4f4; margin: 0; padding: 0;">

    <table border="0" cellpadding="0" cellspacing="0" width="100%">
        <tr>
            <td align="center" style="padding: 20px 10px;">
                
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td align="center" style="background-color: #e74c3c; padding: 30px 0;">
                            <h1 style="color: #ffffff; font-family: Arial, sans-serif; font-size: 28px; font-weight: bold; margin: 0;">ToMaT</h1>
                            <p style="color: #ffffff; font-family: Arial, sans-serif; font-size: 14px; margin: 5px 0 0;">Toko Online Terpercaya</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td align="left" style="padding: 40px 30px; font-family: Arial, sans-serif; color: #333333;">
                            <h2 style="font-size: 20px; font-weight: bold; margin: 0 0 20px; color: #e74c3c;">Pemberitahuan Verifikasi Toko</h2>
                            
                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 20px;">
                                Halo, <strong>{{ $sellerName }}</strong>
                            </p>
                            
                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 20px;">
                                Terima kasih telah mendaftar sebagai penjual di <strong>ToMaT</strong> dengan toko <strong>{{ $storeName }}</strong>.
                            </p>

                            <div style="background-color: #fff3cd; border-left: 4px solid #ffc107; padding: 15px; margin: 20px 0; border-radius: 4px;">
                                <p style="font-size: 16px; line-height: 24px; margin: 0; color: #856404;">
                                    <strong>⚠️ Mohon Maaf</strong><br>
                                    Setelah melakukan verifikasi, kami belum dapat menerima pendaftaran toko Anda saat ini.
                                </p>
                            </div>

                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 20px;">
                                <strong>Kemungkinan penyebab:</strong>
                            </p>
                            <ul style="font-size: 15px; line-height: 24px; color: #666; margin: 0 0 20px; padding-left: 20px;">
                                <li>Data yang diberikan tidak lengkap atau tidak sesuai</li>
                                <li>Foto KTP atau dokumen tidak jelas/tidak valid</li>
                                <li>Informasi toko tidak memenuhi persyaratan kami</li>
                                <li>Data tidak sesuai dengan ketentuan yang berlaku</li>
                            </ul>

                            <p style="font-size: 16px; line-height: 24px; margin: 0 0 30px;">
                                <strong>Anda dapat melakukan registrasi ulang</strong> dengan memastikan semua data dan dokumen yang Anda berikan sudah lengkap dan sesuai dengan ketentuan kami.
                            </p>

                            <!-- Button -->
                            <table border="0" cellspacing="0" cellpadding="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $registrationUrl }}" class="button-link" style="background-color: #ff5722; color: #ffffff; display: inline-block; font-size: 16px; font-weight: bold; padding: 15px 30px; text-decoration: none; border-radius: 5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2);">Daftar Ulang Sekarang</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="font-size: 14px; line-height: 22px; margin: 30px 0 0; color: #666;">
                                Jika Anda memiliki pertanyaan atau memerlukan klarifikasi lebih lanjut, silakan hubungi tim support kami di <a href="mailto:support@tomat.com" style="color: #e74c3c;">support@tomat.com</a>
                            </p>

                        </td>
                    </tr>

                    <!-- URL Fallback -->
                    <tr>
                        <td align="left" style="padding: 0 30px 30px; font-family: Arial, sans-serif;">
                            <p style="font-size: 12px; color: #999999; line-height: 18px; margin: 0;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:<br>
                                <a href="{{ $registrationUrl }}" style="color: #e74c3c; word-break: break-all;">{{ $registrationUrl }}</a>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
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
