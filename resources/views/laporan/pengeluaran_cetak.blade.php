<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rekapan Pengeluaran Bulan {{ ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan - 1] }} {{ $tahun }}</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            color: #000;
            line-height: 1.5;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }
        .header h1 {
            font-size: 16pt;
            margin: 0 0 5px 0;
            text-transform: uppercase;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        th, td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .signature-container {
            width: 100%;
            margin-top: 50px;
        }
        .signature-box {
            float: right;
            width: 300px;
            text-align: center;
        }
        .signature-box p {
            margin: 0 0 5px 0;
        }
        .signature-space {
            height: 80px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    @php
        $rupiah = fn ($nilai) => 'Rp ' . number_format((float) $nilai, 0, ',', '.');
        $namaBulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][$bulan - 1];
        $namaBulanSekarang = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'][now()->format('n') - 1];
        $tanggalSekarang = now()->format('d') . ' ' . $namaBulanSekarang . ' ' . now()->format('Y');
    @endphp

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; background: #0ea5e9; color: white; border: none; border-radius: 5px;">Cetak Dokumen</button>
        <button onclick="window.close()" style="padding: 10px 20px; font-size: 14px; cursor: pointer; background: #f3f4f6; color: #374151; border: 1px solid #d1d5db; border-radius: 5px; margin-left: 10px;">Tutup</button>
    </div>

    <div class="header">
        <h1>Rekapan Pengeluaran Bulan {{ $namaBulan }} {{ $tahun }}</h1>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="15%">Tanggal</th>
                <th width="15%">Jenis</th>
                <th width="20%">Nama Pengeluaran</th>
                <th width="20%">Penerima</th>
                <th width="25%" class="text-right">Jumlah</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($pengeluaran as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal->format('d M Y') }}</td>
                    <td>{{ $item->jenis }}</td>
                    <td>
                        {{ $item->nama_kegiatan ?: '-' }}
                        @if($item->keterangan)
                            <br><small style="color: #666;">({{ $item->keterangan }})</small>
                        @endif
                    </td>
                    <td>{{ $item->penerima ?: '-' }}</td>
                    <td class="text-right">{{ $rupiah($item->jumlah) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Belum ada data pengeluaran.</td>
                </tr>
            @endforelse
        </tbody>
        @if ($pengeluaran->isNotEmpty())
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">TOTAL KESELURUHAN</th>
                    <th class="text-right">{{ $rupiah($total) }}</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="signature-container">
        <div class="signature-box">
            <p>Surabaya, {{ $tanggalSekarang }}</p>
            <p>Mengetahui,</p>
            <p>Kepala Departemen</p>
            <div class="signature-space"></div>
            <p class="signature-name">( ......................................... )</p>
        </div>
        <div style="clear: both;"></div>
    </div>

    <script>
        // Otomatis muncul dialog print saat halaman dibuka
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
