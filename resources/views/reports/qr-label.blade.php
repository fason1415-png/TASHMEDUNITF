<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>QR Label</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; color: #111827; }
        .card { border: 1px solid #d1d5db; border-radius: 12px; padding: 18px; width: 420px; }
        .title { font-size: 18px; font-weight: 700; margin-bottom: 8px; }
        .meta { font-size: 12px; color: #4b5563; margin-bottom: 12px; line-height: 1.5; }
        .qr { margin-top: 12px; margin-bottom: 12px; }
        .qr img { width: 180px; height: 180px; display: block; }
        .url { font-size: 11px; color: #6b7280; }
    </style>
</head>
<body>
<div class="card">
    <div class="title">ShifoReyting AI QR Label</div>
    <div class="meta">
        <div><strong>Clinic:</strong> {{ $qrCode->clinic?->name }}</div>
        <div><strong>Branch:</strong> {{ $qrCode->branch?->name }}</div>
        <div><strong>Department:</strong> {{ $qrCode->department?->name }}</div>
        <div><strong>Doctor:</strong> {{ $qrCode->doctor?->full_name }}</div>
        <div><strong>Service Point:</strong> {{ $qrCode->servicePoint?->name }}</div>
        <div><strong>QR Code:</strong> {{ $qrCode->code }}</div>
    </div>
    <div class="qr">
        <img src="{{ $qrImageDataUri }}" alt="QR Code">
    </div>
    <div class="url">{{ $surveyUrl }}</div>
</div>
</body>
</html>
