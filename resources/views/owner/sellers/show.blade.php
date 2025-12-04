<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Penjual - ToMaT</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/owner/dashboard.css') }}">
    <style>
        .detail-container { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.02); display: flex; gap: 40px; }
        .left-info { flex: 1; }
        .right-docs { flex: 1; border-left: 1px solid #eee; padding-left: 40px; }
        
        h3 { margin-top: 0; color: #4CAF50; margin-bottom: 20px; font-size: 1.2rem; }
        .info-row { margin-bottom: 15px; }
        .info-label { font-size: 0.85rem; color: #888; display: block; margin-bottom: 5px; }
        .info-value { font-size: 1rem; color: #333; font-weight: 500; }

        .doc-preview { width: 100%; height: 200px; background-color: #f9f9f9; border-radius: 8px; display: flex; align-items: center; justify-content: center; border: 2px dashed #ddd; margin-bottom: 20px; overflow: hidden; cursor: pointer; transition: 0.3s; }
        .doc-preview:hover { border-color: #4CAF50; box-shadow: 0 4px 12px rgba(76, 175, 80, 0.2); }
        .doc-preview img { max-width: 100%; max-height: 100%; object-fit: contain; }

        /* Modal Styles */
        .modal { display: none; position: fixed; z-index: 9999; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.9); }
        .modal-content { margin: auto; display: block; max-width: 90%; max-height: 90%; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); animation: zoom 0.3s; }
        @keyframes zoom { from {transform: translate(-50%, -50%) scale(0.7);} to {transform: translate(-50%, -50%) scale(1);} }
        .close-modal { position: absolute; top: 20px; right: 40px; color: #fff; font-size: 40px; font-weight: bold; cursor: pointer; transition: 0.3s; }
        .close-modal:hover { color: #ccc; }
        .modal-caption { position: absolute; bottom: 20px; left: 50%; transform: translateX(-50%); color: #fff; font-size: 18px; background: rgba(0,0,0,0.7); padding: 10px 20px; border-radius: 5px; }

        .action-buttons { margin-top: 30px; display: flex; gap: 15px; }
        .btn { padding: 12px 25px; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; transition: 0.3s; text-decoration: none; color: white; display: inline-block; }
        .btn-approve { background-color: #4CAF50; }
        .btn-approve:hover { background-color: #45a049; }
        .btn-reject { background-color: #e74c3c; }
        .btn-reject:hover { background-color: #c0392b; }
        .btn-back { background-color: #f2f2f2; color: #333; }
        /* Tambahkan ini di dalam tag <style> */
        .text-active { color: #2e7d32; }   /* Hijau */
        .text-rejected { color: #c62828; } /* Merah */
        .text-pending { color: #ef6c00; }  /* Orange */
    </style>
</head>
<body>

    @include('owner.sidebar')

    <main class="main-content">
        <div style="display: flex; align-items: center; margin-bottom: 30px;">
            <a href="{{ route('owner.sellers.index') }}" class="btn-back" style="margin-right: 20px; padding: 10px 15px; border-radius: 50%;"><i class="fas fa-arrow-left"></i></a>
            <h1 class="page-title" style="margin: 0;">Verifikasi Penjual</h1>
        </div>

        <div class="detail-container">
            
            <div class="left-info">
                <h3>Informasi Toko & PIC</h3>
                
                <div class="info-row"><span class="info-label">Nama Toko</span><span class="info-value">{{ $seller->store_name }}</span></div>
                <div class="info-row"><span class="info-label">Deskripsi</span><span class="info-value">{{ $seller->store_description ?? '-' }}</span></div>
                <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
                <div class="info-row"><span class="info-label">Nama PIC</span><span class="info-value">{{ $seller->pic_name }}</span></div>
                <div class="info-row"><span class="info-label">Email</span><span class="info-value">{{ $seller->pic_email }}</span></div>
                <div class="info-row"><span class="info-label">No. Telepon</span><span class="info-value">{{ $seller->pic_phone }}</span></div>
                <div class="info-row"><span class="info-label">No. KTP</span><span class="info-value">{{ $seller->pic_ktp_number }}</span></div>
                <div class="info-row">
                    <span class="info-label">Alamat Lengkap</span>
                    <span class="info-value">
                        {{ $seller->pic_street }}, RT {{ $seller->pic_rt }}/RW {{ $seller->pic_rw }}<br>
                        Kel. {{ $seller->pic_village }}, {{ $seller->pic_city }}<br>
                        Prov. {{ $seller->pic_province }}
                    </span>
                </div>
                
                <div class="info-row">
                    <span class="info-label">Status Saat Ini</span>
                    <span class="info-value text-{{ strtolower($seller->status) }}" style="font-weight: bold;">
                        {{ $seller->status }}
                    </span>
                    </span>
                </div>
            </div>

            <div class="right-docs">
                <h3>Dokumen Pendukung</h3>
                
                <span class="info-label">Foto KTP</span>
                <div class="doc-preview" onclick="openModal('{{ asset( $seller->pic_ktp_file_path) }}', 'Foto KTP - {{ $seller->pic_name }}')">
                    @if($seller->pic_ktp_file_path)
                        <img src="{{ asset('storage/' . $seller->pic_ktp_file_path) }}" alt="Foto KTP">
                    @else
                        <span style="color: #ccc;">Tidak ada file</span>
                    @endif
                </div>

                <span class="info-label">Foto PIC</span>
                <div class="doc-preview" onclick="openModal('{{ asset( $seller->pic_photo_path) }}', 'Foto PIC - {{ $seller->pic_name }}')">
                    @if($seller->pic_photo_path)
                        <img src="{{ asset('storage/' . $seller->pic_photo_path) }}" alt="Foto PIC">
                    @else
                        <span style="color: #ccc;">Tidak ada file</span>
                    @endif
                </div>

                <div class="action-buttons">
                    <form action="{{ route('owner.sellers.updateStatus', $seller->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="ACTIVE">
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Yakin ingin mengaktifkan akun ini?')">
                            <i class="fas fa-check"></i> Terima (Aktifkan)
                        </button>
                    </form>

                    <form action="{{ route('owner.sellers.updateStatus', $seller->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="REJECTED">
                        <button type="submit" class="btn btn-reject" onclick="return confirm('Yakin ingin menolak akun ini?')">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </main>

    <!-- Modal for Image Preview -->
    <div id="imageModal" class="modal" onclick="closeModal()">
        <span class="close-modal">&times;</span>
        <img class="modal-content" id="modalImage">
        <div class="modal-caption" id="modalCaption"></div>
    </div>

    <script>
        function openModal(imageSrc, caption) {
            const modal = document.getElementById('imageModal');
            const modalImg = document.getElementById('modalImage');
            const modalCaption = document.getElementById('modalCaption');
            
            modal.style.display = 'block';
            modalImg.src = imageSrc;
            modalCaption.textContent = caption;
        }

        function closeModal() {
            document.getElementById('imageModal').style.display = 'none';
        }

        // Close modal with ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>