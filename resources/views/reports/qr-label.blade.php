<!DOCTYPE html>
<html lang="uz">
<head>
    <meta charset="UTF-8">
    <title>QR Label — {{ $qrCode->code }}</title>
    <style>
        @page { margin: 20mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1e293b;
            margin: 0;
            padding: 0;
        }
        .card {
            border: 3px solid #0d9488;
            border-radius: 20px;
            padding: 30px 36px;
            width: 480px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 16px;
            border-bottom: 2px solid #e2e8f0;
        }
        .logo {
            font-size: 22px;
            font-weight: 700;
            color: #0f766e;
            letter-spacing: -0.5px;
        }
        .logo-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }
        .doctor-block {
            background-color: #f0fdfa;
            border: 1px solid #99f6e4;
            border-radius: 12px;
            padding: 14px 18px;
            margin-bottom: 18px;
            text-align: center;
        }
        .doctor-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f766e;
        }
        .doctor-dept {
            font-size: 12px;
            color: #64748b;
            margin-top: 3px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 6px;
        }
        .info-label {
            display: table-cell;
            width: 100px;
            font-size: 11px;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 3px 0;
        }
        .info-value {
            display: table-cell;
            font-size: 12px;
            color: #334155;
            padding: 3px 0;
        }
        .qr-section {
            text-align: center;
            margin: 24px 0 16px;
        }
        .qr-section img {
            width: 300px;
            height: 300px;
            display: inline-block;
        }
        .scan-text {
            text-align: center;
            font-size: 14px;
            font-weight: 700;
            color: #0f766e;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .scan-sub {
            text-align: center;
            font-size: 11px;
            color: #94a3b8;
            margin-bottom: 16px;
        }
        .url-bar {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
            word-break: break-all;
        }
        .code-badge {
            text-align: center;
            margin-top: 12px;
        }
        .code-badge span {
            background-color: #0d9488;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            padding: 4px 14px;
            border-radius: 20px;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="header">
        <div class="logo">ShifoReyting</div>
        <div class="logo-sub">Tibbiy xizmat sifatini baholash tizimi</div>
    </div>

    @if($qrCode->doctor)
        <div class="doctor-block">
            <div class="doctor-name">{{ $qrCode->doctor->full_name }}</div>
            @if($qrCode->department)
                <div class="doctor-dept">{{ $qrCode->department->name }}</div>
            @endif
        </div>
    @endif

    @if($qrCode->clinic)
        <div class="info-row">
            <div class="info-label">Klinika</div>
            <div class="info-value">{{ $qrCode->clinic->name }}</div>
        </div>
    @endif
    @if($qrCode->branch)
        <div class="info-row">
            <div class="info-label">Filial</div>
            <div class="info-value">{{ $qrCode->branch->name }}</div>
        </div>
    @endif

    <div class="scan-text">Skanerlang va baholang!</div>
    <div class="scan-sub">Telefoningiz kamerasi bilan QR kodni skanerlang</div>

    <div class="qr-section">
        <img src="{{ $qrImageDataUri }}" alt="QR Code">
    </div>

    <div class="url-bar">{{ $surveyUrl }}</div>

    <div class="code-badge">
        <span>{{ $qrCode->code }}</span>
    </div>
</div>
</body>
</html>
