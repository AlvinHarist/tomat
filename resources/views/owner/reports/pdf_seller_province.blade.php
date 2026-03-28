<!DOCTYPE html>
<html>
<head>
    <title>Laporan Lokasi Toko</title>
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

    @if($data->isEmpty())
        <div style="text-align: center; margin-top: 50px; color: #666;">
            <h3>Tidak ada laporan dalam periode tanggal ini.</h3>
        </div>
    @else
        <table>
            <thead>
                <tr>
                    <th style="width: 5%">No</th>
                    <th>Nama Toko</th>
                    <th>Nama PIC</th>
                    <th>Provinsi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->store_name }}</td>
                    <td>{{ $item->user->name ?? '-' }}</td>
                    <td>{{ $item->pic_province }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>
</html>