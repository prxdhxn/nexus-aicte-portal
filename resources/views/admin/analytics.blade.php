@extends('layouts.app')

@section('title', 'Admin Analytics - Nexus')

@push('styles')
<style>
    /* ═══ PAGE HEADER ═══ */
    .analytics-header {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 28px;
        margin-top: 8px;
    }
    .analytics-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        color: var(--text-main);
        margin: 0;
        letter-spacing: -0.5px;
    }
    .analytics-header p {
        color: var(--text-muted);
        margin: 4px 0 0;
        font-size: 0.88rem;
    }
    .analytics-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: color-mix(in srgb, var(--accent-color) 10%, var(--surface-color));
        border: 1px solid color-mix(in srgb, var(--accent-color) 25%, transparent);
        color: var(--accent-color);
        font-size: 0.78rem;
        font-weight: 700;
        padding: 5px 12px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* ═══ KPI CARDS ═══ */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) { .kpi-grid { grid-template-columns: repeat(2, 1fr); } }
    @media (max-width: 576px) { .kpi-grid { grid-template-columns: 1fr; } }

    .kpi-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        padding: 22px 22px 18px;
        position: relative;
        overflow: hidden;
        transition: var(--transition);
        animation: fadeUp 0.5s ease forwards;
        opacity: 0;
    }
    .kpi-card:hover { transform: translateY(-3px); box-shadow: 0 12px 35px rgba(0,0,0,0.1); }
    .kpi-card:nth-child(1) { animation-delay: 0.05s; }
    .kpi-card:nth-child(2) { animation-delay: 0.10s; }
    .kpi-card:nth-child(3) { animation-delay: 0.15s; }
    .kpi-card:nth-child(4) { animation-delay: 0.20s; }

    /* Coloured left accent bar */
    .kpi-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0;
        width: 4px; height: 100%;
        border-radius: 16px 0 0 16px;
    }
    .kpi-card.kpi-blue::before   { background: linear-gradient(180deg, #0ea5e9, #6366f1); }
    .kpi-card.kpi-violet::before { background: linear-gradient(180deg, #8b5cf6, #ec4899); }
    .kpi-card.kpi-emerald::before{ background: linear-gradient(180deg, #10b981, #06b6d4); }
    .kpi-card.kpi-amber::before  { background: linear-gradient(180deg, #f59e0b, #ef4444); }

    .kpi-icon {
        width: 42px; height: 42px;
        border-radius: 12px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1rem;
        margin-bottom: 16px;
    }
    .kpi-blue   .kpi-icon { background: rgba(14,165,233,0.12);  color: #0ea5e9; }
    .kpi-violet .kpi-icon { background: rgba(139,92,246,0.12);  color: #8b5cf6; }
    .kpi-emerald .kpi-icon{ background: rgba(16,185,129,0.12);  color: #10b981; }
    .kpi-amber  .kpi-icon { background: rgba(245,158,11,0.12);  color: #f59e0b; }

    .kpi-value {
        font-size: 2.1rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        color: var(--text-main);
        line-height: 1;
        margin-bottom: 4px;
    }
    .kpi-label {
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--text-muted);
        text-transform: uppercase;
        letter-spacing: 0.6px;
    }

    /* ═══ CHART CARDS ═══ */
    .chart-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 992px) { .chart-grid { grid-template-columns: 1fr; } }

    .panel {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: 16px;
        overflow: hidden;
        animation: fadeUp 0.5s ease 0.25s forwards;
        opacity: 0;
    }
    .panel-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 22px 14px;
        border-bottom: 1px solid var(--border-color);
    }
    .panel-title {
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--text-main);
        font-family: 'Outfit', sans-serif;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .panel-title i { color: var(--accent-color); font-size: 0.85rem; }
    .panel-subtitle {
        font-size: 0.75rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .panel-body { padding: 22px; }
    .panel-body-sm { padding: 18px 22px; }

    /* avg score pill inside panel header */
    .avg-pill {
        background: color-mix(in srgb, #10b981 12%, var(--surface-color));
        border: 1px solid color-mix(in srgb, #10b981 25%, transparent);
        color: #10b981;
        font-size: 0.75rem;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
    }

    /* ═══ SCORE LEGEND ═══ */
    .score-legend {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-top: 18px;
    }
    .score-legend-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.82rem;
        color: var(--text-muted);
        font-weight: 500;
    }
    .score-legend-dot {
        width: 10px; height: 10px;
        border-radius: 3px;
        margin-right: 8px;
        flex-shrink: 0;
    }

    /* ═══ TOP TABLE ═══ */
    .rank-table { width: 100%; border-collapse: collapse; }
    .rank-table th {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        color: var(--text-muted);
        padding: 10px 14px;
        border-bottom: 1px solid var(--border-color);
        white-space: nowrap;
    }
    .rank-table td {
        padding: 13px 14px;
        border-bottom: 1px solid var(--border-color);
        vertical-align: middle;
        color: var(--text-main);
        font-size: 0.88rem;
    }
    .rank-table tr:last-child td { border-bottom: none; }
    .rank-table tbody tr { transition: background 0.15s ease; }
    .rank-table tbody tr:hover { background: color-mix(in srgb, var(--accent-color) 4%, var(--surface-color)); }

    .rank-num {
        width: 28px; height: 28px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 0.75rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
    }
    .rank-1 { background: rgba(245,158,11,0.15); color: #f59e0b; }
    .rank-2 { background: rgba(148,163,184,0.15); color: #94a3b8; }
    .rank-3 { background: rgba(205,127,50,0.12); color: #cd7f32; }
    .rank-other { background: var(--bg-color); color: var(--text-muted); }

    .curr-title-link {
        font-weight: 600;
        color: var(--text-main);
        text-decoration: none;
        transition: color 0.15s;
    }
    .curr-title-link:hover { color: var(--accent-color); }

    .sme-pill {
        display: inline-flex; align-items: center; gap: 6px;
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--text-muted);
        padding: 3px 10px;
    }

    .adoption-bar-wrap {
        display: flex; align-items: center; gap: 10px;
    }
    .adoption-bar {
        flex: 1;
        height: 6px;
        background: var(--bg-color);
        border-radius: 4px;
        overflow: hidden;
        min-width: 60px;
    }
    .adoption-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, var(--accent-color), #6366f1);
        border-radius: 4px;
        transition: width 0.8s cubic-bezier(0.4,0,0.2,1);
    }
    .adoption-count {
        font-size: 0.82rem;
        font-weight: 700;
        color: var(--text-main);
        min-width: 18px;
        text-align: right;
    }

    /* ═══ SECONDARY ROW ═══ */
    .secondary-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 18px;
        margin-bottom: 24px;
    }
    @media (max-width: 768px) { .secondary-grid { grid-template-columns: 1fr; } }

    .mini-stat {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 16px;
        border-bottom: 1px solid var(--border-color);
    }
    .mini-stat:last-child { border-bottom: none; }
    .mini-stat-icon {
        width: 36px; height: 36px; border-radius: 10px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; flex-shrink: 0;
    }
    .mini-stat-val { font-weight: 700; font-size: 1rem; color: var(--text-main); }
    .mini-stat-lbl { font-size: 0.75rem; color: var(--text-muted); font-weight: 500; }
</style>
@endpush

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
<div class="analytics-header">
    <div>
        <h1><i class="fa-solid fa-chart-mixed me-2" style="color:var(--accent-color);font-size:1.4rem;"></i>Analytics</h1>
        <p>Platform overview · {{ now()->format('l, d M Y') }}</p>
    </div>
    <div class="d-flex align-items-center gap-3">
        <span class="analytics-badge"><i class="fa-solid fa-circle-dot"></i> Live Data</span>
        <button onclick="window.print()" class="btn btn-outline-secondary btn-sm" style="font-size:0.8rem;">
            <i class="fa-solid fa-print me-1"></i>Export
        </button>
    </div>
</div>

{{-- ── KPI CARDS ────────────────────────────────────────────────── --}}
<div class="kpi-grid">
    <div class="kpi-card kpi-blue">
        <div class="kpi-icon"><i class="fa-solid fa-users"></i></div>
        <div class="kpi-value">{{ $stats['total_users'] }}</div>
        <div class="kpi-label">Total Users</div>
    </div>
    <div class="kpi-card kpi-violet">
        <div class="kpi-icon"><i class="fa-solid fa-building-columns"></i></div>
        <div class="kpi-value">{{ $stats['total_institutes'] }}</div>
        <div class="kpi-label">Institutes</div>
    </div>
    <div class="kpi-card kpi-emerald">
        <div class="kpi-icon"><i class="fa-solid fa-book-open-reader"></i></div>
        <div class="kpi-value">{{ $stats['total_curricula'] }}</div>
        <div class="kpi-label">Curricula Published</div>
    </div>
    <div class="kpi-card kpi-amber">
        <div class="kpi-icon"><i class="fa-solid fa-file-arrow-up"></i></div>
        <div class="kpi-value">{{ $stats['total_adoptions'] }}</div>
        <div class="kpi-label">Total Adoptions</div>
    </div>
</div>

{{-- ── CHARTS ROW ───────────────────────────────────────────────── --}}
<div class="chart-grid">

    {{-- Line Chart --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-title"><i class="fa-solid fa-chart-line"></i> Adoption Trends</p>
                <span class="panel-subtitle">Submissions over the last 7 days</span>
            </div>
        </div>
        <div class="panel-body">
            <canvas id="adoptionTrendChart" style="height:260px;max-height:260px;"></canvas>
        </div>
    </div>

    {{-- Doughnut + Score Legend --}}
    <div class="panel">
        <div class="panel-header">
            <div>
                <p class="panel-title"><i class="fa-solid fa-chart-pie"></i> Score Distribution</p>
                <span class="panel-subtitle">Approval score ranges</span>
            </div>
            <span class="avg-pill"><i class="fa-solid fa-star" style="font-size:0.65rem;"></i> Avg {{ $avgApprovalScore }}/100</span>
        </div>
        <div class="panel-body" style="display:flex;flex-direction:column;align-items:center;">
            <div style="width:180px;height:180px;">
                <canvas id="scoreRangeChart"></canvas>
            </div>
            <div class="score-legend w-100 mt-3">
                @php
                    $legendColors = ['#ef4444','#f59e0b','#10b981'];
                    $i = 0;
                @endphp
                @foreach($scoreRanges as $label => $count)
                <div class="score-legend-item">
                    <div class="d-flex align-items-center">
                        <span class="score-legend-dot" style="background:{{ $legendColors[$i] }};"></span>
                        {{ $label }}
                    </div>
                    <strong style="color:var(--text-main);">{{ $count }}</strong>
                </div>
                @php $i++; @endphp
                @endforeach
            </div>
        </div>
    </div>

</div>

{{-- ── TOP CURRICULA TABLE ──────────────────────────────────────── --}}
<div class="panel" style="animation-delay:0.35s;">
    <div class="panel-header">
        <div>
            <p class="panel-title"><i class="fa-solid fa-trophy"></i> Top Curricula by Adoption</p>
            <span class="panel-subtitle">Most adopted curricula on the platform</span>
        </div>
        <a href="{{ route('curricula.index') }}" class="btn btn-outline-secondary btn-sm" style="font-size:0.78rem;">
            View All <i class="fa-solid fa-arrow-right ms-1"></i>
        </a>
    </div>
    @php $maxAdoptions = $topCurricula->max('adoptions_count') ?: 1; @endphp
    <div style="overflow-x:auto;">
        <table class="rank-table">
            <thead>
                <tr>
                    <th style="width:44px;padding-left:22px;">#</th>
                    <th>Curriculum Title</th>
                    <th>SME / Owner</th>
                    <th style="min-width:180px;">Adoptions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topCurricula as $idx => $curr)
                <tr>
                    <td style="padding-left:22px;">
                        <span class="rank-num rank-{{ $idx < 3 ? $idx+1 : 'other' }}">{{ $idx + 1 }}</span>
                    </td>
                    <td>
                        <a href="{{ route('curricula.show', $curr) }}" class="curr-title-link">
                            {{ Str::limit($curr->title, 55) }}
                        </a>
                    </td>
                    <td>
                        <span class="sme-pill">
                            <i class="fa-solid fa-circle-user" style="color:var(--accent-color);font-size:0.7rem;"></i>
                            {{ $curr->sme->name ?? '—' }}
                        </span>
                    </td>
                    <td>
                        <div class="adoption-bar-wrap">
                            <div class="adoption-bar">
                                <div class="adoption-bar-fill" style="width: {{ $maxAdoptions > 0 ? round(($curr->adoptions_count / $maxAdoptions) * 100) : 0 }}%;"></div>
                            </div>
                            <span class="adoption-count">{{ $curr->adoptions_count }}</span>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection

@push('scripts')
<script>
(function() {
    // Read CSS theme variables so charts auto-adapt to dark/light mode
    const style    = getComputedStyle(document.documentElement);
    const textMain = style.getPropertyValue('--text-main').trim()   || '#1e293b';
    const textMuted= style.getPropertyValue('--text-muted').trim()  || '#64748b';
    const border   = style.getPropertyValue('--border-color').trim()|| '#e2e8f0';
    const accent   = style.getPropertyValue('--accent-color').trim()|| '#0ea5e9';

    // ── Shared chart defaults ──────────────────────────────────────
    Chart.defaults.color = textMuted;
    Chart.defaults.borderColor = border;
    Chart.defaults.font.family = "'Inter', sans-serif";

    // ── Adoption Trend — Line ──────────────────────────────────────
    new Chart(document.getElementById('adoptionTrendChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($labels) !!},
            datasets: [{
                label: 'Adoptions',
                data: {!! json_encode($adoptionTrends) !!},
                borderColor: accent,
                backgroundColor: function(ctx) {
                    const grad = ctx.chart.ctx.createLinearGradient(0, 0, 0, 260);
                    grad.addColorStop(0,   'rgba(14,165,233,0.22)');
                    grad.addColorStop(1,   'rgba(14,165,233,0.00)');
                    return grad;
                },
                borderWidth: 2.5,
                fill: true,
                tension: 0.42,
                pointBackgroundColor: accent,
                pointBorderColor: 'transparent',
                pointRadius: 4,
                pointHoverRadius: 6,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.parsed.y} adoption${ctx.parsed.y !== 1 ? 's' : ''}`
                    }
                }
            },
            scales: {
                x: {
                    grid: { color: border, drawTicks: false },
                    ticks: { color: textMuted, font: { size: 11 }, padding: 6 },
                    border: { display: false }
                },
                y: {
                    beginAtZero: true,
                    ticks: { precision: 0, color: textMuted, font: { size: 11 }, padding: 8 },
                    grid: { color: border },
                    border: { display: false, dash: [4,4] }
                }
            }
        }
    });

    // ── Score Distribution — Doughnut ──────────────────────────────
    new Chart(document.getElementById('scoreRangeChart'), {
        type: 'doughnut',
        data: {
            labels: {!! json_encode(array_keys($scoreRanges)) !!},
            datasets: [{
                data: {!! json_encode(array_values($scoreRanges)) !!},
                backgroundColor: ['#ef4444', '#f59e0b', '#10b981'],
                borderColor: style.getPropertyValue('--surface-color').trim() || '#ffffff',
                borderWidth: 3,
                hoverOffset: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0f172a',
                    titleColor: '#f1f5f9',
                    bodyColor: '#94a3b8',
                    borderColor: '#334155',
                    borderWidth: 1,
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.label}: ${ctx.parsed} submission${ctx.parsed !== 1 ? 's' : ''}`
                    }
                }
            }
        }
    });

    // ── Re-render charts on theme toggle so colors stay in sync ───
    const darkBtn = document.getElementById('darkToggle');
    if (darkBtn) {
        darkBtn.addEventListener('click', () => {
            setTimeout(() => location.reload(), 350);
        });
    }
})();
</script>
@endpush
