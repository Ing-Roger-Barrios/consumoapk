<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Iniciar Sesión — CIO</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=Barlow:wght@300;400;500&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root {
  --orange: #F05A00;
  --orange-dim: #C04500;
  --dark: #0A0B0D;
  --dark2: #0F1114;
  --dark3: #141720;
  --steel: #1E2230;
  --steel2: #252A3A;
  --line: rgba(255,255,255,0.06);
  --line2: rgba(255,255,255,0.10);
  --text: #E8EAF0;
  --muted: #6B7280;
  --input-bg: #0D0F12;
  --input-border: rgba(255,255,255,0.1);
  --input-focus: rgba(240,90,0,0.5);
  --error: #F87171;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
html { height: 100%; }

body {
  min-height: 100vh;
  background: var(--dark);
  color: var(--text);
  font-family: 'Barlow', sans-serif;
  font-weight: 300;
  display: flex;
  overflow: hidden;
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

/* ── LAYOUT ── */
.login-layout {
  display: flex;
  width: 100%;
  min-height: 100vh;
  position: relative;
  z-index: 1;
}

/* ── LEFT PANEL (branding) ── */
.panel-left {
  flex: 1;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  padding: 48px;
  position: relative;
  overflow: hidden;
  border-right: 1px solid var(--line);
}

/* Líneas decorativas de fondo */
.panel-left::before {
  content: '';
  position: absolute;
  top: -100px; right: -100px;
  width: 500px; height: 500px;
  border: 1px solid rgba(240,90,0,0.06);
  border-radius: 50%;
  pointer-events: none;
}
.panel-left::after {
  content: '';
  position: absolute;
  top: -40px; right: -40px;
  width: 320px; height: 320px;
  border: 1px solid rgba(240,90,0,0.1);
  border-radius: 50%;
  pointer-events: none;
}

.panel-left-top { position: relative; z-index: 2; }
.panel-left-bottom { position: relative; z-index: 2; }

/* Logo */
.logo { display: flex; align-items: center; gap: 14px; text-decoration: none; margin-bottom: 64px; }
.logo-mark {
  width: 42px; height: 42px; background: var(--orange);
  display: flex; align-items: center; justify-content: center;
  clip-path: polygon(0 0, 85% 0, 100% 15%, 100% 100%, 15% 100%, 0 85%);
  font-family: 'Barlow Condensed', sans-serif; font-weight: 800; font-size: 15px; color: white; letter-spacing: 0.05em;
}
.logo-text { font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 18px; letter-spacing: 0.12em; text-transform: uppercase; color: var(--text); }
.logo-text span { color: var(--orange); }

/* Tagline grande */
.panel-headline {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 800;
  font-size: clamp(36px, 3.5vw, 56px);
  line-height: 0.95; text-transform: uppercase; letter-spacing: -0.01em;
  margin-bottom: 24px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.2s;
}
.panel-headline .accent { color: var(--orange); }
.panel-headline .ghost { color: transparent; -webkit-text-stroke: 1px rgba(255,255,255,0.15); }

.panel-sub {
  font-size: 15px; font-weight: 300; line-height: 1.7; color: var(--muted);
  max-width: 380px; margin-bottom: 48px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.35s;
}

/* Feature pills */
.feature-list {
  display: flex; flex-direction: column; gap: 14px;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.5s;
}
.feature-item {
  display: flex; align-items: center; gap: 14px;
  font-size: 13px; font-weight: 400; color: #9CA3AF;
}
.feature-dot {
  width: 28px; height: 28px; flex-shrink: 0;
  background: rgba(240,90,0,0.08); border: 1px solid rgba(240,90,0,0.2);
  clip-path: polygon(0 0, 75% 0, 100% 25%, 100% 100%, 25% 100%, 0 75%);
  display: flex; align-items: center; justify-content: center; font-size: 12px;
}

/* Deco giratorio */
.deco-ring-wrap {
  position: absolute; bottom: -120px; right: -80px;
  width: 400px; height: 400px; pointer-events: none;
  opacity: 0; animation: fadeIn 1.5s ease forwards 0.8s;
}
.deco-ring {
  position: absolute; inset: 0; border-radius: 50%;
  border: 1px solid rgba(240,90,0,0.08); animation: spin 40s linear infinite;
}
.deco-ring:nth-child(2) { inset: 50px; border-color: rgba(240,90,0,0.05); animation-duration: 25s; animation-direction: reverse; }
.deco-ring-core {
  position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%);
  width: 100px; height: 100px;
  clip-path: polygon(50% 0, 100% 25%, 100% 75%, 50% 100%, 0 75%, 0 25%);
  background: radial-gradient(circle, rgba(240,90,0,0.12), transparent 70%);
  border: 1px solid rgba(240,90,0,0.15);
}

/* Version badge */
.version-badge {
  font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.12em;
  color: var(--muted); text-transform: uppercase;
  display: flex; align-items: center; gap: 8px;
}
.version-badge::before { content: ''; width: 24px; height: 1px; background: var(--muted); opacity: 0.4; }

/* ── RIGHT PANEL (form) ── */
.panel-right {
  width: 480px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 48px 56px;
  background: var(--dark2);
  border-left: 1px solid var(--line);
  position: relative;
}

/* Corner accents */
.panel-right::before {
  content: '';
  position: absolute; top: 0; right: 0;
  width: 60px; height: 60px;
  border-top: 2px solid var(--orange);
  border-right: 2px solid var(--orange);
  opacity: 0.4;
}
.panel-right::after {
  content: '';
  position: absolute; bottom: 0; left: 0;
  width: 60px; height: 60px;
  border-bottom: 2px solid var(--orange);
  border-left: 2px solid var(--orange);
  opacity: 0.4;
}

.form-wrap {
  width: 100%;
  opacity: 0; animation: fadeUp 0.7s ease forwards 0.3s;
}

.form-eyebrow {
  font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 500;
  letter-spacing: 0.2em; color: var(--orange); text-transform: uppercase;
  display: flex; align-items: center; gap: 8px; margin-bottom: 20px;
}
.form-eyebrow .live-dot { width: 5px; height: 5px; background: var(--orange); border-radius: 50%; animation: pulse-dot 1.6s ease infinite; }

.form-title {
  font-family: 'Barlow Condensed', sans-serif; font-weight: 800;
  font-size: 38px; text-transform: uppercase; letter-spacing: -0.01em;
  line-height: 1; margin-bottom: 8px;
}
.form-subtitle { font-size: 14px; font-weight: 300; color: var(--muted); margin-bottom: 36px; }

/* Status (flash messages) */
.status-msg {
  font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.05em;
  color: #34D399; background: rgba(52,211,153,0.08); border: 1px solid rgba(52,211,153,0.2);
  padding: 10px 14px; margin-bottom: 24px;
}

/* Form fields */
.field { margin-bottom: 22px; }
.field-label {
  font-family: 'JetBrains Mono', monospace; font-size: 10px; font-weight: 500;
  letter-spacing: 0.15em; text-transform: uppercase; color: var(--muted);
  display: block; margin-bottom: 8px;
}
.field-input {
  width: 100%; background: var(--input-bg); border: 1px solid var(--input-border);
  color: var(--text); font-family: 'Barlow', sans-serif; font-size: 14px; font-weight: 400;
  padding: 12px 16px; outline: none; transition: border-color 0.2s, box-shadow 0.2s;
  -webkit-appearance: none;
}
.field-input::placeholder { color: var(--muted); font-size: 13px; }
.field-input:focus {
  border-color: rgba(240,90,0,0.6);
  box-shadow: 0 0 0 3px rgba(240,90,0,0.08);
}
.field-input:-webkit-autofill,
.field-input:-webkit-autofill:hover,
.field-input:-webkit-autofill:focus {
  -webkit-box-shadow: 0 0 0 1000px var(--input-bg) inset !important;
  -webkit-text-fill-color: var(--text) !important;
  border-color: rgba(240,90,0,0.4) !important;
  caret-color: var(--text);
}
.field-error {
  font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.05em;
  color: var(--error); margin-top: 6px; display: flex; align-items: center; gap: 6px;
}
.field-error::before { content: '!'; font-weight: 700; }

/* Remember me */
.remember-row {
  display: flex; align-items: center; gap: 10px;
  margin-bottom: 28px;
}
.remember-check {
  width: 16px; height: 16px; flex-shrink: 0;
  background: var(--input-bg); border: 1px solid var(--input-border);
  appearance: none; -webkit-appearance: none; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  position: relative; transition: all 0.2s;
}
.remember-check:checked {
  background: var(--orange); border-color: var(--orange);
}
.remember-check:checked::after {
  content: '';
  position: absolute;
  width: 4px; height: 7px;
  border: 1.5px solid white;
  border-top: none; border-left: none;
  transform: rotate(45deg) translate(-1px, -1px);
}
.remember-check:focus { outline: none; box-shadow: 0 0 0 3px rgba(240,90,0,0.15); }
.remember-label {
  font-family: 'JetBrains Mono', monospace; font-size: 11px; letter-spacing: 0.08em;
  color: var(--muted); text-transform: uppercase; cursor: pointer; user-select: none;
}

/* Submit button */
.btn-submit {
  width: 100%; background: var(--orange); color: white; border: none; cursor: pointer;
  font-family: 'Barlow Condensed', sans-serif; font-weight: 700; font-size: 15px;
  letter-spacing: 0.15em; text-transform: uppercase;
  padding: 15px 32px; margin-bottom: 20px;
  clip-path: polygon(0 0, 96% 0, 100% 20%, 100% 100%, 4% 100%, 0 80%);
  transition: background 0.2s, transform 0.15s;
  display: flex; align-items: center; justify-content: center; gap: 10px;
}
.btn-submit:hover { background: var(--orange-dim); transform: translateY(-1px); }
.btn-submit:active { transform: translateY(0); }

/* Forgot password */
.forgot-link {
  display: block; text-align: center;
  font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.12em;
  text-transform: uppercase; text-decoration: none; color: var(--muted);
  transition: color 0.2s;
}
.forgot-link:hover { color: var(--orange); }

/* Divider */
.form-divider {
  height: 1px; background: var(--line); margin: 28px 0;
  position: relative; display: flex; align-items: center; justify-content: center;
}
.form-divider::after {
  content: 'SISTEMA SEGURO';
  position: absolute; background: var(--dark2); padding: 0 12px;
  font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 0.2em;
  color: var(--steel2); white-space: nowrap;
}

/* Security badges */
.security-row {
  display: flex; align-items: center; justify-content: center; gap: 20px;
}
.security-item {
  display: flex; align-items: center; gap: 6px;
  font-family: 'JetBrains Mono', monospace; font-size: 9px; letter-spacing: 0.1em;
  color: var(--steel2); text-transform: uppercase;
}
.security-dot { width: 4px; height: 4px; background: var(--steel2); border-radius: 50%; }

/* Back link */
.back-link {
  position: absolute; top: 32px; left: 40px;
  font-family: 'JetBrains Mono', monospace; font-size: 10px; letter-spacing: 0.12em;
  text-transform: uppercase; text-decoration: none; color: var(--muted);
  display: flex; align-items: center; gap: 8px; transition: color 0.2s;
}
.back-link:hover { color: var(--text); }
.back-link::before { content: '←'; }

/* ── ANIMATIONS ── */
@keyframes fadeUp { from{opacity:0;transform:translateY(20px)} to{opacity:1;transform:translateY(0)} }
@keyframes fadeIn { from{opacity:0} to{opacity:1} }
@keyframes spin   { to{transform:rotate(360deg)} }
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:0.3} }

/* ── RESPONSIVE ── */
@media(max-width:860px){
  .panel-left { display: none; }
  .panel-right { width: 100%; padding: 40px 28px; }
  .panel-right::before, .panel-right::after { width: 40px; height: 40px; }
}
</style>
</head>
<body>

<div class="blueprint-bg"></div>

<div class="login-layout">

  {{-- ══ PANEL IZQUIERDO (branding) ══ --}}
  <div class="panel-left">
    <div class="panel-left-top">
      <a href="{{ url('/') }}" class="logo">
        <div class="logo-mark">CIO</div>
        <span class="logo-text">Control <span>Inteligente</span> de Obras</span>
      </a>

      <div class="panel-headline">
        <span class="accent">Presupuesto.</span><br>
        <span>Ejecución.</span><br>
        <span class="ghost">Control.</span>
      </div>

      <p class="panel-sub">
        La plataforma que conecta a tu equipo de obra con los datos que necesitan para construir con precisión y pagar con evidencia.
      </p>

      <div class="feature-list">
        <div class="feature-item">
          <div class="feature-dot">📐</div>
          <span>Importa tu presupuesto desde Excel automáticamente</span>
        </div>
        <div class="feature-item">
          <div class="feature-dot">👷</div>
          <span>Mano de obra por jornal o contrato con fotos de avance</span>
        </div>
        <div class="feature-item">
          <div class="feature-dot">📊</div>
          <span>Dashboard en tiempo real: presupuestado vs. ejecutado</span>
        </div>
        <div class="feature-item">
          <div class="feature-dot">📱</div>
          <span>Accede desde cualquier dispositivo, donde estés</span>
        </div>
      </div>
    </div>

    <div class="panel-left-bottom">
      <div class="version-badge">Laravel {{ app()->version() }} · CIO v1.0</div>
    </div>

    {{-- Deco --}}
    <div class="deco-ring-wrap">
      <div class="deco-ring"></div>
      <div class="deco-ring"></div>
      <div class="deco-ring-core"></div>
    </div>
  </div>

  {{-- ══ PANEL DERECHO (formulario) ══ --}}
  <div class="panel-right">

    <a href="{{ url('/') }}" class="back-link">Volver al inicio</a>

    <div class="form-wrap">

      <div class="form-eyebrow">
        <span class="live-dot"></span>
        Acceso al sistema
      </div>

      <div class="form-title">Bienvenido</div>
      <p class="form-subtitle">Ingresa tus credenciales para continuar</p>

      {{-- Status --}}
      @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
      @endif

      <form method="POST" action="{{ route('login') }}">
        @csrf

        {{-- Email --}}
        <div class="field">
          <label class="field-label" for="email">Correo electrónico</label>
          <input
            id="email"
            class="field-input"
            type="email"
            name="email"
            value="{{ old('email') }}"
            required autofocus autocomplete="username"
            placeholder="tu@correo.com"
          >
          @error('email')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Password --}}
        <div class="field">
          <label class="field-label" for="password">Contraseña</label>
          <input
            id="password"
            class="field-input"
            type="password"
            name="password"
            required autocomplete="current-password"
            placeholder="••••••••"
          >
          @error('password')
            <div class="field-error">{{ $message }}</div>
          @enderror
        </div>

        {{-- Remember me --}}
        <div class="remember-row">
          <input
            id="remember_me"
            type="checkbox"
            class="remember-check"
            name="remember"
          >
          <label for="remember_me" class="remember-label">Mantener sesión iniciada</label>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-submit">
          <span>Ingresar al sistema</span>
          <span>→</span>
        </button>

        {{-- Forgot password --}}
        @if (Route::has('password.request'))
          <a href="{{ route('password.request') }}" class="forgot-link">
            ¿Olvidaste tu contraseña?
          </a>
        @endif

        <div class="form-divider"></div>

        <div class="security-row">
          <div class="security-item"><span class="security-dot"></span>Sesión cifrada</div>
          <div class="security-item"><span class="security-dot"></span>Acceso verificado</div>
          <div class="security-item"><span class="security-dot"></span>Laravel Auth</div>
        </div>
      </form>
    </div>
  </div>

</div>

</body>
</html>