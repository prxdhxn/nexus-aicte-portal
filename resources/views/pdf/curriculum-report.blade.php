<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Curriculum Report</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #0ea5e9;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            color: #0ea5e9;
            font-size: 24px;
        }
        .stats {
            width: 100%;
            margin-bottom: 20px;
        }
        .stats td {
            width: 33%;
            padding: 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            text-align: center;
        }
        .stats .value {
            font-size: 20px;
            font-weight: bold;
            color: #0f172a;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table.data th, table.data td {
            padding: 10px;
            border: 1px solid #cbd5e1;
            text-align: left;
        }
        table.data th {
            background-color: #f1f5f9;
            font-weight: bold;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #64748b;
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Nexus — AICTE Model Curriculum Portal</h1>
        <h2>{{ $curriculum->title }}</h2>
        <p>SME: {{ $curriculum->sme->name }} | Generated: {{ now()->format('d M Y, H:i') }}</p>
    </div>

    <table class="stats">
        <tr>
            <td>
                <div class="value">{{ $adoptions->count() }}</div>
                <div>Total Adoptions</div>
            </td>
            <td>
                <div class="value">{{ number_format($avgScore, 1) }}</div>
                <div>Avg Approval Score</div>
            </td>
            <td>
                <div class="value">{{ $adoptions->whereNull('approval_score')->count() }}</div>
                <div>Pending Reviews</div>
            </td>
        </tr>
    </table>

    <table class="data">
        <thead>
            <tr>
                <th>Institute Name</th>
                <th>Submitted Date</th>
                <th>Score</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($adoptions as $adoption)
                <tr>
                    <td>{{ $adoption->institute->name ?? 'Unknown Institute' }}</td>
                    <td>{{ $adoption->created_at->format('d M Y') }}</td>
                    <td>{{ $adoption->approval_score ?? '—' }}</td>
                    <td>{{ $adoption->approval_score !== null ? 'Graded' : 'Pending' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No adoptions found for this curriculum.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Nexus — AICTE Model Curriculum Portal | Confidential
    </div>
</body>
</html>
