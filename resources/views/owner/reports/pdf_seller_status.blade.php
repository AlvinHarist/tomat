<!DOCTYPE html>
<html>
<head>
    <title>Laporan Status Penjual</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .meta { margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>{{ $meta['title'] }}</h2>
        <p style="margin: 0; font-size: 14px;">{{ $meta['period'] }}</p>
    </div>

    <div class="meta">
        <strong>Tanggal dibuat:</strong> {{ $meta['date'] }} <br>
        <strong>Oleh:</strong> {{ $meta['processor'] }}
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 5%">No</th>
                <th>Nama User (Email)</th>
                <th>Nama PIC</th>
                <th>Nama Toko</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->pic_email }}</td>
                <td>{{ $item->pic_name }}</td>
                <td>{{ $item->store_name }}</td>
                <td>
                    @if($item->status == 'ACTIVE') Aktif
                    @elseif($item->status == 'PENDING') Pending
                    @else Tidak Aktif (Ditolak)
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>