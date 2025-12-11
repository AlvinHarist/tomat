<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter {{ $reportTitle }} - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        body { 
            background-color: #f4f4f4; 
            display: flex; 
            height: 100vh; 
            overflow: hidden; 
        }
        /* Override style untuk tombol Cetak Keseluruhan */
        .btn-outline-green {
            background:#fff; 
            border:1px solid #4CAF50; /* Border hijau */
            color:#4CAF50; /* Teks hijau */
            padding:10px 14px; 
            border-radius:8px; 
            display:inline-block; 
            text-decoration:none; 
            font-weight:600; 
            transition: background-color 0.3s;
        }
        .btn-outline-green:hover {
            background: #e6f7e6; /* Hover hijau muda */
        }
        
        /* Overriding form input focus color (CSS Owner defaultnya mungkin biru/hijau, kita pastikan hijau) */
        .form-input:focus, .form-select:focus, .form-textarea:focus {
            border-color: #4CAF50;
            box-shadow: 0 0 0 1px #4CAF50; /* Memberi efek fokus hijau yang lembut */
        }
        
        /* Mengubah warna Alert/Catatan menjadi Skema Hijau Muda/Info */
        .alert-info-green {
            background: #e6f7e6; 
            border-left: 4px solid #4CAF50; 
            color: #065f46;
            padding: 12px 16px; 
            border-radius: 8px; 
            margin-bottom: 16px;
        }
        .alert-info-green p {
            margin: 0;
            font-size: 0.9rem;
        }
        .alert-info-green ul {
            list-style: disc;
            margin-left: 20px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
@include('seller.partials.sidebar')

<main class="main-content">
    <div class="page-container">

        <div class="form-header" style="justify-content: flex-start;">
            <a href="{{ route('seller.reports.index') }}" style="color: #999;">
                <i class="fas fa-arrow-left" style="font-size: 1.5rem;"></i>
            </a>
            <div>
                <h1 class="page-title" style="margin-bottom: 5px; font-size: 1.6rem; color: #444;">
                    Filter {{ $reportTitle }}
                </h1>
                <p class="text-muted" style="margin-top: 0;">Pilih rentang tanggal untuk mencetak laporan.</p>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger">
                <p><strong>Gagal!</strong> {{ session('error') }}</p>
            </div>
        @endif
        @if(session('warning'))
            <div class="alert" style="background:#fffbe5; border-left:4px solid #ffc107; color:#a16207;">
                <p><strong>Perhatian!</strong> {{ session('warning') }}</p>
            </div>
        @endif

        <div class="card">
            <form action="{{ $reportRoute }}" method="GET">
                <div class="card-body">
                    <div class="form-row two-col">
                        <div class="form-group">
                            <label for="start_date" class="form-label">Tanggal Mulai:</label>
                            <input type="date" name="start_date" id="start_date" required class="form-input">
                        </div>
                        <div class="form-group">
                            <label for="end_date" class="form-label">Tanggal Selesai:</label>
                            <input type="date" name="end_date" id="end_date" required class="form-input">
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="justify-content: flex-end;">
                    <a href="{{ $reportRoute }}"
                       class="btn-outline-green">
                       <i class="fas fa-file-alt" style="margin-right: 5px;"></i> Cetak Laporan (Keseluruhan)
                    </a>
                    <button type="submit"
                            class="btn btn-primary">
                        <i class="fas fa-filter" style="margin-right: 5px;"></i> Cetak Laporan (Filter Tanggal)
                    </button>
                </div>
            </form>
        </div>

        <div class="alert-info-green" style="margin-top: 20px;">
            <p style="font-weight: bold;">Catatan:</p>
            <ul>
                <li>Untuk mencetak laporan keseluruhan tanpa filter tanggal, klik tombol "Cetak Laporan (Keseluruhan)".</li>
                <li>Peringatan akan muncul jika pada rentang tanggal yang dipilih tidak ada produk yang diunggah.</li>
            </ul>
        </div>

    </div>
</main>
</body>
</html>