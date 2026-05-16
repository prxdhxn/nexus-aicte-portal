<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Nexus | AICTE Curriculum Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; height: 100vh; overflow: hidden; background: #0a0f1e; }
        .login-wrap { display: flex; height: 100vh; }

        /* ═══════════════════════════════════════
           LEFT — FORM PANEL
        ═══════════════════════════════════════ */
        .form-panel {
            width: 500px;
            min-width: 500px;
            background: linear-gradient(160deg, #ffffff 0%, #f0f6ff 100%);
            display: flex;
            flex-direction: column;
            padding: 40px 56px 36px;
            overflow-y: auto;
            position: relative;
            z-index: 2;
            box-shadow: 6px 0 40px rgba(0,0,0,0.18);
        }
        /* Subtle top accent bar */
        .form-panel::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 4px;
            background: linear-gradient(90deg, #0ea5e9, #6366f1, #0ea5e9);
            background-size: 200% 100%;
            animation: shimmer 3s linear infinite;
        }
        @keyframes shimmer { 0% { background-position: 0% 0; } 100% { background-position: 200% 0; } }

        .brand {
            display: flex; align-items: center; gap: 12px;
            text-decoration: none; margin-bottom: 32px;
        }
        .brand-logo {
            display: flex; align-items: center; justify-content: center;
            color: #0f172a; font-size: 2rem;
        }
        .brand-name { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.8rem; color: #0f172a; letter-spacing: -0.5px; }

        .form-title { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.85rem; color: #0f172a; margin-bottom: 4px; line-height: 1.2; }
        .form-subtitle { font-size: 0.88rem; color: #64748b; margin-bottom: 22px; font-weight: 500; }
        .form-subtitle a { color: #0ea5e9; text-decoration: none; font-weight: 600; }
        .form-subtitle a:hover { text-decoration: underline; }

        /* Error banner */
        .error-banner {
            background: #fef2f2; border: 1px solid #fecaca;
            color: #dc2626; border-radius: 10px;
            padding: 10px 14px; font-size: 0.84rem;
            display: flex; align-items: center; gap: 8px;
            margin-bottom: 16px; font-weight: 500;
        }

        /* Social buttons */
        .social-row { display: flex; gap: 10px; margin-bottom: 14px; }
        .social-btn {
            flex: 1; border: 1.5px solid #e2e8f0; background: white;
            color: #334155; font-weight: 600; border-radius: 10px;
            padding: 10px 14px; display: flex; align-items: center;
            justify-content: center; gap: 8px; text-decoration: none;
            font-size: 0.84rem; transition: all 0.2s; cursor: pointer;
        }
        .social-btn:hover { background: #f8fafc; border-color: #94a3b8; color: #1e293b; transform: translateY(-1px); box-shadow: 0 4px 12px rgba(0,0,0,0.07); }
        .social-btn img { width: 16px; height: 16px; }

        .divider { display: flex; align-items: center; gap: 12px; color: #94a3b8; font-size: 0.78rem; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; margin: 14px 0; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: #e2e8f0; }

        .form-label { font-weight: 600; font-size: 0.84rem; color: #374151; margin-bottom: 6px; display: block; }
        .field-wrap { position: relative; }
        .form-control {
            width: 100%; padding: 11px 14px; border: 2px solid #e2e8f0;
            border-radius: 10px; font-size: 0.92rem; font-family: 'Inter', sans-serif;
            background: white; color: #1e293b; transition: all 0.2s; outline: none;
        }
        .form-control:focus { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14,165,233,0.12); }
        .form-control.is-invalid { border-color: #ef4444; }
        .eye-btn {
            position: absolute; right: 12px; top: 50%; transform: translateY(-50%);
            background: none; border: none; color: #94a3b8; cursor: pointer; padding: 0;
            font-size: 0.9rem; transition: color 0.2s;
        }
        .eye-btn:hover { color: #475569; }

        .btn-login {
            width: 100%; margin-top: 6px; padding: 12px;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 100%);
            border: none; border-radius: 10px; color: white;
            font-family: 'Outfit', sans-serif; font-weight: 700;
            font-size: 1rem; letter-spacing: 0.3px; cursor: pointer;
            transition: all 0.25s; position: relative; overflow: hidden;
        }
        .btn-login::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, #0284c7 0%, #4f46e5 100%);
            opacity: 0; transition: opacity 0.25s;
        }
        .btn-login:hover::before { opacity: 1; }
        .btn-login:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14,165,233,0.35); }
        .btn-login:active { transform: translateY(0); }
        .btn-login span { position: relative; z-index: 1; }

        /* Demo box */
        .demo-box {
            margin-top: auto; padding: 14px;
            background: linear-gradient(135deg, #f0f9ff, #eff6ff);
            border: 1px solid #bae6fd; border-radius: 12px;
        }
        .demo-box .demo-title { font-size: 0.77rem; font-weight: 700; color: #0369a1; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px; }
        .demo-btns { display: flex; flex-wrap: wrap; gap: 5px; }
        .demo-pill {
            background: white; border: 1px solid #bae6fd;
            color: #0369a1; font-size: 0.74rem; font-weight: 600;
            padding: 5px 10px; border-radius: 20px; cursor: pointer;
            transition: all 0.2s; display: flex; align-items: center; gap: 5px;
        }
        .demo-pill:hover { background: #0ea5e9; border-color: #0ea5e9; color: white; }

        /* ═══════════════════════════════════════
           RIGHT — HERO PANEL
        ═══════════════════════════════════════ */
        .hero-panel {
            flex: 1; position: relative; overflow: hidden;
            background: #030712;
            display: flex; align-items: center; justify-content: center;
        }

        /* Aurora layers */
        .aurora {
            position: absolute; border-radius: 50%;
            filter: blur(90px); pointer-events: none; animation: drift ease-in-out infinite;
        }
        .aurora-1 { width: 700px; height: 700px; top: -20%; left: -10%; background: radial-gradient(circle, rgba(14,165,233,0.5) 0%, transparent 70%); animation-duration: 20s; animation-delay: 0s; }
        .aurora-2 { width: 600px; height: 600px; bottom: -20%; right: -5%; background: radial-gradient(circle, rgba(99,102,241,0.55) 0%, transparent 70%); animation-duration: 25s; animation-delay: -8s; }
        .aurora-3 { width: 500px; height: 500px; top: 20%; right: 20%; background: radial-gradient(circle, rgba(16,185,129,0.3) 0%, transparent 70%); animation-duration: 30s; animation-delay: -15s; }
        .aurora-4 { width: 350px; height: 350px; bottom: 10%; left: 30%; background: radial-gradient(circle, rgba(139,92,246,0.35) 0%, transparent 70%); animation-duration: 22s; animation-delay: -5s; }
        @keyframes drift {
            0%, 100% { transform: translate(0, 0) scale(1); }
            33%       { transform: translate(60px, -80px) scale(1.12); }
            66%       { transform: translate(-50px, 50px) scale(0.9); }
        }

        /* Grid overlay */
        .grid-overlay {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(255,255,255,0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.03) 1px, transparent 1px);
            background-size: 60px 60px;
            pointer-events: none;
        }

        /* Canvas */
        #heroCanvas { position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0.5; pointer-events: none; }

        /* Hero content */
        .hero-content {
            position: relative; z-index: 10;
            text-align: center; max-width: 520px; padding: 0 40px;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(14,165,233,0.12); border: 1px solid rgba(14,165,233,0.3);
            color: #38bdf8; font-size: 0.75rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 6px 16px; border-radius: 30px; margin-bottom: 24px;
        }
        .hero-badge::before { content: ''; width: 6px; height: 6px; border-radius: 50%; background: #38bdf8; box-shadow: 0 0 8px #38bdf8; animation: pulse-dot 1.5s ease infinite; }
        @keyframes pulse-dot { 0%, 100% { opacity: 1; } 50% { opacity: 0.3; } }

        .hero-title {
            font-family: 'Outfit', sans-serif; font-weight: 800;
            font-size: clamp(2rem, 3.5vw, 3rem); line-height: 1.15;
            color: white; margin-bottom: 16px;
            text-shadow: 0 0 60px rgba(14,165,233,0.2);
        }
        .hero-title .gradient-word {
            background: linear-gradient(135deg, #38bdf8, #818cf8, #34d399);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .hero-desc { font-size: 0.98rem; color: rgba(255,255,255,0.65); line-height: 1.65; margin-bottom: 36px; font-family: 'Outfit', sans-serif; }

        /* Floating stat cards */
        .stats-row { display: flex; justify-content: center; gap: 14px; flex-wrap: wrap; margin-bottom: 36px; }
        .stat-card {
            background: rgba(255,255,255,0.06);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 16px; padding: 16px 22px;
            text-align: center; min-width: 120px;
            transition: transform 0.3s, box-shadow 0.3s;
            animation: float-card 6s ease-in-out infinite;
        }
        .stat-card:nth-child(2) { animation-delay: -2s; }
        .stat-card:nth-child(3) { animation-delay: -4s; }
        @keyframes float-card {
            0%, 100% { transform: translateY(0); }
            50%       { transform: translateY(-8px); }
        }
        .stat-card:hover { transform: translateY(-12px) scale(1.05); box-shadow: 0 20px 40px rgba(0,0,0,0.3); }
        .stat-num { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.6rem; color: white; line-height: 1; }
        .stat-num.blue   { background: linear-gradient(135deg, #38bdf8, #818cf8); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-num.green  { background: linear-gradient(135deg, #34d399, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-num.purple { background: linear-gradient(135deg, #a78bfa, #f472b6); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
        .stat-lbl { font-size: 0.72rem; color: rgba(255,255,255,0.5); font-weight: 600; margin-top: 4px; text-transform: uppercase; letter-spacing: 0.5px; }

        /* Feature pills */
        .feature-pills { display: flex; flex-wrap: wrap; justify-content: center; gap: 8px; }
        .pill {
            display: inline-flex; align-items: center; gap: 7px;
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.75); font-size: 0.8rem; font-weight: 500;
            padding: 7px 14px; border-radius: 30px;
            transition: all 0.2s;
        }
        .pill:hover { background: rgba(14,165,233,0.15); border-color: rgba(14,165,233,0.4); color: #38bdf8; }
        .pill i { font-size: 0.75rem; color: #38bdf8; }

        /* Bottom brand strip */
        .hero-bottom {
            position: absolute; bottom: 28px; left: 50%; transform: translateX(-50%);
            display: flex; align-items: center; gap: 8px; z-index: 10;
            color: rgba(255,255,255,0.3); font-size: 0.75rem; font-weight: 600;
            letter-spacing: 1px; text-transform: uppercase;
            white-space: nowrap;
        }
        .hero-bottom::before, .hero-bottom::after { content: ''; width: 40px; height: 1px; background: rgba(255,255,255,0.15); }

        @media (max-width: 900px) {
            .form-panel { width: 100%; min-width: 0; }
            .hero-panel { display: none; }
            body { overflow: auto; }
            .login-wrap { height: auto; min-height: 100vh; }
        }
    </style>
</head>
<body>
<div class="login-wrap">

    {{-- ══ FORM PANEL ══════════════════════════════════════════ --}}
    <div class="form-panel">
        <div style="position: absolute; top: 32px; right: 40px; z-index: 100;" data-theme="light">
            @include('partials.translator')
        </div>
        <a href="/" class="brand">
            <div class="brand-logo"><i class="fa-solid fa-graduation-cap"></i></div>
            <div class="brand-name">Nexus</div>
        </a>

        <h1 class="form-title">Welcome back</h1>
        <p class="form-subtitle">New here? <a href="{{ route('register') }}">Create an account →</a></p>

        @if($errors->any())
        <div class="error-banner">
            <i class="fa-solid fa-circle-exclamation"></i>
            {{ $errors->first() ?: 'Invalid email or password. Please try again.' }}
        </div>
        @endif

        {{-- Social --}}
        <div class="social-row">
            <a href="{{ route('social.redirect', 'google') }}" class="social-btn">
                <img src="https://upload.wikimedia.org/wikipedia/commons/c/c1/Google_%22G%22_logo.svg" alt="G">
                Google
            </a>
            <a href="{{ route('social.redirect', 'github') }}" class="social-btn">
                <i class="fa-brands fa-github" style="font-size:1rem;"></i>
                GitHub
            </a>
        </div>

        <div class="divider">or email & password</div>

        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <div class="field-wrap">
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror"
                        value="{{ old('email') }}" placeholder="you@institution.edu" required autofocus>
                </div>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="field-wrap">
                    <input type="password" name="password" id="password" class="form-control" placeholder="••••••••" required style="padding-right:42px;">
                    <button type="button" class="eye-btn" onclick="togglePwd()">
                        <i class="fa-solid fa-eye" id="eyeIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-login" id="loginBtn">
                <span><i class="fa-solid fa-arrow-right-to-bracket me-2"></i>Sign In</span>
            </button>
        </form>

        {{-- Demo Accounts --}}
        <div class="demo-box mt-4">
            <div class="demo-title"><i class="fa-solid fa-flask me-1"></i>Demo Accounts — password: <code>password</code></div>
            <div class="demo-btns">
                <button class="demo-pill" onclick="fillDemo('admin@example.com')">
                    <i class="fa-solid fa-shield-halved"></i> Admin
                </button>
                <button class="demo-pill" onclick="fillDemo('sme@example.com')">
                    <i class="fa-solid fa-chalkboard-user"></i> SME
                </button>
                <button class="demo-pill" onclick="fillDemo('institute@example.com')">
                    <i class="fa-solid fa-building-columns"></i> Institute
                </button>
            </div>
        </div>
    </div>

    {{-- ══ HERO PANEL ══════════════════════════════════════════ --}}
    <div class="hero-panel">
        <canvas id="heroCanvas"></canvas>
        <div class="aurora aurora-1"></div>
        <div class="aurora aurora-2"></div>
        <div class="aurora aurora-3"></div>
        <div class="aurora aurora-4"></div>
        <div class="grid-overlay"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <span>Live Platform</span>
            </div>

            <h2 class="hero-title">
                India's Unified<br>
                <span class="gradient-word">AICTE Curriculum</span><br>
                Network
            </h2>

            <p class="hero-desc">
                A single portal where AICTE-approved institutes discover, adopt, and get scored on model curricula crafted by India's leading Subject Matter Experts.
            </p>

            <div class="stats-row">
                <div class="stat-card">
                    <div class="stat-num blue" id="cnt-curricula">0</div>
                    <div class="stat-lbl">Curricula</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num green" id="cnt-institutes">0</div>
                    <div class="stat-lbl">Institutes</div>
                </div>
                <div class="stat-card">
                    <div class="stat-num purple" id="cnt-adoptions">0</div>
                    <div class="stat-lbl">Adoptions</div>
                </div>
            </div>

            <div class="feature-pills">
                <span class="pill"><i class="fa-solid fa-book-open-reader"></i>Model Curricula</span>
                <span class="pill"><i class="fa-solid fa-star"></i>SME Grading</span>
                <span class="pill"><i class="fa-solid fa-bell"></i>Real-time Alerts</span>
                <span class="pill"><i class="fa-solid fa-chart-line"></i>Analytics</span>
                <span class="pill"><i class="fa-brands fa-google"></i>OAuth Login</span>
            </div>
        </div>

        <div class="hero-bottom">AICTE · INT221 · Nexus Portal · {{ date('Y') }}</div>
    </div>
</div>

<script>
/* ── Password toggle ── */
function togglePwd() {
    const p = document.getElementById('password');
    const i = document.getElementById('eyeIcon');
    if (p.type === 'password') { p.type = 'text'; i.className = 'fa-solid fa-eye-slash'; }
    else                       { p.type = 'password'; i.className = 'fa-solid fa-eye'; }
}

/* ── Demo account quick-fill ── */
function fillDemo(email) {
    document.getElementById('email').value    = email;
    document.getElementById('password').value = 'password';
    // Pulse the button
    const btn = document.getElementById('loginBtn');
    btn.style.boxShadow = '0 0 0 6px rgba(14,165,233,0.3)';
    setTimeout(() => btn.style.boxShadow = '', 600);
}

/* ── Animated count-up for stat numbers ── */
function countUp(el, target, suffix = '') {
    let start = 0; const dur = 2000; const step = 16;
    const inc = target / (dur / step);
    const t = setInterval(() => {
        start = Math.min(start + inc, target);
        el.textContent = Math.floor(start) + suffix;
        if (start >= target) clearInterval(t);
    }, step);
}
window.addEventListener('load', () => {
    setTimeout(() => {
        countUp(document.getElementById('cnt-curricula'),  23);
        countUp(document.getElementById('cnt-institutes'), 48);
        countUp(document.getElementById('cnt-adoptions'),  137);
    }, 400);
});

/* ── Particle constellation on canvas ── */
(function () {
    const canvas = document.getElementById('heroCanvas');
    const ctx    = canvas.getContext('2d');
    function resize() { canvas.width = canvas.parentElement.clientWidth; canvas.height = canvas.parentElement.clientHeight; }
    window.addEventListener('resize', resize); resize();

    const N = 80;
    const pts = Array.from({ length: N }, () => ({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        vx: (Math.random() - 0.5) * 0.5,
        vy: (Math.random() - 0.5) * 0.5,
        r: Math.random() * 1.8 + 0.6,
    }));

    (function loop() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        pts.forEach(p => {
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > canvas.width)  p.vx *= -1;
            if (p.y < 0 || p.y > canvas.height)  p.vy *= -1;
            ctx.beginPath();
            ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(148,218,255,0.8)';
            ctx.fill();
        });
        for (let i = 0; i < pts.length; i++) {
            for (let j = i + 1; j < pts.length; j++) {
                const dx = pts[i].x - pts[j].x, dy = pts[i].y - pts[j].y;
                const d  = Math.sqrt(dx * dx + dy * dy);
                if (d < 130) {
                    ctx.beginPath();
                    ctx.strokeStyle = `rgba(148,218,255,${0.22 * (1 - d / 130)})`;
                    ctx.lineWidth = 0.7;
                    ctx.moveTo(pts[i].x, pts[i].y);
                    ctx.lineTo(pts[j].x, pts[j].y);
                    ctx.stroke();
                }
            }
        }
        requestAnimationFrame(loop);
    })();
})();

/* ── Login button loading state ── */
document.getElementById('loginForm').addEventListener('submit', function() {
    const btn = document.getElementById('loginBtn');
    btn.innerHTML = '<span><i class="fa-solid fa-spinner fa-spin me-2"></i>Authenticating…</span>';
    btn.disabled = true;
});
</script>
</body>
</html>
