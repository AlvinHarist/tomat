<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Seller - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        .table-container { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { text-align: left; padding: 15px; color: #888; font-size: 0.85rem; border-bottom: 1px solid #eee; }
        td { padding: 15px; color: #555; font-size: 0.9rem; border-bottom: 1px solid #f9f9f9; vertical-align: middle; }
        
        /* Status Badges */
        .badge { padding: 6px 12px; border-radius: 20px; font-size: 0.75rem; font-weight: bold; text-transform: uppercase; }
        .badge-pending { background-color: #fff3e0; color: #ef6c00; } /* Orange */
        .badge-active { background-color: #e8f5e9; color: #2e7d32; }   /* Hijau */
        .badge-rejected { background-color: #ffebee; color: #c62828; } /* Merah */

        .btn-detail { background-color: #f2f2f2; color: #333; padding: 8px 15px; border-radius: 6px; text-decoration: none; font-size: 0.85rem; transition: 0.3s; }
        .btn-detail:hover { background-color: #e0e0e0; }
    </style>
</head>
<body>

    <aside class="sidebar">
        <div class="user-profile">
            <div class="user-info">
                <div class="user-name">{{ Auth::guard('owner')->user()->name ?? 'Owner' }}</div>
                <div class="user-role">Owner</div>
            </div>
            <div class="avatar"></div>
        </div>

        <div class="menu-title">MENU</div>
        <nav class="nav-links">
            <a href="{{ route('owner.dashboard') }}"><i class="fas fa-home"></i> Dashboard</a>
            <a href="{{ route('owner.sellers.index') }}" class="active"><i class="fas fa-store"></i> Seller</a>
            <a href="#"><i class="fas fa-box-open"></i> Categories</a>
            <a href="#"><i class="fas fa-cubes"></i> Products</a>
            <a href="#"><i class="fas fa-cog"></i> Reports</a>
        </nav>
        <div class="logo">ToMaT</div>
    </aside>

    <main class="main-content">
        <h1 class="page-title">Manajemen Penjual</h1>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Nama Toko</th>
                        <th>Nama PIC</th>
                        <th>Email</th>
                        <th>Tanggal Daftar</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sellers as $seller)
                    <tr>
                        <td style="font-weight: bold;">{{ $seller->store_name }}</td>
                        <td>{{ $seller->pic_name }}</td>
                        <td>{{ $seller->pic_email }}</td>
                        <td>{{ $seller->created_at->format('d M Y') }}</td>
                        <td>
                            @if($seller->status == 'PENDING')
                                <span class="badge badge-pending">Pending</span>
                            @elseif($seller->status == 'ACTIVE')
                                <span class="badge badge-active">Active</span>
                            @else
                                <span class="badge badge-rejected">Rejected</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('owner.sellers.show', $seller->id) }}" class="btn-detail">
                                <i class="fas fa-eye"></i> Detail & Verifikasi
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>