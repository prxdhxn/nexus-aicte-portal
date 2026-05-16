@extends('layouts.app')
@section('title', 'Dashboard - Nexus')

{{-- ═══════════════════════════════════════════
     NEXUS WELCOME ANIMATION — fires once on login
═══════════════════════════════════════════════ --}}
@push('styles')
<style>
    /* Overlay */
    #nexusWelcome {
        position: fixed; inset: 0; z-index: 99999;
        background: #04101f;
        display: flex; align-items: center; justify-content: center;
        overflow: hidden;
        font-family: 'Outfit', sans-serif;
    }

    /* Constellation canvas */
    #nexusCanvas {
        position: absolute; inset: 0;
        width: 100%; height: 100%;
        opacity: 0.5;
    }

    /* Scan line */
    #nexusScan {
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, #0ea5e9, #38bdf8, #0ea5e9, transparent);
        box-shadow: 0 0 20px 4px rgba(14,165,233,0.6);
        animation: scanLine 0.9s cubic-bezier(0.4,0,0.2,1) 0.2s forwards;
    }
    @keyframes scanLine {
        from { left: -100%; top: 50%; }
        to   { left: 100%;  top: 50%; }
    }

    /* Main content */
    #nexusContent {
        position: relative; z-index: 2;
        text-align: center;
        opacity: 0;
        animation: nexusFadeIn 0.5s ease 0.9s forwards;
    }
    @keyframes nexusFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Corner label */
    #nexusCorner {
        position: absolute; top: 28px; left: 32px;
        font-size: 0.72rem; font-weight: 700;
        color: rgba(14,165,233,0.6);
        letter-spacing: 4px; text-transform: uppercase;
        opacity: 0;
        animation: nexusFadeIn 0.4s ease 0.5s forwards;
    }

    /* Version tag */
    #nexusVersion {
        position: absolute; bottom: 28px; right: 32px;
        font-size: 0.65rem; font-weight: 600;
        color: rgba(255,255,255,0.2);
        letter-spacing: 3px;
        opacity: 0;
        animation: nexusFadeIn 0.4s ease 0.5s forwards;
    }

    /* Logo */
    .nw-logo {
        font-size: clamp(3rem, 8vw, 5.5rem);
        font-weight: 800;
        letter-spacing: -2px;
        color: #ffffff;
        line-height: 1;
        margin-bottom: 4px;
        text-shadow: 0 0 60px rgba(14,165,233,0.5), 0 0 120px rgba(14,165,233,0.2);
    }
    .nw-logo span { color: #38bdf8; }

    /* Divider line */
    .nw-divider {
        width: 0; height: 1px;
        background: linear-gradient(90deg, transparent, #0ea5e9, transparent);
        margin: 16px auto;
        animation: expandLine 0.6s ease 1.1s forwards;
    }
    @keyframes expandLine {
        to { width: 220px; }
    }

    /* Greeting */
    .nw-greeting {
        font-size: 0.78rem;
        letter-spacing: 5px;
        text-transform: uppercase;
        color: #0ea5e9;
        font-weight: 700;
        margin-bottom: 10px;
        opacity: 0;
        animation: nexusFadeIn 0.5s ease 1.3s forwards;
    }

    /* User name — typing cursor */
    .nw-name {
        font-size: clamp(1.4rem, 4vw, 2.2rem);
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1px;
        min-height: 3rem;
    }
    .nw-cursor {
        display: inline-block;
        width: 3px; height: 1.1em;
        background: #38bdf8;
        margin-left: 3px;
        vertical-align: text-bottom;
        border-radius: 2px;
        animation: blink 0.7s steps(1) infinite;
    }
    @keyframes blink { 50% { opacity: 0; } }

    /* Role badge */
    .nw-badge {
        display: inline-flex; align-items: center; gap: 7px;
        background: rgba(14,165,233,0.12);
        border: 1px solid rgba(14,165,233,0.35);
        color: #38bdf8;
        padding: 6px 18px; border-radius: 30px;
        font-size: 0.78rem; font-weight: 700;
        letter-spacing: 2px; text-transform: uppercase;
        margin-top: 18px;
        opacity: 0;
        animation: nexusFadeIn 0.5s ease 2.4s forwards;
    }
    .nw-badge::before {
        content: '';
        width: 7px; height: 7px; border-radius: 50%;
        background: #0ea5e9;
        box-shadow: 0 0 8px #0ea5e9;
        animation: blink 1.2s steps(1) infinite;
    }

    /* Curtain panels — exit animation */
    #nexusCurtainTop,
    #nexusCurtainBottom {
        position: absolute; left: 0; right: 0;
        background: #04101f;
        z-index: 3;
        transition: transform 0.7s cubic-bezier(0.7,0,0.3,1);
    }
    #nexusCurtainTop    { top: 0;   height: 50%; transform: translateY(0); }
    #nexusCurtainBottom { bottom: 0; height: 50%; transform: translateY(0); }
    #nexusCurtainTop.open    { transform: translateY(-100%); }
    #nexusCurtainBottom.open { transform: translateY(100%); }
</style>
@endpush


@push('styles')
<style>
    .stat-card {
        background: var(--surface-color);
        border: 1px solid var(--border-color);
        border-radius: var(--radius);
        padding: 22px;
        display: flex;
        align-items: center;
        gap: 18px;
        transition: var(--transition);
        box-shadow: var(--shadow-sm);
    }
    .stat-card:hover { transform: translateY(-3px); box-shadow: var(--shadow-md); }
    .stat-icon {
        width: 52px; height: 52px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.3rem; flex-shrink: 0;
    }
    .stat-icon.blue   { background: rgba(14,165,233,0.12); color: #0ea5e9; }
    .stat-icon.green  { background: rgba(16,185,129,0.12); color: #10b981; }
    .stat-icon.orange { background: rgba(245,158,11,0.12); color: #f59e0b; }
    .stat-icon.purple { background: rgba(139,92,246,0.12); color: #8b5cf6; }
    .stat-number { font-size: 1.8rem; font-weight: 800; color: var(--text-main); line-height: 1; font-family: 'Outfit', sans-serif; }
    .stat-label  { font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }

    .activity-feed { display: flex; flex-direction: column; gap: 0; }
    .activity-item {
        display: flex; align-items: flex-start; gap: 14px;
        padding: 13px 0;
        border-bottom: 1px solid var(--border-color);
        animation: fadeUp 0.4s ease;
    }
    .activity-item:last-child { border-bottom: none; }
    .activity-dot {
        width: 34px; height: 34px; border-radius: 10px;
        background: var(--bg-color);
        border: 1px solid var(--border-color);
        display: flex; align-items: center; justify-content: center;
        font-size: 0.75rem; color: var(--accent-color);
        flex-shrink: 0; margin-top: 2px;
    }
    .activity-action { font-weight: 600; font-size: 0.85rem; color: var(--text-main); }
    .activity-desc   { font-size: 0.8rem; color: var(--text-muted); margin-top: 1px; }
    .activity-time   { font-size: 0.72rem; color: var(--text-muted); margin-top: 2px; }
    .activity-user   { font-size: 0.72rem; font-weight: 600; color: var(--accent-color); }

    .orb { position: absolute; filter: blur(100px); opacity: 0.12; border-radius: 50%; animation: float 25s infinite ease-in-out; pointer-events: none; }
    .orb-1 { width: 500px; height: 500px; background: #0ea5e9; top: -150px; left: -100px; animation-delay: 0s; }
    .orb-2 { width: 400px; height: 400px; background: #8b5cf6; bottom: -100px; right: -100px; animation-delay: -8s; }
    .orb-3 { width: 300px; height: 300px; background: #10b981; top: 40%; left: 55%; animation-delay: -15s; }
    @keyframes float { 0%, 100% { transform: translate(0,0) scale(1); } 33% { transform: translate(60px,-80px) scale(1.05); } 66% { transform: translate(-40px,60px) scale(0.95); } }
    .chart-wrapper { position: relative; height: 220px; }
    .empty-activity { text-align: center; padding: 30px 0; color: var(--text-muted); }
    .empty-activity i { font-size: 2rem; opacity: 0.3; display: block; margin-bottom: 10px; }
</style>
@endpush

@section('content')
{{-- Welcome Overlay: always rendered; JS decides whether to show it --}}
<div id="nexusWelcome" style="display:none">
    <canvas id="nexusCanvas"></canvas>
    <div id="nexusScan"></div>
    <div id="nexusCorner">nexus&nbsp;&nbsp;//&nbsp;&nbsp;aicte</div>
    <div id="nexusVersion">v2.0&nbsp;·&nbsp;{{ date('Y') }}</div>
    <div id="nexusContent">
        <div class="nw-logo">N<span>e</span>xus</div>
        <div class="nw-divider"></div>
        <div class="nw-greeting">Access Granted</div>
        <div class="nw-name" id="nwName"><span class="nw-cursor"></span></div>
        <div class="nw-badge">{{ ucfirst(auth()->user()->role) }}</div>
    </div>
    <div id="nexusCurtainTop"></div>
    <div id="nexusCurtainBottom"></div>
</div>

<div style="position:fixed;inset:0;overflow:hidden;pointer-events:none;z-index:-1;">
    <div class="orb orb-1"></div><div class="orb orb-2"></div><div class="orb orb-3"></div>
</div>

<!-- Welcome Row -->
<div class="d-flex justify-content-between align-items-center mb-4 fade-up">
    <div>
        <h2 class="fw-bold mb-1" style="font-family:'Outfit',sans-serif;">
            👋 {{ __('dashboard.welcome_back') }}, {{ auth()->user()->name }}!
        </h2>
        <p class="text-muted mb-0" style="font-size:0.9rem;">{{ __('dashboard.overview') }}</p>
    </div>
    <span class="badge" style="background:var(--accent-glow);color:var(--accent-color);font-size:0.8rem;padding:8px 14px;border-radius:20px;border:1px solid var(--accent-color);">
        <i class="fa-solid fa-circle-dot me-1" style="font-size:0.6rem;"></i>{{ ucfirst(auth()->user()->role) }}
    </span>
</div>

<!-- Stats Cards -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3 fade-up fade-up-delay-1">
        <div class="stat-card h-100">
            <div class="stat-icon blue"><i class="fa-solid fa-book-open"></i></div>
            <div>
                <div class="stat-number" id="stat-curricula" data-target="0">...</div>
                <div class="stat-label">{{ __('dashboard.total_curricula') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up fade-up-delay-2">
        <div class="stat-card h-100">
            <div class="stat-icon green"><i class="fa-solid fa-file-circle-check"></i></div>
            <div>
                <div class="stat-number" id="stat-submissions" data-target="0">...</div>
                <div class="stat-label" id="stat-submissions-label">{{ __('dashboard.submissions') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up fade-up-delay-3">
        <div class="stat-card h-100">
            <div class="stat-icon orange"><i class="fa-solid fa-star-half-stroke"></i></div>
            <div>
                <div class="stat-number" id="stat-score" data-target="0" data-suffix="/100">...</div>
                <div class="stat-label" id="stat-score-label">{{ __('dashboard.avg_score') }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3 fade-up fade-up-delay-4">
        <div class="stat-card h-100">
            <div class="stat-icon purple"><i class="fa-solid fa-calendar-days"></i></div>
            <div>
                <div class="stat-number" id="stat-deadlines" data-target="0">...</div>
                <div class="stat-label">{{ __('dashboard.due_week') }}</div>
            </div>
        </div>
    </div>
</div>

<!-- Chart + Activity -->
<div class="row g-4 mb-4">
    <!-- Chart -->
    <div class="col-lg-7 fade-up">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="fa-solid fa-chart-bar me-2" style="color:var(--accent-color)"></i>{{ $chartTitle ?? 'Analytics' }}</span>
            </div>
            <div class="card-body">
                @if(count($chartLabels) > 0)
                    <div class="chart-wrapper">
                        <canvas id="submissionsChart"></canvas>
                    </div>
                @else
                    <div class="empty-activity">
                        <i class="fa-solid fa-chart-simple"></i>
                        @if(auth()->user()->role === 'institute')
                            No scored adoptions yet. Submit an adoption to start tracking scores!
                        @else
                            No data yet. Create a curriculum to see analytics.
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Activity Feed -->
    <div class="col-lg-5 fade-up">
        <div class="card h-100">
            <div class="card-header">
                <i class="fa-solid fa-timeline me-2" style="color:var(--accent-color)"></i>{{ __('dashboard.recent_activity') }}
                @if(auth()->user()->role === 'admin')
                    <small class="text-muted ms-2" style="font-weight:400;">(All users)</small>
                @endif
            </div>
            <div class="card-body p-0">
                @if($activities->count() > 0)
                    <div class="activity-feed px-3 pt-2 pb-1">
                        @foreach($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-dot"><i class="fa-solid {{ $activity->icon ?? 'fa-circle-info' }}"></i></div>
                            <div style="flex:1;min-width:0">
                                <div class="activity-action">{{ $activity->action }}</div>
                                <div class="activity-desc">{{ $activity->description }}</div>
                                <div class="d-flex gap-2 mt-1">
                                    <span class="activity-user">{{ $activity->user_name }}</span>
                                    <span class="activity-time">· {{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="empty-activity">
                        <i class="fa-solid fa-wave-square"></i>
                        No activity yet. Start by creating a curriculum!
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-3">
    @if(auth()->user()->role === 'admin')
    <div class="col-md-4 fade-up">
        <div class="card text-center p-4">
            <div class="mb-3"><i class="fa-solid fa-users fa-2x" style="color:var(--accent-color)"></i></div>
            <h6 class="fw-bold mb-2">Manage Users</h6>
            <p class="text-muted small mb-3">Add, view or remove portal users.</p>
            <a href="{{ route('admin.users.index') }}" class="btn btn-primary btn-sm rounded-pill px-4">Go to Users</a>
        </div>
    </div>
    @endif
    <div class="col-md-4 fade-up">
        <div class="card text-center p-4">
            <div class="mb-3"><i class="fa-solid fa-book-open fa-2x" style="color:#8b5cf6"></i></div>
            <h6 class="fw-bold mb-2">Curricula</h6>
            <p class="text-muted small mb-3">
                @if(auth()->user()->role === 'institute') View and submit your curricula.
                @else Manage curricula and view adoptions. @endif
            </p>
            <a href="{{ route('curricula.index') }}" class="btn btn-primary btn-sm rounded-pill px-4">Browse Curricula</a>
        </div>
    </div>
</div>

@push('scripts')
<script>
// ═══ WELCOME ANIMATION — referrer + sessionStorage detection ═══
(function () {
    const uid    = '{{ auth()->id() }}';
    const key    = 'nxw_' + uid;
    const ref    = document.referrer;
    const fromLogin = ref.includes('/login') || ref.includes('/auth/');
    const fresh  = fromLogin && !sessionStorage.getItem(key);
    const el     = document.getElementById('nexusWelcome');
    if (!el) return;

    if (!fresh) { el.remove(); return; } // not a fresh login — kill it immediately

    sessionStorage.setItem(key, '1');
    // Page loader must be gone
    const loader = document.getElementById('page-loader');
    if (loader) loader.style.display = 'none';
    // Show overlay
    el.style.display = 'flex';

    /* Constellation canvas */
    const canvas = document.getElementById('nexusCanvas');
    const ctx = canvas.getContext('2d');
    let W, H, pts;
    function resize() { W = canvas.width = window.innerWidth; H = canvas.height = window.innerHeight; }
    function initPts() { pts = Array.from({length:55},()=>({x:Math.random()*W,y:Math.random()*H,vx:(Math.random()-.5)*.35,vy:(Math.random()-.5)*.35,r:Math.random()*2+1})); }
    function drawPts() {
        ctx.clearRect(0,0,W,H);
        for(let i=0;i<pts.length;i++) for(let j=i+1;j<pts.length;j++){const d=Math.hypot(pts[i].x-pts[j].x,pts[i].y-pts[j].y);if(d<140){ctx.beginPath();ctx.strokeStyle=`rgba(14,165,233,${.18*(1-d/140)})`;ctx.lineWidth=.8;ctx.moveTo(pts[i].x,pts[i].y);ctx.lineTo(pts[j].x,pts[j].y);ctx.stroke();}}
        pts.forEach(p=>{ctx.beginPath();ctx.arc(p.x,p.y,p.r,0,Math.PI*2);ctx.fillStyle='rgba(56,189,248,0.7)';ctx.fill();p.x+=p.vx;p.y+=p.vy;if(p.x<0||p.x>W)p.vx*=-1;if(p.y<0||p.y>H)p.vy*=-1;});
    }
    let raf; function loop(){drawPts();raf=requestAnimationFrame(loop);}
    resize(); initPts(); loop();
    window.addEventListener('resize',()=>{resize();initPts();});

    /* Typing */
    const name = @json(auth()->user()->name);
    const nameEl = document.getElementById('nwName');
    let typed = 0;
    function typeNext() {
        if (typed < name.length) {
            nameEl.innerHTML = name.slice(0,++typed)+'<span class="nw-cursor"></span>';
            setTimeout(typeNext, 65 + Math.random()*40);
        }
    }
    setTimeout(typeNext, 1500);

    /* Curtain exit */
    let exited = false;
    function exit() {
        if (exited) return; exited = true;
        document.getElementById('nexusCurtainTop').classList.add('open');
        document.getElementById('nexusCurtainBottom').classList.add('open');
        setTimeout(()=>{
            cancelAnimationFrame(raf);
            el.style.transition='opacity 0.35s ease';
            el.style.opacity='0';
            setTimeout(()=>el.remove(), 350);
        }, 680);
    }
    setTimeout(exit, 3400);
    el.addEventListener('click', exit);
})();

// ═══ FETCH STATS VIA API & COUNT-UP ANIMATION ═══
function triggerCountUp() {
    document.querySelectorAll('.stat-number[data-target]').forEach(el => {
        const target  = parseFloat(el.dataset.target) || 0;
        const suffix  = el.dataset.suffix || '';
        const isFloat = !Number.isInteger(target);
        let start = 0;
        const duration = 1200;
        const step = timestamp => {
            if (!start) start = timestamp;
            const progress = Math.min((timestamp - start) / duration, 1);
            const ease = 1 - Math.pow(1 - progress, 3);
            const val = ease * target;
            el.textContent = (isFloat ? val.toFixed(1) : Math.floor(val)) + suffix;
            if (progress < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    });
}

fetch('/api/stats')
    .then(r => r.json())
    .then(data => {
        // Update stat labels dynamically
        const subLabel = document.getElementById('stat-submissions-label');
        if (subLabel && data.submissionsLabel) subLabel.textContent = data.submissionsLabel;

        document.getElementById('stat-curricula').dataset.target = data.totalCurricula;
        document.getElementById('stat-submissions').dataset.target = data.totalSubmissions;
        document.getElementById('stat-deadlines').dataset.target = data.upcomingDeadlines;

        // Avg score: show "—" if no scored submissions yet
        const scoreEl = document.getElementById('stat-score');
        if (data.avgScore === 0) {
            scoreEl.removeAttribute('data-target');
            scoreEl.textContent = '—';
            const scoreLabel = document.getElementById('stat-score-label');
            if (scoreLabel) scoreLabel.textContent = 'Avg. Score';
        } else {
            scoreEl.dataset.target = data.avgScore;
        }

        triggerCountUp();
    })
    .catch(err => console.error('Failed to fetch stats:', err));

// ═══ CHART.JS ═══
@if(count($chartLabels) > 0)
const isDark = document.documentElement.getAttribute('data-theme') === 'dark';
const ctx = document.getElementById('submissionsChart').getContext('2d');
const submissionsChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($chartLabels),
        datasets: [{
            label: 'Submissions',
            data: @json($chartData),
            backgroundColor: 'rgba(14,165,233,0.18)',
            borderColor: 'rgba(14,165,233,0.9)',
            borderWidth: 2,
            borderRadius: 8,
            hoverBackgroundColor: 'rgba(14,165,233,0.35)',
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: {
                grid: { display: false },
                ticks: { color: isDark ? '#94a3b8' : '#64748b', font: { size: 11 } }
            },
            y: {
                beginAtZero: true, ticks: { stepSize: 1, color: isDark ? '#94a3b8' : '#64748b', font: { size: 11 } },
                grid: { color: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)' }
            }
        }
    }
});
// Update chart colors on dark mode toggle
document.getElementById('darkToggle')?.addEventListener('click', () => {
    setTimeout(() => {
        const dark = document.documentElement.getAttribute('data-theme') === 'dark';
        submissionsChart.options.scales.x.ticks.color = dark ? '#94a3b8' : '#64748b';
        submissionsChart.options.scales.y.ticks.color = dark ? '#94a3b8' : '#64748b';
        submissionsChart.options.scales.y.grid.color  = dark ? 'rgba(255,255,255,0.06)' : 'rgba(0,0,0,0.05)';
        submissionsChart.update();
    }, 100);
});
@endif
</script>
@endpush
@endsection
