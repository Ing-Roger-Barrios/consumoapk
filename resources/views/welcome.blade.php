<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>CIO — Control Inteligente de Obras</title>
<meta name="description" content="Plataforma de gestión de obras. Presupuestos, mano de obra, materiales y libro contable en un solo sistema.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --orange: #F05A00;
  --orange-dim: #C04500;
  --orange-glow: rgba(240,90,0,0.15);
  --dark: #0A0B0D;
  --dark2: #0F1114;
  --dark3: #141720;
  --steel: #1E2230;
  --steel2: #252A3A;
  --line: rgba(255,255,255,0.06);
  --text: #E8EAF0;
  --muted: #6B7280;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { scroll-behavior: smooth; }
body {
  background: var(--dark);
  color: var(--text);
  font-family: 'Barlow', sans-serif;
  font-weight: 300;
  overflow-x: hidden;
}

/* ── BLUEPRINT BG ── */
.blueprint-bg {
  position: fixed; inset: 0; pointer-events: none; z-index: 0;
  background-image:
    linear-gradient(rgba(59,130,246,0.04) 1px, transparent 1px),
    linear-gradient(90deg, rgba(59,130,246,0.04) 1px, transparent 1px),
    linear-gradient(rgba(59,130,246,0.015) 1px, transparent 1px),
    linear-gradient(90deg, rgba(59,130,246,0.015) 1px, transparent 1px);
  background-size: 80px 80px, 80px 80px, 20px 20px, 20px 20px;
}

/* ── NAV ── */
nav {
  position: fixed; top: 0; left: 0; right: 0; z-index: 100;
  padding: 18px 48px;
  display: flex; align-items: center; justify-content: space-between;
  background: linear-gradient(to bottom, rgba(10,11,13,0.97) 60%, transparent);
  backdrop-filter: blur(10px);
  border-bottom: 1px solid rgba(255,255,255,0.04);
}
.logo { display: flex; align-items: center; gap: 12px; text-decoration: none; }
.logo-mark {
  width: 36px; height: 36px; background: var(--orange);
  display: flex; align-items: center; justify-content: center;
  clip-path: polygon(0 0, 85% 0, 100% 15%, 100% 100%, 15% 100%, 0 85%);
  font-family: 'Barlow Condensed', sans-serif;
  font-weight: 800; font-size: 13px; color: white; letter-spacing: 0.05em;
}
.logo-text {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 700;
  font-size: 17px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text);
}
.logo-text span { color: var(--orange); }

.nav-center { display: flex; align-items: center; gap: 32px; list-style: none; }
.nav-center a {
  font-family: 'Barlow Condensed', sans-serif; font-size: 12px; font-weight: 600;
  letter-spacing: 0.15em; text-transform: uppercase; text-decoration: none;
  color: var(--muted); transition: color 0.2s;
}
.nav-center a:hover { color: var(--text); }

.nav-right { display: flex; align-items: center; gap: 12px; }

.btn-nav-primary {
  font-family: 'Barlow Condensed', sans-serif; font-size: 12px; font-weight: 700;
  letter-spacing: 0.12em; text-transform: uppercase; text-decoration: none;
  color: white; background: var(--orange); padding: 8px 22px;
  clip-path: polygon(0 0, 90% 0, 100% 30%, 100% 100%, 10% 100%, 0 70%);
  transition: background 0.2s;
}
.btn-nav-primary:hover { background: var(--orange-dim); }

.btn-nav-dashboard {
  font-family: 'Barlow Condensed', sans-serif; font-size: 12px; font-weight: 700;
  letter-spacing: 0.12em; text-transform: uppercase; text-decoration: none;
  color: var(--orange); border: 1px solid rgba(240,90,0,0.4); padding: 7px 18px;
  transition: all 0.2s;
}
.btn-nav-dashboard:hover { background: rgba(240,90,0,0.1); color: white; }

/* ── HERO ── */
.hero {
  position: relative; min-height: 100vh;
  display: flex; align-items: center;
  padding: 120px 48px 100px; overflow: hidden;
}
.hero-content { position: relative; z-index: 2; max-width: 720px; }

.hero-eyebrow {
  font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500;
  letter-spacing: 0.2em; color: var(--orange); text-transform: uppercase;
  margin-bottom: 24px; display: flex; align-items: center; gap: 10px;
  opacity: 0; animation: fadeUp 0.6s ease forwards 0.2s;
}
.hero-eyebrow::before { content: ''; width: 32px; height: 1px; background: var(--orange); }

.hero-title {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 800;
  font-size: clamp(60px, 8.5vw, 108px);
  line-height: 0.9; letter-spacing: -0.01em; text-transform: uppercase;
  margin-bottom: 32px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.35s;
}
.hero-title .line1 { display: block; color: var(--text); }
.hero-title .line2 { display: block; color: var(--orange); }
.hero-title .line3 { display: block; color: transparent; -webkit-text-stroke: 1.5px rgba(255,255,255,0.15); }

.hero-desc {
  font-size: 17px; font-weight: 300; line-height: 1.75; color: #9CA3AF;
  max-width: 500px; margin-bottom: 44px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.5s;
}

.hero-actions {
  display: flex; align-items: center; gap: 20px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.65s;
}
.btn-primary {
  display: inline-flex; align-items: center; gap: 10px;
  background: var(--orange); color: white; text-decoration: none;
  font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 14px;
  letter-spacing: 0.12em; text-transform: uppercase; padding: 15px 32px;
  clip-path: polygon(0 0, 92% 0, 100% 25%, 100% 100%, 8% 100%, 0 75%);
  transition: background 0.2s, transform 0.2s;
}
.btn-primary:hover { background: #D94F00; transform: translateY(-2px); }

.btn-secondary {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 600; font-size: 13px;
  letter-spacing: 0.12em; text-transform: uppercase; color: var(--muted);
  text-decoration: none; display: flex; align-items: center; gap: 8px; transition: color 0.2s;
}
.btn-secondary:hover { color: var(--text); }

/* Hero deco */
.hero-deco {
  position: absolute; right: -40px; top: 50%; transform: translateY(-50%);
  width: 580px; height: 580px; pointer-events: none;
  opacity: 0; animation: fadeIn 1.4s ease forwards 0.9s;
}
.hero-deco-ring { position: absolute; inset: 0; border-radius: 50%; border: 1px solid rgba(240,90,0,0.1); animation: spin 35s linear infinite; }
.hero-deco-ring:nth-child(2) { inset: 45px; border-color: rgba(240,90,0,0.07); animation-duration: 22s; animation-direction: reverse; }
.hero-deco-ring:nth-child(3) { inset: 110px; border-color: rgba(59,130,246,0.08); animation-duration: 16s; }
.hero-deco-core {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
  width: 158px; height: 158px;
  background: radial-gradient(circle, rgba(240,90,0,0.18), transparent 70%);
  border: 1px solid rgba(240,90,0,0.28);
  clip-path: polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%, 0 25%);
  display: flex; align-items: center; justify-content: center;
  font-family: 'Barlow Condensed', sans-serif; font-size: 44px; font-weight: 800; color: var(--orange);
}

/* Hero stats */
.hero-stats {
  position: absolute; bottom: 44px; left: 48px; right: 48px;
  display: flex; gap: 40px; align-items: center;
  opacity: 0; animation: fadeUp 0.7s ease forwards 1s;
  border-top: 1px solid var(--line); padding-top: 28px;
}
.stat-num { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 30px; color: var(--orange); line-height: 1; }
.stat-label { font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 0.12em; color: var(--muted); text-transform: uppercase; margin-top: 4px; }
.stat-sep { width: 1px; height: 36px; background: var(--line); flex-shrink: 0; }
.live-dot { width: 6px; height: 6px; background: var(--orange); border-radius: 50%; display: inline-block; animation: pulse-dot 1.6s ease infinite; }

/* ── SECTIONS ── */
section { position: relative; z-index: 1; }
.container { max-width: 1200px; margin: 0 auto; }

.section-label {
  font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 500;
  letter-spacing: 0.25em; color: var(--orange); text-transform: uppercase;
  display: flex; align-items: center; gap: 10px; margin-bottom: 16px;
}
.section-label::after { content: ''; max-width: 60px; height: 1px; background: var(--orange); flex: 1; }

.section-title {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 800;
  font-size: clamp(36px, 5vw, 58px); line-height: 1; text-transform: uppercase; letter-spacing: -0.01em;
}

/* ── FEATURES ── */
.features { padding: 100px 48px; }
.features-header { margin-bottom: 56px; max-width: 560px; }
.features-grid { display: grid; grid-template-columns: repeat(3,1fr); gap: 2px; background: var(--line); }
.feature-card { background: var(--dark2); padding: 40px 36px; position: relative; overflow: hidden; transition: background 0.3s; }
.feature-card:hover { background: var(--dark3); }
.feature-card::before { content: ''; position: absolute; top: 0; left: 0; width: 3px; height: 0; background: var(--orange); transition: height 0.4s ease; }
.feature-card:hover::before { height: 100%; }
.feature-num { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 64px; line-height: 1; color: var(--steel2); position: absolute; top: 18px; right: 22px; transition: color 0.3s; }
.feature-card:hover .feature-num { color: var(--steel); }
.feature-icon { width: 44px; height: 44px; background: var(--orange-glow); border: 1px solid rgba(240,90,0,0.22); display: flex; align-items: center; justify-content: center; font-size: 20px; margin-bottom: 24px; clip-path: polygon(0 0, 80% 0, 100% 20%, 100% 100%, 20% 100%, 0 80%); }
.feature-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 19px; letter-spacing: 0.04em; text-transform: uppercase; color: var(--text); margin-bottom: 12px; }
.feature-desc { font-size: 14px; font-weight: 300; line-height: 1.75; color: var(--muted); }

/* ── MODULES ── */
.modules-section { background: var(--dark2); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 100px 48px; overflow: hidden; }
.modules-inner { display: grid; grid-template-columns: 1fr 1fr; gap: 80px; align-items: center; }
.modules-meta { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--muted); letter-spacing: 0.15em; text-transform: uppercase; margin-bottom: 10px; padding-left: 20px; }
.module-list { display: flex; flex-direction: column; gap: 2px; }
.module-item { display: flex; align-items: center; gap: 14px; padding: 15px 20px; background: var(--dark3); border-left: 3px solid transparent; cursor: pointer; transition: all 0.25s; position: relative; }
.module-item.active, .module-item:hover { background: var(--steel); border-left-color: var(--orange); }
.module-code { font-family: 'JetBrains Mono', monospace; font-size: 11px; font-weight: 500; color: var(--orange); min-width: 32px; }
.module-name { font-family: 'Barlow Condensed', sans-serif; font-weight: 600; font-size: 14px; letter-spacing: 0.05em; text-transform: uppercase; color: var(--text); }
.module-pct { margin-left: auto; font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--muted); }
.module-bar { position: absolute; bottom: 0; left: 0; height: 2px; background: var(--orange); opacity: 0.35; transition: width 0.9s ease; }
.modules-total { margin-top: 10px; padding: 15px 20px; background: var(--dark); border-top: 2px solid var(--orange); display: flex; justify-content: space-between; align-items: center; }
.modules-total-label { font-family: 'JetBrains Mono', monospace; font-size: 10px; color: var(--muted); letter-spacing: 0.1em; text-transform: uppercase; }
.modules-total-val { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 22px; color: var(--orange); }
.modules-desc { font-size: 16px; font-weight: 300; line-height: 1.8; color: var(--muted); margin-bottom: 32px; }
.modules-pills { display: flex; flex-wrap: wrap; gap: 8px; }
.pill { font-family: 'JetBrains Mono', monospace; font-size: 10px; padding: 5px 12px; border: 1px solid var(--steel2); color: var(--muted); background: var(--dark3); letter-spacing: 0.08em; }

/* ── WORKFLOW ── */
.workflow { padding: 100px 48px; }
.workflow-header { text-align: center; margin-bottom: 72px; }
.workflow-header .section-label { justify-content: center; }
.workflow-header .section-label::after { display: none; }
.workflow-header .section-label::before { content: ''; max-width: 60px; height: 1px; background: var(--orange); }
.workflow-steps { display: grid; grid-template-columns: repeat(5,1fr); position: relative; }
.workflow-steps::before { content: ''; position: absolute; top: 29px; left: 12%; right: 12%; height: 1px; background: linear-gradient(to right, transparent, var(--orange), var(--orange), transparent); opacity: 0.25; }
.workflow-step { display: flex; flex-direction: column; align-items: center; text-align: center; padding: 0 12px; }
.step-num { width: 58px; height: 58px; background: var(--dark2); border: 1px solid var(--steel2); display: flex; align-items: center; justify-content: center; font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 22px; color: var(--orange); margin-bottom: 20px; clip-path: polygon(15% 0%, 85% 0%, 100% 15%, 100% 85%, 85% 100%, 15% 100%, 0% 85%, 0% 15%); transition: all 0.3s; }
.workflow-step:hover .step-num { background: var(--orange); color: white; }
.step-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 14px; letter-spacing: 0.08em; text-transform: uppercase; color: var(--text); margin-bottom: 8px; }
.step-desc { font-size: 13px; font-weight: 300; color: var(--muted); line-height: 1.65; }

/* ── LABOR ── */
.labor-section { background: linear-gradient(135deg, var(--dark2) 0%, var(--dark3) 100%); border-top: 1px solid var(--line); border-bottom: 1px solid var(--line); padding: 100px 48px; }
.labor-header { margin-bottom: 48px; display: flex; justify-content: space-between; align-items: flex-end; }
.labor-desc { max-width: 480px; font-size: 15px; font-weight: 300; line-height: 1.75; color: var(--muted); }
.labor-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; }
.labor-card { background: var(--dark); padding: 40px; position: relative; overflow: hidden; }
.labor-card::after { content: ''; position: absolute; top: -40px; right: -40px; width: 120px; height: 120px; border: 1px solid var(--line); border-radius: 50%; }
.labor-badge { display: inline-flex; align-items: center; gap: 8px; font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.15em; text-transform: uppercase; padding: 5px 12px; margin-bottom: 24px; }
.badge-jornal { background: rgba(59,130,246,0.08); color: #3B82F6; border: 1px solid rgba(59,130,246,0.2); }
.badge-item   { background: rgba(240,90,0,0.08); color: var(--orange); border: 1px solid rgba(240,90,0,0.2); }
.labor-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 26px; text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 16px; }
.labor-features { list-style: none; display: flex; flex-direction: column; gap: 10px; }
.labor-features li { display: flex; align-items: flex-start; gap: 10px; font-size: 13.5px; font-weight: 300; color: #9CA3AF; line-height: 1.55; }
.labor-features li::before { content: '→'; color: var(--orange); font-size: 12px; margin-top: 2px; flex-shrink: 0; }

/* ── CTA ── */
.cta-section { padding: 130px 48px; text-align: center; position: relative; overflow: hidden; }
.cta-section::before { content: 'CIO'; position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: min(38vw,440px); color: transparent; -webkit-text-stroke: 1px rgba(255,255,255,0.022); line-height: 1; pointer-events: none; white-space: nowrap; }
.cta-inner { position: relative; z-index: 2; max-width: 620px; margin: 0 auto; }
.cta-title { font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: clamp(42px,5.5vw,68px); text-transform: uppercase; line-height: 1; margin-bottom: 20px; }
.cta-title span { color: var(--orange); }
.cta-sub { font-size: 16px; font-weight: 300; color: var(--muted); margin-bottom: 44px; line-height: 1.75; }
.cta-actions { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; align-items: center; }

/* ── FOOTER ── */
footer { background: var(--dark2); border-top: 1px solid var(--line); padding: 28px 48px; display: flex; align-items: center; justify-content: space-between; }
.footer-copy { font-family: 'JetBrains Mono', monospace; font-size: 11px; color: var(--muted); letter-spacing: 0.05em; }
.footer-tagline { font-family: 'Barlow Condensed', sans-serif; font-size: 13px; font-weight: 600; letter-spacing: 0.15em; text-transform: uppercase; color: var(--steel2); }

.divider { height: 1px; background: linear-gradient(to right, transparent, var(--orange), transparent); opacity: 0.25; margin: 0 48px; }

/* ── SCROLL REVEAL ── */
.reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.7s ease, transform 0.7s ease; }
.reveal.visible { opacity: 1; transform: translateY(0); }

/* ── KEYFRAMES ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(24px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes spin   { to{transform:rotate(360deg)} }
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.3} }

/* ── RESPONSIVE ── */
@media(max-width:960px){
  nav{padding:14px 20px;}
  .nav-center{display:none;}
  .hero{padding:100px 20px 90px;}
  .hero-deco{display:none;}
  .hero-stats{left:20px;right:20px;gap:16px;flex-wrap:wrap;}
  .features,.workflow,.labor-section,.cta-section{padding:64px 20px;}
  .modules-section{padding:64px 20px;}
  .features-grid,.labor-grid{grid-template-columns:1fr;}
  .modules-inner{grid-template-columns:1fr;gap:40px;}
  .workflow-steps{grid-template-columns:1fr 1fr;gap:32px;}
  .workflow-steps::before{display:none;}
  .labor-header{flex-direction:column;gap:16px;align-items:flex-start;}
  footer{flex-direction:column;gap:10px;text-align:center;padding:24px 20px;}
  .divider{margin:0 20px;}
}
</style>
</head>
<body>

<div class="blueprint-bg"></div>

{{-- ══ NAV ══ --}}
<nav>
  <a href="{{ url('/') }}" class="logo">
    <div class="logo-mark">CIO</div>
    <span class="logo-text">Control <span>Inteligente</span> de Obras</span>
  </a>

  <ul class="nav-center">
    <li><a href="#features">Módulos</a></li>
    <li><a href="#workflow">Flujo</a></li>
    <li><a href="#labor">Mano de Obra</a></li>
    <li><a href="#cta">Contacto</a></li>
  </ul>

  <div class="nav-right">
    @auth
      <a href="{{ url('/dashboard') }}" class="btn-nav-dashboard">Dashboard →</a>
    @else
      @if (Route::has('login'))
        <a href="{{ route('login') }}" class="btn-nav-primary">Iniciar Sesión</a>
      @endif
    @endauth
  </div>
</nav>

{{-- ══ HERO ══ --}}
<section class="hero">
  <div class="hero-content">
    <div class="hero-eyebrow">
      <span class="live-dot"></span>
      Plataforma de gestión para constructoras
    </div>
    <h1 class="hero-title">
      <span class="line1">Control</span>
      <span class="line2">Inteligente</span>
      <span class="line3">de Obras</span>
    </h1>
    <p class="hero-desc">
      Presupuestos, ejecución, mano de obra y finanzas de cada proyecto en un solo sistema. Diseñado para residentes y contratistas que necesitan precisión, no suposiciones.
    </p>
    <div class="hero-actions">
      @auth
        <a href="{{ url('/dashboard') }}" class="btn-primary"><span>Ir al Dashboard</span><span>→</span></a>
      @else
        @if (Route::has('login'))
          <a href="{{ route('login') }}" class="btn-primary"><span>Acceder al sistema</span><span>→</span></a>
        @endif
      @endauth
      <a href="#features" class="btn-secondary">Ver funciones ↓</a>
    </div>
  </div>

  <div class="hero-deco">
    <div class="hero-deco-ring"></div>
    <div class="hero-deco-ring"></div>
    <div class="hero-deco-ring"></div>
    <div class="hero-deco-core">CIO</div>
  </div>

  <div class="hero-stats">
    <div><div class="stat-num">6+</div><div class="stat-label">Módulos de control</div></div>
    <div class="stat-sep"></div>
    <div><div class="stat-num">100%</div><div class="stat-label">Trazabilidad</div></div>
    <div class="stat-sep"></div>
    <div><div class="stat-num">Real</div><div class="stat-label">Tiempo de datos</div></div>
    <div class="stat-sep"></div>
    <div><div class="stat-num">Multi</div><div class="stat-label">Proyecto y usuarios</div></div>
  </div>
</section>

<div class="divider"></div>

{{-- ══ FEATURES ══ --}}
<section class="features" id="features">
  <div class="container">
    <div class="features-header reveal">
      <div class="section-label">Sistema modular</div>
      <h2 class="section-title">Todo lo que una<br>obra necesita</h2>
    </div>
    <div class="features-grid">
      <div class="feature-card reveal">
        <div class="feature-num">01</div><div class="feature-icon">📐</div>
        <div class="feature-title">Presupuesto por Módulos</div>
        <p class="feature-desc">Importa tu planilla Excel y CIO crea automáticamente la estructura de módulos e ítems. Desde M01 Obras Preliminares hasta donde necesites.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:.08s">
        <div class="feature-num">02</div><div class="feature-icon">📊</div>
        <div class="feature-title">Libro Contable</div>
        <p class="feature-desc">Registra ingresos y egresos con comprobantes fotográficos. Planillas, materiales, equipo y gastos generales con saldo en tiempo real.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:.16s">
        <div class="feature-num">03</div><div class="feature-icon">🧱</div>
        <div class="feature-title">Materiales de Ejecución</div>
        <p class="feature-desc">Controla el avance real: presupuestado vs. ejecutado, con porcentaje por ítem y alertas de sobrecosto automáticas.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:.24s">
        <div class="feature-num">04</div><div class="feature-icon">👷</div>
        <div class="feature-title">Mano de Obra</div>
        <p class="feature-desc">Sistema dual: jornal semanal con asistencia y horas extra, o por ítem/módulo con verificación fotográfica de avance por contratista.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:.32s">
        <div class="feature-num">05</div><div class="feature-icon">⚙️</div>
        <div class="feature-title">Equipo y Maquinaria</div>
        <p class="feature-desc">Registra uso de equipos por jornada. Alquileres vs. equipo propio con costos reales de operación y tiempo de uso.</p>
      </div>
      <div class="feature-card reveal" style="transition-delay:.4s">
        <div class="feature-num">06</div><div class="feature-icon">📈</div>
        <div class="feature-title">Dashboard Comparativo</div>
        <p class="feature-desc">Vista consolidada presupuesto vs. ejecución por categoría, con barras de progreso, alertas de desvío y proyección de cierre.</p>
      </div>
    </div>
  </div>
</section>

<div class="divider"></div>

{{-- ══ MODULES ══ --}}
<section class="modules-section">
  <div class="container">
    <div class="modules-inner">
      <div class="reveal">
        <p class="modules-meta">proyecto_id: 001 — avance general: 67%</p>
        <div class="module-list">
          <div class="module-item active"><span class="module-code">M01</span><span class="module-name">Obras Preliminares</span><span class="module-pct">100%</span><div class="module-bar" style="width:100%"></div></div>
          <div class="module-item active"><span class="module-code">M02</span><span class="module-name">Obra Gruesa</span><span class="module-pct">92%</span><div class="module-bar" style="width:92%"></div></div>
          <div class="module-item"><span class="module-code">M03</span><span class="module-name">Obra Fina</span><span class="module-pct">45%</span><div class="module-bar" style="width:45%"></div></div>
          <div class="module-item"><span class="module-code">M04</span><span class="module-name">Inst. Eléctrica</span><span class="module-pct">30%</span><div class="module-bar" style="width:30%"></div></div>
          <div class="module-item"><span class="module-code">M05</span><span class="module-name">Inst. Hidrosanitaria</span><span class="module-pct">20%</span><div class="module-bar" style="width:20%"></div></div>
          <div class="module-item"><span class="module-code">M06</span><span class="module-name">Obras Finales</span><span class="module-pct">0%</span><div class="module-bar" style="width:0%"></div></div>
        </div>
        <div class="modules-total">
          <span class="modules-total-label">Total presupuestado</span>
          <span class="modules-total-val">Bs 92,581.25</span>
        </div>
      </div>

      <div class="reveal" style="transition-delay:.2s">
        <div class="section-label">Estructura de obra</div>
        <h2 class="section-title" style="margin-bottom:24px">Importa tu<br>presupuesto<br>en segundos</h2>
        <p class="modules-desc">Sube el Excel con el formato de tu planilla. CIO detecta automáticamente las filas de módulo con prefijo <code style="color:var(--orange);font-size:13px">&gt;</code> y crea toda la jerarquía. Sin reescribir nada.</p>
        <div class="modules-pills">
          <span class="pill">.xlsx compatible</span>
          <span class="pill">Detección automática</span>
          <span class="pill">Módulos + ítems</span>
          <span class="pill">Cantidades y unitarios</span>
          <span class="pill">Carga masiva</span>
        </div>
      </div>
    </div>
  </div>
</section>

{{-- ══ WORKFLOW ══ --}}
<section class="workflow" id="workflow">
  <div class="container">
    <div class="workflow-header reveal">
      <div class="section-label">Metodología</div>
      <h2 class="section-title">El flujo de trabajo</h2>
    </div>
    <div class="workflow-steps">
      <div class="workflow-step reveal"><div class="step-num">01</div><div class="step-title">Crea el proyecto</div><p class="step-desc">Registra cliente, ubicación y monto contratado. Asigna residentes.</p></div>
      <div class="workflow-step reveal" style="transition-delay:.1s"><div class="step-num">02</div><div class="step-title">Carga presupuesto</div><p class="step-desc">Importa Excel con módulos e ítems o carga manualmente la estructura.</p></div>
      <div class="workflow-step reveal" style="transition-delay:.2s"><div class="step-num">03</div><div class="step-title">Asigna personal</div><p class="step-desc">Trabajadores por jornal o contratistas a ítems y módulos completos.</p></div>
      <div class="workflow-step reveal" style="transition-delay:.3s"><div class="step-num">04</div><div class="step-title">Registra avances</div><p class="step-desc">El residente verifica, fotografía y registra el % de avance con evidencia.</p></div>
      <div class="workflow-step reveal" style="transition-delay:.4s"><div class="step-num">05</div><div class="step-title">Controla resultados</div><p class="step-desc">Dashboard en tiempo real: presupuesto vs. ejecución, saldos y proyecciones.</p></div>
    </div>
  </div>
</section>

<div class="divider"></div>

{{-- ══ LABOR ══ --}}
<section class="labor-section" id="labor">
  <div class="container">
    <div class="labor-header reveal">
      <div>
        <div class="section-label">Módulo especializado</div>
        <h2 class="section-title">Mano de obra<br>sin papel</h2>
      </div>
      <p class="labor-desc">Dos sistemas de pago completamente distintos, integrados en una sola plataforma.</p>
    </div>
    <div class="labor-grid">
      <div class="labor-card reveal">
        <div class="labor-badge badge-jornal"><span class="live-dot" style="background:#3B82F6"></span>Modalidad Jornal</div>
        <h3 class="labor-title">Planilla Semanal</h3>
        <ul class="labor-features">
          <li>Registro diario Lun-Sáb: asistencia completa, media o ausencia</li>
          <li>Horas extra por día con tarifa diferenciada</li>
          <li>Descuentos: anticipos, ausencias y otros conceptos</li>
          <li>Snapshot de salarios que preserva el histórico</li>
          <li>Vista de impresión con columna de firma para planilla física</li>
          <li>Total neto automático por trabajador y por semana</li>
        </ul>
      </div>
      <div class="labor-card reveal" style="transition-delay:.15s">
        <div class="labor-badge badge-item"><span class="live-dot"></span>Modalidad Contrato</div>
        <h3 class="labor-title">Por Ítem o Módulo</h3>
        <ul class="labor-features">
          <li>Asigna un contratista a un ítem específico o a un módulo entero</li>
          <li>Avance desglosado ítem por ítem con porcentaje individual</li>
          <li>Hasta 3 fotos de evidencia por ítem verificadas por el residente</li>
          <li>Monto calculado proporcionalmente al peso del ítem en el módulo</li>
          <li>Historial completo de avances y pagos por contratista</li>
          <li>Barra de progreso en tiempo real hasta completar el 100%</li>
        </ul>
      </div>
    </div>
  </div>
</section>

{{-- ══ CTA ══ --}}
<section class="cta-section" id="cta">
  <div class="cta-inner reveal">
    <div class="section-label" style="justify-content:center;margin-bottom:28px;">
      <span class="live-dot"></span> Listo para comenzar
    </div>
    <h2 class="cta-title">Tu próxima obra<br>bajo <span>control total</span></h2>
    <p class="cta-sub">
      CIO es el sistema que tu equipo necesita para ejecutar con precisión,<br>
      pagar con evidencia y rendir cuentas sin sorpresas.
    </p>
    <div class="cta-actions">
      @auth
        <a href="{{ url('/dashboard') }}" class="btn-primary" style="font-size:15px;padding:16px 40px">Ir al Dashboard →</a>
      @else
      <a href="https://wa.me/59171063438" class="btn-primary" style="font-size:15px;padding:16px 40px">Obtener Acceso</a>
        @if (Route::has('login'))
          <a href="{{ route('login') }}" class="btn-secondary" style="font-size:15px;padding:16px 40px">Acceder al sistema →</a>
        @endif
        
      @endauth
    </div>
  </div>
</section>

{{-- ══ FOOTER ══ --}}
<footer>
  <span class="footer-copy">© {{ date('Y') }} CIO — Control Inteligente de Obras</span>
  <span class="footer-tagline">Construir con datos. Pagar con evidencia.</span>
  <span class="footer-copy">Roger</span>
</footer>

<script>
// Scroll reveal
const observer = new IntersectionObserver(
  entries => entries.forEach(el => { if (el.isIntersecting) el.target.classList.add('visible'); }),
  { threshold: 0.1 }
);
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

// Animar barras de módulos al entrar en viewport
const moduleObs = new IntersectionObserver(entries => {
  entries.forEach(entry => {
    if (!entry.isIntersecting) return;
    entry.target.querySelectorAll('.module-bar').forEach(bar => {
      const w = bar.style.width;
      bar.style.width = '0';
      setTimeout(() => bar.style.width = w, 150);
    });
    moduleObs.unobserve(entry.target);
  });
}, { threshold: 0.3 });
const ml = document.querySelector('.module-list');
if (ml) moduleObs.observe(ml);

// Hover en módulos
document.querySelectorAll('.module-item').forEach(item => {
  item.addEventListener('mouseenter', () => {
    document.querySelectorAll('.module-item').forEach(i => i.classList.remove('active'));
    item.classList.add('active');
  });
});
</script>
</body>
</html>