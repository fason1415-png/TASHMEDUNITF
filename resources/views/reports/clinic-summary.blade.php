<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Clinic Summary</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        .header { margin-bottom: 16px; }
        .title { font-size: 18px; font-weight: bold; }
        .meta { color: #6b7280; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 14px; }
        th, td { border: 1px solid #d1d5db; padding: 8px; text-align: left; }
        th { background: #f3f4f6; }
    </style>
</head>
<body>
<div class="header">
    <div class="title">ShifoReyting AI - Clinic Summary</div>
    <div class="meta">
        Clinic: {{ $clinic->name }}<br>
        Period: {{ $from->format('Y-m-d') }} to {{ $to->format('Y-m-d') }}
    </div>
</div>

<table>
    <thead>
    <tr>
        <th>Metric</th>
        <th>Value</th>
    </tr>
    </thead>
    <tbody>
    <tr><td>Total Feedback</td><td>{{ $metrics['feedback_count'] }}</td></tr>
    <tr><td>Approved Responses</td><td>{{ $metrics['approved_count'] }}</td></tr>
    <tr><td>Average Quality Score</td><td>{{ $metrics['avg_quality_score'] }}</td></tr>
    <tr><td>Average Confidence Score</td><td>{{ $metrics['avg_confidence_score'] }}</td></tr>
    <tr><td>Flagged Responses</td><td>{{ $metrics['flagged_count'] }}</td></tr>
    <tr><td>Open High/Critical Escalations</td><td>{{ $metrics['critical_escalations'] }}</td></tr>
    </tbody>
</table>
</body>
</html>

