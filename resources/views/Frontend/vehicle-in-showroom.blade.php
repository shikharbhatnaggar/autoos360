@extends('frontend.layouts.app')

@section('title', $vehicle->model . ' — AutoOS360 Live Experience')

@push('styles')
<link href="https://googleapis.com" rel="stylesheet">
<style>
    /* 🌟 STRIP OUT GLOBAL LAYOUT WRAPPERS FOR FULLSCREEN MODE */
    nav, header:not(.showroom-header), footer, .navbar, .site-footer {
        display: none !important;
    }
    body, html {
        margin: 0 !important;
        padding: 0 !important;
        background: #05070F !important;
        overflow: hidden !important;
        width: 100vw;
        height: 100vh;
    }

    :root {
        --dark-bg: #05070F;
        --neon-glow: #ef4444;
    }

    body {
      font-family: 'Inter', sans-serif;
      color: #ffffff;
      -webkit-font-smoothing: antialiased;
    }

    /* Ambient Lighting Effects */
    .ambient-glow {
      position: fixed;
      inset: 0;
      background: radial-gradient(circle at 50% 50%, rgba(239, 68, 68, 0.12) 0%, transparent 60%);
      pointer-events: none;
      z-index: 2;
    }

    /* 🌟 FULLSCREEN SLIDER CONTEXT STAGE VIEWPORT */
    .experience-container {
      position: fixed;
      inset: 0;
      width: 100vw;
      height: 100vh;
      z-index: 5;
      opacity: 0;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
      transition: opacity 1s cubic-bezier(0.2, 0.8, 0.2, 1);
    }
    .experience-container.is-active { opacity: 1; }

    .slider-viewport {
      position: absolute;
      inset: 0;
      width: 100%;
      height: 100%;
      z-index: 1;
      background: var(--dark-bg);
    }

    .slider-track {
      display: flex;
      width: 100%;
      height: 100%;
      transition: transform 0.7s cubic-bezier(0.25, 1, 0.5, 1);
    }
    .slide-item {
      flex: 0 0 100%;
      width: 100%;
      height: 100%;
      position: relative;
    }
    .slide-item img {
      width: 100%;
      height: 100%;
      object-fit: cover; /* 🌟 Spreads image edge-to-edge seamlessly */
    }

    /* Overlay Shadow Vignette to maintain text contrast */
    .slider-vignette {
      position: absolute;
      inset: 0;
      background: linear-gradient(to bottom, rgba(5,7,15,0.7) 0%, transparent 30%, transparent 70%, rgba(5,7,15,0.85) 100%);
      z-index: 3;
      pointer-events: none;
    }

    /* Absolute Control Action Pedals */
    .slider-btn {
      position: absolute;
      top: 50%;
      transform: translateY(-50%);
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(5, 7, 15, 0.5);
      backdrop-filter: blur(8px);
      -webkit-backdrop-filter: blur(8px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      color: #fff;
      display: grid;
      place-items: center;
      transition: all 0.3s ease;
      z-index: 10;
      border: none;
      cursor: pointer;
    }
    .slider-btn:hover { background: var(--neon-glow); transform: translateY(-50%) scale(1.1); box-shadow: 0 0 15px var(--neon-glow); }
    .slider-btn--prev { left: 24px; }
    .slider-btn--next { right: 24px; }
    .slider-btn svg { width: 22px; height: 22px; fill: none; stroke: currentColor; stroke-width: 2.5; }

    /* Floating UI Layers over Slider */
    .showroom-header {
      position: relative;
      z-index: 15;
      width: 15%;
      padding: 24px;
      box-sizing: border-box;
      pointer-events: none;
    }
    .showroom-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      padding: 8px 16px;
      border-radius: 9999px;
      text-transform: uppercase;
      font-size: 11px;
      font-weight: 800;
      tracking-widest: 0.1em;
      background: rgba(5, 7, 15, 0.6);
      backdrop-filter: blur(8px);
      border: 1px solid rgba(255,255,255,0.08);
      color: #fff;
    }

    .bottom-interface-layer {
      position: relative;
      z-index: 15;
      width: 100%;
      padding: 40px 24px;
      box-sizing: border-box;
      display: flex;
      flex-direction: column;
      align-items: center;
      gap: 20px;
      background: linear-gradient(to top, rgba(5,7,15,0.9) 0%, transparent 100%);
    }

    .slider-indicators {
      display: flex;
      gap: 8px;
    }
    .bullet {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.3);
      transition: all 0.3s ease;
      cursor: pointer;
      border: none;
      padding: 0;
    }
    .bullet.is-active {
      width: 32px;
      border-radius: 9999px;
      background: var(--neon-glow);
      box-shadow: 0 0 10px var(--neon-glow);
    }

    .showroom-meta-panel {
      text-align: center;
      max-width: 600px;
    }
    .showroom-meta-panel .price-tag { 
        color: var(--neon-glow); 
        font-weight: 900; 
        font-size: 24px; 
        letter-spacing: 0.02em;
        text-shadow: 0 2px 10px rgba(239,68,68,0.3);
    }
    .showroom-meta-panel h2 { font-family: 'Poppins', sans-serif; font-size: 28px; font-weight: 700; margin: 4px 0 8px 0; letter-spacing: -0.01em; text-shadow: 0 2px 8px rgba(0,0,0,0.5); }
    .showroom-meta-panel p { font-size: 14px; color: #CBCED9; margin: 0; text-shadow: 0 1px 4px rgba(0,0,0,0.5); }

    /* Audio Gate Screen Styles */
    .audio-gate-overlay {
      position: fixed;
      inset: 0;
      background: #05070F;
      z-index: 200;
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 24px;
      text-align: center;
      transition: opacity 0.6s ease, visibility 0.6s;
    }
    .audio-gate-overlay.is-hidden { opacity: 0; visibility: hidden; pointer-events: none; }
    
    .gate-glow-circle {
      width: 110px;
      height: 110px;
      border-radius: 50%;
      background: rgba(239, 68, 68, 0.04);
      border: 2px solid var(--neon-glow);
      display: grid;
      place-items: center;
      margin-bottom: 28px;
      cursor: pointer;
      position: relative;
      box-shadow: 0 0 25px rgba(239, 68, 68, 0.15);
      animation: pulseGlow 2s infinite ease-in-out;
      border: none;
    }
    .gate-glow-circle svg { width: 36px; height: 36px; color: #fff; margin-left: 4px; }
    
    .gate-title { font-family: 'Poppins', sans-serif; font-size: 24px; font-weight: 700; margin-bottom: 8px; }
    .gate-sub { font-size: 14px; color: #6E7191; max-width: 36ch; line-height: 1.5; }

    @keyframes pulseGlow {
      0% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.15); transform: scale(1); }
      50% { box-shadow: 0 0 40px rgba(239, 68, 68, 0.35); transform: scale(1.05); }
      100% { box-shadow: 0 0 20px rgba(239, 68, 68, 0.15); transform: scale(1); }
    }

    /* Update these explicit selector targets inside your push style grid */
    .showroom-header {
    position: absolute !important;
    top: 0;
    left: 0;
    width: 15%;
    padding: 24px;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 50;
    pointer-events: none; /* Allows slider background clicks to pass through safely */
    }

    .showroom-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 10px 24px;
    border-radius: 9999px;
    background: rgba(5, 7, 15, 0.7);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.4);
    }

</style>
@endpush

@section('content')
<!-- Ambient Filter Overlay Layers -->
<div class="ambient-glow"></div>

<!-- 🌟 UPDATED: Production GitHub Pages Soundtrack Engine Stream -->
<audio id="launchMusic" loop preload="auto">
    <source src="https://shikharbhatnaggar.github.io/publicautoos360/assets/music/splash.mp3" type="audio/mpeg">
</audio>

<!-- 1. Cinematic Audio Gate Overlay Layout -->
<div id="audioGate" class="audio-gate-overlay">
    <button type="button" id="enterShowroomBtn" class="gate-glow-circle" aria-label="Explore Showroom Experience">
        <svg xmlns="http://w3.org" viewBox="0 0 24 24" fill="currentColor">
            <path d="M8 5.14v14c0 .86.94 1.39 1.66.9l8-5.14c.64-.41.64-1.39 0-1.8l-8-5.14A1.07 1.07 0 008 5.14z"/>
        </svg>
    </button>
    <h2 class="gate-title">AutoOS360 Live Experience</h2>
    <p class="gate-sub">Tap to unlock the premium fullscreen digital showroom engine with high-fidelity production beats.</p>
</div>

<!-- 2. Main Fullscreen Stage Area Container -->
<div id="expContainer" class="experience-container">
    
    <!-- Floating Top Header -->
    <header class="showroom-header absolute top-0 left-0 w-full z-50 flex justify-center p-6 pointer-events-none">
        <div class="showroom-badge pointer-events-auto">
            <img src="{{ asset('frontend/images/autoos360.png') }}" 
                alt="AutoOS360" 
                class="h-6 w-auto max-w-[160px] object-contain block">
        </div>
    </header>



    <!-- Edge-to-Edge Slider Viewport -->
    <div class="slider-viewport">
        <div class="slider-vignette"></div>

        <!-- Left/Right Action Arrows -->
        <button type="button" id="prevBtn" class="slider-btn slider-btn--prev" aria-label="Previous view">
            <svg viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"/></svg>
        </button>
        <button type="button" id="nextBtn" class="slider-btn slider-btn--next" aria-label="Next view">
            <svg viewBox="0 0 24 24"><path d="M9 5l7 7-7 7"/></svg>
        </button>

        <!-- Dynamic Server-Side Image Track Node Stack -->
        <div id="sliderTrack" class="slider-track">
            @foreach($vehicle->slider_images as $imageUrl)
                <div class="slide-item">
                    <img src="{{ $imageUrl }}" alt="{{ $vehicle->model }} fullscreen aspect" loading="eager">
                </div>
            @endforeach
        </div>
    </div>

    <!-- Floating Bottom Interface Layer Container -->
    <div class="bottom-interface-layer">
        <!-- Bullet Track Indicators -->
        <div id="indicatorsTrack" class="slider-indicators">
            @foreach($vehicle->slider_images as $index => $url)
                <button type="button" class="bullet {{ $index === 0 ? 'is-active' : '' }}" data-slide="{{ $index }}" aria-label="Go to slide {{ $index + 1 }}"></button>
            @endforeach
        </div>

        <!-- Metadata Description Card Panel -->
        <div class="showroom-meta-panel">
            <div class="price-tag">{{ $vehicle->display_price }}</div>
            <h2>{{ $vehicle->make_year }} {{ $vehicle->model }}</h2>
            <p>{{ $vehicle->fuel_type }} &bull; {{ $vehicle->transmission }} &bull; {{ $vehicle->ownership }}</p>
            
            @if($vehicle->tenant){{ $vehicle->tenant->name }} • {{ $vehicle->tenant->city }}@endif
        </div>
    </div>
</div>

@push('scripts')
<script>
(function () {
    'use strict';
    const AUTO_PLAY_INTERVAL = 3800; // Adjusted for a cinema-like pacing sequence
    const track = document.getElementById('sliderTrack');
    const indicators = document.getElementById('indicatorsTrack');
    const audioGate = document.getElementById('audioGate');
    const enterBtn = document.getElementById('enterShowroomBtn');
    const music = document.getElementById('launchMusic');
    const container = document.getElementById('expContainer');
    const bullets = indicators.querySelectorAll('.bullet');
    let currentIndex = 0;
    const totalSlides = {{ count($vehicle->slider_images) }};
    let autoPlayTimer = null;

    function goToSlide(targetIdx) {
        if (totalSlides === 0) return;
        if (targetIdx >= totalSlides) currentIndex = 0;
        else if (targetIdx < 0) currentIndex = totalSlides - 1;
        else currentIndex = targetIdx;

        // Execute hardware-accelerated slider translation matrix
        track.style.transform = `translateX(-${currentIndex * 100}%)`;

        // Sync active state indicators
        bullets.forEach((bullet, idx) => {
            if (idx === currentIndex) bullet.classList.add('is-active');
            else bullet.classList.remove('is-active');
        });
    }

    function startAutoPlay() {
        if (totalSlides <= 1) return;
        autoPlayTimer = setInterval(() => {
            goToSlide(currentIndex + 1);
        }, AUTO_PLAY_INTERVAL);
    }

    function resetAutoPlayTimer() {
        clearInterval(autoPlayTimer);
        startAutoPlay();
    }

    document.getElementById('nextBtn').addEventListener('click', () => {
        goToSlide(currentIndex + 1);
        resetAutoPlayTimer();
    });

    document.getElementById('prevBtn').addEventListener('click', () => {
        goToSlide(currentIndex - 1);
        resetAutoPlayTimer();
    });

    bullets.forEach(bullet => {
        bullet.addEventListener('click', () => {
            goToSlide(parseInt(bullet.dataset.slide, 10));
            resetAutoPlayTimer();
        });
    });

    // Immersive Transition Portal
    if (enterBtn && audioGate) {
        enterBtn.addEventListener('click', () => {
            if (music) {
                music.volume = 0.50;
                music.play().catch(err => console.log('[DEBUG] Audio context held:', err));
            }
            audioGate.classList.add('is-hidden');
            container.classList.add('is-active');
            startAutoPlay();
        });
    }
})();
</script>
@endpush