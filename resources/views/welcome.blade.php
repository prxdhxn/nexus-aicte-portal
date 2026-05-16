<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nexus — AICTE Curriculum Portal</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700;800;900&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root { --accent: #0ea5e9; --accent2: #6366f1; --green: #10b981; }
        html { scroll-behavior: smooth; }
        body { font-family: 'Inter', sans-serif; background: #030712; color: white; overflow-x: hidden; }

        /* ── CANVAS ─────────────────────────────── */
        #bg-canvas { position: fixed; inset: 0; z-index: 0; pointer-events: none; }

        /* ── NAV ─────────────────────────────────── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            display: flex; align-items: center; justify-content: space-between;
            padding: 16px 60px;
            background: rgba(3,7,18,0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            transition: all 0.3s;
        }
        .nav-brand {
            display: flex; align-items: center; gap: 12px;
            font-family: 'Outfit', sans-serif; font-weight: 800;
            font-size: 1.6rem; color: white; text-decoration: none;
        }
        .nav-brand-icon {
            display: flex; align-items: center; justify-content: center;
            font-size: 1.8rem; color: white;
        }
        .nav-links { display: flex; align-items: center; gap: 8px; }
        .nav-link { color: rgba(255,255,255,0.65); text-decoration: none; font-size: 0.88rem; font-weight: 500; padding: 8px 16px; border-radius: 8px; transition: all 0.2s; }
        .nav-link:hover { color: white; background: rgba(255,255,255,0.07); }
        .nav-btn {
            padding: 9px 22px; border-radius: 10px; font-weight: 700;
            font-size: 0.88rem; text-decoration: none; transition: all 0.25s;
            font-family: 'Outfit', sans-serif;
        }
        .nav-btn-outline { border: 1.5px solid rgba(255,255,255,0.2); color: white; }
        .nav-btn-outline:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.05); color: white; }
        .nav-btn-fill {
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            color: white; border: none;
            box-shadow: 0 4px 14px rgba(14,165,233,0.35);
        }
        .nav-btn-fill:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(14,165,233,0.5); color: white; }

        /* ── HERO ─────────────────────────────────── */
        .hero {
            position: relative; z-index: 1; min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            text-align: center; padding: 120px 20px 80px;
        }
        /* Aurora blobs */
        .blob { position: absolute; border-radius: 50%; filter: blur(120px); pointer-events: none; animation: blob-drift ease-in-out infinite; }
        .blob-1 { width: 800px; height: 800px; top: -200px; left: -200px; background: radial-gradient(circle, rgba(14,165,233,0.35) 0%, transparent 65%); animation-duration: 22s; }
        .blob-2 { width: 700px; height: 700px; bottom: -150px; right: -100px; background: radial-gradient(circle, rgba(99,102,241,0.4) 0%, transparent 65%); animation-duration: 28s; animation-delay: -10s; }
        .blob-3 { width: 550px; height: 550px; top: 30%; left: 40%; background: radial-gradient(circle, rgba(16,185,129,0.25) 0%, transparent 65%); animation-duration: 35s; animation-delay: -18s; }
        @keyframes blob-drift {
            0%,100% { transform: translate(0,0) scale(1); }
            33%      { transform: translate(80px,-100px) scale(1.1); }
            66%      { transform: translate(-60px,70px) scale(0.92); }
        }

        .hero-inner { position: relative; z-index: 5; max-width: 800px; }

        .hero-eyebrow {
            display: inline-flex; align-items: center; gap: 8px;
            border: 1px solid rgba(14,165,233,0.35);
            background: rgba(14,165,233,0.08);
            color: #38bdf8; font-size: 0.78rem; font-weight: 700;
            letter-spacing: 1.5px; text-transform: uppercase;
            padding: 7px 18px; border-radius: 30px; margin-bottom: 28px;
        }
        .hero-eyebrow span.dot { width: 6px; height: 6px; border-radius: 50%; background: #38bdf8; box-shadow: 0 0 8px #38bdf8; animation: blink 1.5s ease infinite; }
        @keyframes blink { 0%,100% { opacity: 1; } 50% { opacity: 0.2; } }

        .hero-title {
            font-family: 'Outfit', sans-serif; font-weight: 900;
            font-size: clamp(2.8rem, 6vw, 5rem); line-height: 1.08;
            margin-bottom: 22px; letter-spacing: -1px;
        }
        .hero-title .line2 {
            background: linear-gradient(135deg, #38bdf8 0%, #818cf8 50%, #34d399 100%);
            -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
        }
        .hero-desc { font-size: 1.1rem; color: rgba(255,255,255,0.6); line-height: 1.7; max-width: 560px; margin: 0 auto 40px; }

        .hero-cta { display: flex; gap: 14px; justify-content: center; flex-wrap: wrap; margin-bottom: 56px; }
        .cta-primary {
            padding: 15px 36px; border-radius: 12px; font-family: 'Outfit', sans-serif;
            font-weight: 700; font-size: 1rem; text-decoration: none; color: white;
            background: linear-gradient(135deg, #0ea5e9, #6366f1);
            box-shadow: 0 8px 28px rgba(14,165,233,0.4);
            transition: all 0.25s; display: inline-flex; align-items: center; gap: 8px;
        }
        .cta-primary:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(14,165,233,0.55); color: white; }
        .cta-secondary {
            padding: 15px 36px; border-radius: 12px; font-family: 'Outfit', sans-serif;
            font-weight: 700; font-size: 1rem; text-decoration: none;
            border: 1.5px solid rgba(255,255,255,0.2); color: white;
            transition: all 0.25s; display: inline-flex; align-items: center; gap: 8px;
        }
        .cta-secondary:hover { border-color: rgba(255,255,255,0.5); background: rgba(255,255,255,0.05); color: white; }

        /* Floating stat pills */
        .hero-stats { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
        .stat-pill {
            background: rgba(255,255,255,0.05);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 14px; padding: 14px 24px;
            display: flex; align-items: center; gap: 12px;
            animation: float-stat 5s ease-in-out infinite;
        }
        .stat-pill:nth-child(2) { animation-delay: -1.6s; }
        .stat-pill:nth-child(3) { animation-delay: -3.2s; }
        @keyframes float-stat { 0%,100% { transform: translateY(0); } 50% { transform: translateY(-7px); } }
        .stat-icon { width: 36px; height: 36px; border-radius: 9px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; }
        .si-blue   { background: rgba(14,165,233,0.2); color: #38bdf8; }
        .si-green  { background: rgba(16,185,129,0.2); color: #34d399; }
        .si-purple { background: rgba(139,92,246,0.2); color: #a78bfa; }
        .stat-val { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.4rem; color: white; line-height: 1; }
        .stat-label { font-size: 0.72rem; color: rgba(255,255,255,0.45); font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }

        /* ── FEATURES SECTION ──────────────────── */
        .features { position: relative; z-index: 1; padding: 100px 60px; max-width: 1200px; margin: 0 auto; }
        .section-label { text-align: center; font-size: 0.78rem; color: var(--accent); font-weight: 700; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 14px; }
        .section-title { text-align: center; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: clamp(1.8rem, 3vw, 2.6rem); margin-bottom: 14px; }
        .section-desc { text-align: center; color: rgba(255,255,255,0.5); font-size: 0.98rem; max-width: 500px; margin: 0 auto 60px; line-height: 1.65; }

        .features-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .feat-card {
            background: rgba(255,255,255,0.04);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px; padding: 28px 26px;
            transition: all 0.35s; position: relative; overflow: hidden;
        }
        .feat-card::before {
            content: ''; position: absolute; inset: 0; border-radius: 20px;
            background: linear-gradient(135deg, rgba(14,165,233,0.08), rgba(99,102,241,0.06));
            opacity: 0; transition: opacity 0.35s;
        }
        .feat-card:hover { border-color: rgba(14,165,233,0.3); transform: translateY(-6px); box-shadow: 0 20px 50px rgba(0,0,0,0.35); }
        .feat-card:hover::before { opacity: 1; }
        .feat-icon { width: 48px; height: 48px; border-radius: 13px; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; margin-bottom: 18px; position: relative; z-index: 1; }
        .feat-card h3 { font-family: 'Outfit', sans-serif; font-weight: 700; font-size: 1.1rem; margin-bottom: 10px; position: relative; z-index: 1; }
        .feat-card p { font-size: 0.87rem; color: rgba(255,255,255,0.5); line-height: 1.65; position: relative; z-index: 1; }

        /* ── ROLES SECTION ─────────────────────── */
        .roles-section { position: relative; z-index: 1; padding: 80px 60px; max-width: 1200px; margin: 0 auto; }
        .roles-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
        .role-card {
            border-radius: 22px; padding: 36px 30px; text-align: center;
            position: relative; overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        .role-card:hover { transform: translateY(-8px); box-shadow: 0 30px 60px rgba(0,0,0,0.4); }
        .role-card.admin  { background: linear-gradient(160deg, rgba(14,165,233,0.15), rgba(99,102,241,0.12)); border: 1px solid rgba(14,165,233,0.2); }
        .role-card.sme    { background: linear-gradient(160deg, rgba(16,185,129,0.15), rgba(6,182,212,0.12)); border: 1px solid rgba(16,185,129,0.2); }
        .role-card.inst   { background: linear-gradient(160deg, rgba(139,92,246,0.15), rgba(244,114,182,0.1)); border: 1px solid rgba(139,92,246,0.2); }
        .role-icon { font-size: 2.5rem; margin-bottom: 18px; }
        .role-card h3 { font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1.25rem; margin-bottom: 10px; }
        .role-card p { font-size: 0.87rem; color: rgba(255,255,255,0.55); line-height: 1.6; margin-bottom: 20px; }
        .role-tag { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 0.73rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; }
        .tag-admin  { background: rgba(14,165,233,0.2); color: #38bdf8; }
        .tag-sme    { background: rgba(16,185,129,0.2); color: #34d399; }
        .tag-inst   { background: rgba(139,92,246,0.2); color: #a78bfa; }

        /* ── CTA BANNER ────────────────────────── */
        .cta-banner {
            position: relative; z-index: 1; margin: 0 60px 100px;
            border-radius: 28px; padding: 70px 60px;
            background: linear-gradient(135deg, #0ea5e9 0%, #6366f1 50%, #8b5cf6 100%);
            text-align: center; overflow: hidden;
        }
        .cta-banner::before {
            content: ''; position: absolute; inset: 0;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='30' cy='30' r='1'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            pointer-events: none;
        }
        .cta-banner h2 { font-family: 'Outfit', sans-serif; font-weight: 900; font-size: clamp(1.8rem, 3vw, 2.8rem); margin-bottom: 14px; position: relative; }
        .cta-banner p { font-size: 1rem; opacity: 0.85; margin-bottom: 36px; position: relative; max-width: 480px; margin-left: auto; margin-right: auto; }
        .cta-white { padding: 15px 40px; border-radius: 12px; font-family: 'Outfit', sans-serif; font-weight: 800; font-size: 1rem; text-decoration: none; background: white; color: #4f46e5; display: inline-flex; align-items: center; gap: 8px; transition: all 0.25s; position: relative; }
        .cta-white:hover { transform: translateY(-3px); box-shadow: 0 14px 40px rgba(0,0,0,0.3); color: #4f46e5; }

        /* ── FOOTER ────────────────────────────── */
        footer {
            position: relative; z-index: 1;
            border-top: 1px solid rgba(255,255,255,0.07);
            padding: 28px 60px; display: flex;
            align-items: center; justify-content: space-between;
            color: rgba(255,255,255,0.3); font-size: 0.82rem;
        }

        @media (max-width: 900px) {
            nav { padding: 14px 20px; }
            .features, .roles-section { padding: 60px 20px; }
            .features-grid, .roles-grid { grid-template-columns: 1fr; }
            .cta-banner { margin: 0 20px 60px; padding: 50px 24px; }
            footer { flex-direction: column; gap: 8px; text-align: center; padding: 20px; }
        }
    </style>
</head>
<body>

<canvas id="bg-canvas"></canvas>

{{-- NAV --}}
<nav>
    <a href="/" class="nav-brand">
        <div class="nav-brand-icon"><i class="fa-solid fa-graduation-cap"></i></div>
        Nexus
    </a>
    <div class="nav-links">
        @include('partials.translator')
        <a href="#features" class="nav-link">Features</a>
        <a href="#roles" class="nav-link">Roles</a>
        <a href="{{ route('login') }}" class="nav-btn nav-btn-outline">Sign In</a>
        <a href="{{ route('register') }}" class="nav-btn nav-btn-fill">Get Started</a>
    </div>
</nav>

{{-- HERO --}}
<section class="hero">
    <div class="blob blob-1"></div>
    <div class="blob blob-2"></div>
    <div class="blob blob-3"></div>
    <div class="hero-inner">
        <div class="hero-eyebrow"><span class="dot"></span> AICTE Approved · INT221 Project</div>

        <h1 class="hero-title">
            One Portal for<br>
            <span class="line2">India's Model Curricula</span>
        </h1>
        <p class="hero-desc">Nexus connects AICTE-approved institutes with expert-crafted model curricula. Adopt, submit, and get scored — all in one place.</p>

        <div class="hero-cta">
            <a href="{{ route('register') }}" class="cta-primary">
                <i class="fa-solid fa-rocket"></i> Start for Free
            </a>
            <a href="{{ route('login') }}" class="cta-secondary">
                <i class="fa-solid fa-arrow-right-to-bracket"></i> Sign In
            </a>
        </div>

        <div class="hero-stats">
            <div class="stat-pill">
                <div class="stat-icon si-blue"><i class="fa-solid fa-book-open"></i></div>
                <div><div class="stat-val">23+</div><div class="stat-label">Curricula</div></div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon si-green"><i class="fa-solid fa-building-columns"></i></div>
                <div><div class="stat-val">48+</div><div class="stat-label">Institutes</div></div>
            </div>
            <div class="stat-pill">
                <div class="stat-icon si-purple"><i class="fa-solid fa-star"></i></div>
                <div><div class="stat-val">137+</div><div class="stat-label">Adoptions</div></div>
            </div>
        </div>
    </div>
</section>

{{-- FEATURES --}}
<section class="features" id="features">
    <div class="section-label">What's Inside</div>
    <h2 class="section-title">Everything you need</h2>
    <p class="section-desc">From curriculum creation to adoption tracking — every workflow is covered.</p>

    <div class="features-grid">
        @php $feats = [
            ['bg'=>'rgba(14,165,233,0.15)','color'=>'#38bdf8','icon'=>'fa-book-open-reader','title'=>'Model Curricula','desc'=>'Browse AICTE-standardized curricula across disciplines, created by certified Subject Matter Experts.'],
            ['bg'=>'rgba(16,185,129,0.15)','color'=>'#34d399','icon'=>'fa-file-arrow-up','title'=>'One-Click Adoption','desc'=>'Upload your implementation plan with drag-and-drop. PDF, DOC, DOCX & ZIP supported up to 10MB.'],
            ['bg'=>'rgba(99,102,241,0.15)','color'=>'#818cf8','icon'=>'fa-star-half-stroke','title'=>'SME Grading','desc'=>'Subject Matter Experts review and score each adoption on a 100-point scale with written feedback.'],
            ['bg'=>'rgba(245,158,11,0.15)','color'=>'#fbbf24','icon'=>'fa-bell','title'=>'Real-time Alerts','desc'=>'Instant in-app notifications when curricula are published or your submission is graded.'],
            ['bg'=>'rgba(139,92,246,0.15)','color'=>'#a78bfa','icon'=>'fa-chart-line','title'=>'Analytics Dashboard','desc'=>'Admins track platform-wide adoption trends, score distributions, and institute engagement.'],
            ['bg'=>'rgba(244,114,182,0.15)','color'=>'#f472b6','icon'=>'fa-shield-halved','title'=>'Role-Based Security','desc'=>'Three distinct roles — Admin, SME, Institute — each with tightly scoped permissions and middleware.'],
        ]; @endphp
        @foreach($feats as $f)
        <div class="feat-card">
            <div class="feat-icon" style="background:{{ $f['bg'] }};color:{{ $f['color'] }};"><i class="fa-solid {{ $f['icon'] }}"></i></div>
            <h3>{{ $f['title'] }}</h3>
            <p>{{ $f['desc'] }}</p>
        </div>
        @endforeach
    </div>
</section>

{{-- ROLES --}}
<section class="roles-section" id="roles">
    <div class="section-label">Who Uses Nexus</div>
    <h2 class="section-title">Built for every role</h2>
    <p class="section-desc">Three distinct roles, one unified portal.</p>
    <div class="roles-grid">
        <div class="role-card admin">
            <div class="role-icon">🛡️</div>
            <h3>Administrator</h3>
            <p>Full platform control — manage users, view analytics, oversee all curricula and adoptions across every institute.</p>
            <span class="role-tag tag-admin">Full Access</span>
        </div>
        <div class="role-card sme">
            <div class="role-icon">🎓</div>
            <h3>Subject Matter Expert</h3>
            <p>Create and publish model curricula, review institute submissions, and provide scored feedback and guidance.</p>
            <span class="role-tag tag-sme">Creator & Reviewer</span>
        </div>
        <div class="role-card inst">
            <div class="role-icon">🏛️</div>
            <h3>Institute</h3>
            <p>Discover curricula, submit your adoption plan, track your scores, and engage in the discussion forum.</p>
            <span class="role-tag tag-inst">Adopter</span>
        </div>
    </div>
</section>

{{-- CTA BANNER --}}
<div class="cta-banner">
    <h2>Ready to join the network?</h2>
    <p>Create your free institute account and start adopting AICTE model curricula today.</p>
    <a href="{{ route('register') }}" class="cta-white">
        <i class="fa-solid fa-rocket"></i> Create Free Account
    </a>
</div>

<footer>
    <span>© {{ date('Y') }} Nexus — AICTE Curriculum Portal. INT221 Project.</span>
    <span>Built with Laravel 12 · MongoDB · Bootstrap 5</span>
</footer>

<script>
/* Particle canvas background */
(function () {
    const c = document.getElementById('bg-canvas'), ctx = c.getContext('2d');
    function resize() { c.width = window.innerWidth; c.height = window.innerHeight; }
    window.addEventListener('resize', resize); resize();
    const pts = Array.from({ length: 60 }, () => ({
        x: Math.random() * c.width, y: Math.random() * c.height,
        vx: (Math.random() - 0.5) * 0.3, vy: (Math.random() - 0.5) * 0.3,
        r: Math.random() * 1.4 + 0.4,
    }));
    (function loop() {
        ctx.clearRect(0, 0, c.width, c.height);
        pts.forEach(p => {
            p.x += p.vx; p.y += p.vy;
            if (p.x < 0 || p.x > c.width) p.vx *= -1;
            if (p.y < 0 || p.y > c.height) p.vy *= -1;
            ctx.beginPath(); ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
            ctx.fillStyle = 'rgba(148,218,255,0.4)'; ctx.fill();
        });
        for (let i = 0; i < pts.length; i++)
            for (let j = i + 1; j < pts.length; j++) {
                const d = Math.hypot(pts[i].x - pts[j].x, pts[i].y - pts[j].y);
                if (d < 110) { ctx.beginPath(); ctx.strokeStyle = `rgba(148,218,255,${0.12 * (1 - d/110)})`; ctx.lineWidth = 0.5; ctx.moveTo(pts[i].x, pts[i].y); ctx.lineTo(pts[j].x, pts[j].y); ctx.stroke(); }
            }
        requestAnimationFrame(loop);
    })();
})();

/* Smooth scroll for anchor links */
document.querySelectorAll('a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
        e.preventDefault();
        document.querySelector(a.getAttribute('href'))?.scrollIntoView({ behavior: 'smooth' });
    });
});

/* Nav shadow on scroll */
window.addEventListener('scroll', () => {
    document.querySelector('nav').style.boxShadow = window.scrollY > 20 ? '0 8px 32px rgba(0,0,0,0.4)' : '';
});
</script>
</body>
</html>
