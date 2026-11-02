<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Laporan AHP - Synthesis Huis')</title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #1E293B;
            line-height: 1.5;
            margin: 0;
            padding: 20px 24px;
            font-size: 13px;
        }

        .header-table {
            width: 100%;
            border: none !important;
            border-collapse: collapse;
            background: transparent;
            margin-bottom: 0;
        }

        .header-table td {
            border: none !important;
            background: transparent;
        }

        .header-wrap {
            border-bottom: 3px solid #D4AF37;
            padding-bottom: 14px;
            margin-bottom: 24px;
        }

        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #1E293B;
            margin: 0;
            line-height: 1.2;
        }

        .company-name span {
            color: #D4AF37;
        }

        .doc-title {
            font-size: 15px;
            font-weight: bold;
            color: #0F172A;
            margin: 6px 0 0;
        }

        .doc-sub {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0 0;
        }

        .doc-address {
            font-size: 10px;
            font-weight: bold;
            color: #64748b;
            margin: 3px 0 0;
            line-height: 1.3;
        }

        .doc-date {
            font-size: 11px;
            font-weight: bold;
            color: #334155;
            margin-bottom: 6px;
        }

        .section-title {
            background-color: #0F172A;
            color: #D4AF37;
            font-size: 13px;
            font-weight: bold;
            padding: 8px 12px;
            margin-top: 22px;
            margin-bottom: 10px;
            border-radius: 4px;
            letter-spacing: 0.3px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            font-size: 12px;
        }

        th {
            background-color: #1E293B;
            color: #fff;
            padding: 9px 10px;
            text-align: left;
            font-weight: 600;
        }

        td {
            border: 1px solid #e2e8f0;
            padding: 8px 10px;
            vertical-align: middle;
        }

        tr:nth-child(even) td {
            background-color: #f8fafc;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .mono {
            font-family: 'Courier New', monospace;
        }

        .rank-1 {
            background: #D4AF37;
            color: #fff;
            font-weight: bold;
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 12px;
        }

        .rank-2 {
            background: #94a3b8;
            color: #fff;
            font-weight: bold;
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 12px;
        }

        .rank-3 {
            background: #b45309;
            color: #fff;
            font-weight: bold;
            padding: 3px 9px;
            border-radius: 12px;
            font-size: 12px;
        }

        .rank-n {
            color: #64748b;
            font-weight: 600;
            font-size: 12px;
        }

        .bar-bg {
            background: #e2e8f0;
            border-radius: 3px;
            height: 8px;
            width: 100px;
            display: inline-block;
            vertical-align: middle;
        }

        .bar-fill {
            background: #D4AF37;
            border-radius: 3px;
            height: 8px;
            display: inline-block;
        }

        .badge-kriteria {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-sub {
            background: #ede9fe;
            color: #7c3aed;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-has-sub {
            background: #dbeafe;
            color: #1d4ed8;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 10px;
            font-weight: 600;
        }

        .badge-no-sub {
            background: #f1f5f9;
            color: #64748b;
            padding: 1px 6px;
            border-radius: 8px;
            font-size: 10px;
        }

        .alt-header-row td {
            background: #1E293B !important;
            color: #D4AF37;
            font-weight: bold;
            font-size: 12px;
            padding: 9px 12px;
            border: none;
        }

        .alt-score-span {
            font-family: 'Courier New', monospace;
            color: #94a3b8;
            font-weight: normal;
            font-size: 11px;
        }

        .sig-table {
            width: 100%;
            border: none !important;
            border-collapse: collapse;
            background: transparent;
            margin-top: 40px;
        }

        .sig-table td {
            border: none !important;
            background: transparent;
            padding: 0;
        }

        .sig-box {
            text-align: center;
            font-size: 12px;
            color: #1E293B;
            line-height: 1.6;
        }

        .sig-space {
            height: 70px;
        }

        .summary-grid {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .summary-grid td {
            border: none !important;
            padding: 0 8px 0 0;
            vertical-align: top;
        }

        .stat-box {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px 14px;
            text-align: center;
        }

        .stat-box .stat-num {
            font-size: 24px;
            font-weight: bold;
            color: #000;
            font-family: 'Courier New', monospace;
        }

        .stat-box .stat-lbl {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        .sub-row-odd td {
            background: #ffffff;
        }

        .sub-row-even td {
            background: #f8fafc;
        }

        .badge-rankn {
            color: #64748b;
            font-size: 11px;
        }
    </style>
</head>

<body>
    @php
        $logoPath = public_path('images/logo.png');
        $logoData = '';
        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
        }
    @endphp

    <div class="header-wrap">
        <table class="header-table">
            <tr>
                <td style="width:65px; vertical-align:middle; padding:0 !important;">
                    @if($logoData)
                        <img src="data:image/png;base64,{{ $logoData }}" style="width:55px;height:55px;display:block;"
                            alt="Logo">
                    @endif
                </td>
                <td style="vertical-align:middle; padding:0 0 0 14px !important;">
                    <p class="company-name">Synthesis<span>Huis</span></p>
                    <p class="doc-title">@yield('doc_title')</p>
                    <p class="doc-sub">Sistem Pendukung Keputusan Metode AHP & Pemilihan Platform Digital
                        Marketing</p>
                    <p class="doc-address">Jl. Raya Bogor No.Km 24, RT.7/RW.6, Cijantung, Kec. Ps. Rebo, Kota Jakarta
                        Timur, Daerah Khusus Ibukota Jakarta 13770</p>
                </td>
            </tr>
        </table>
    </div>

    @yield('content')

    <table class="sig-table">
        <tr>
            <td style="width:60%;"></td>
            <td class="sig-box">
                <p class="doc-date">Jakarta, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                </p>
                <p style="font-weight:bold; margin:0;">Penanggung Jawab,</p>
                <div class="sig-space"></div>
                <p style="font-weight:bold; margin:0;">Kepala Marketing Communication</p>
            </td>
        </tr>
    </table>
</body>

</html>