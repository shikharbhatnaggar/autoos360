<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'AutoOS360 — Buy inspected used cars &amp; commercial vehicles')</title>
<meta name="description" content="@yield('meta_description', 'Browse inspected, warranty-backed used cars and mini commercial vehicles. Every car comes with a 200-point check, verified ownership and free RC transfer.')">
<meta property="og:image" content="@yield('og_image', url('/frontend/images/default-share.jpg'))">
<meta property="og:type" content="website">

<!-- Load global layout css configurations -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('frontend/css/styles.css') }}">
<link rel="icon" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 32 32'%3E%3Crect width='32' height='32' rx='8' fill='%234739FF'/%3E%3Cpath d='M6 20h20l-2-6-3-4H11l-3 4z' fill='white'/%3E%3C/svg%3E">
@stack('styles') {{-- Allows pages to inject specialized CSS styles grids dynamically --}}

</head>
<body>

<!-- 1 · Announcement bar -->
<div class="annc">
  <div class="wrap annc__in">
    <span class="annc__tag">Monsoon offer</span>
    <span class="annc__msg">Save up to ₹80,000 on assured cars, plus zero processing fee on finance.</span>
    <a class="annc__link" href="https://shikharbhatnaggar.github.io/publicautoos360/offers.html">See offers</a>
  </div>
</div>

<!-- 2 · Header -->
<header class="hdr">
  <div class="wrap hdr__in">
    <a class="logo logo--img" href="{{ url('/') }}">
      <img src="{{ asset('frontend/images/autoos360.png') }}" alt="AutoOS360 — home" width="120" height="33">
    </a>

    <button class="burger" type="button" aria-expanded="false" aria-controls="nav" aria-label="Open menu"><span></span></button>

    <nav class="nav" id="nav" aria-label="Main">
      <div class="nav__item">
        <a class="nav__link" href="listing.html?view=all">Buy a car
          <svg class="nav__caret" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4.5L6 8.5l4-4" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="nav__menu">
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all']) }}">New arrivals</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'section' => 'featured']) }}">Featured cars</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'Hatchback']) }}">Hatchbacks</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'Sedan']) }}">Sedans</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'SUV']) }}">SUVs</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Electric']) }}">Electric</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Diesel']) }}">Diesel</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'CNG']) }}">CNG</a>
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Petrol']) }}">Petrol</a>
        </div>
      </div>
      <div class="nav__item">
        <a class="nav__link" href="listing.html?view=commercial">Commercial
          <svg class="nav__caret" viewBox="0 0 12 12" aria-hidden="true"><path d="M2 4.5L6 8.5l4-4" stroke="currentColor" stroke-width="1.6" fill="none" stroke-linecap="round"/></svg>
        </a>
        <div class="nav__menu">
          <a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'commercial']) }}">Mini commercial fleet</a>
          <!-- <a href="listing.html?view=commercial&amp;fuel=Diesel">Diesel runners</a>
          <a href="listing.html?view=commercial&amp;fuel=CNG">CNG runners</a> -->
        </div>
      </div>
      <div class="nav__item"><a class="nav__link" href="#">Sell your car</a></div>
      <div class="nav__item"><a class="nav__link" href="#">Free assessment</a></div>
      <div class="nav__item"><a class="nav__link" href="{{ route('frontend.pricing') }}">Pricing</a></div>
      <a class="btn btn--primary btn--sm" href="{{ route('frontend.partner.register') }}">Become Partner</a>
    </nav>

    <div class="hdr__cta">
      <a class="btn btn--primary btn--sm" href="{{ route('frontend.partner.register') }}">Become Partner</a>
    </div>
  </div>
</header>

  <!-- DYNAMIC VIEW HOOK ENTRY CONTAINER POINT -->
  @yield('content')

  <!-- GLOBAL FOOTER FRAME WORK CHASSIS -->
    <footer class="foot">
        <div class="wrap foot__grid">
            <div class="foot__brand">
            <a class="logo" href="{{ url('/') }}">
                <img src="{{ asset('frontend/images/autoos360.png') }}" alt="AutoOS360 — home" width="160" height="60">
            </a>
            <p class="foot__tag">The bridge between<br>a good car and you.</p>
            <!-- <p class="foot__addr"><b>Registered office</b>
                Purushottam Vihar, Gole Ka Mandir<br>Gwalior, MP 474005</p> -->
            <div class="foot__social">
                <a href="#" aria-label="Facebook"><svg viewBox="0 0 16 16" fill="currentColor"><path d="M9.2 15V8.7h2.1l.3-2.4H9.2V4.7c0-.7.2-1.2 1.2-1.2h1.3V1.4C11.4 1.3 10.6 1.3 9.8 1.3 8 1.3 6.7 2.4 6.7 4.4v1.9H4.6v2.4h2.1V15z"/></svg></a>
                <!-- <a href="#" aria-label="X"><svg viewBox="0 0 16 16" fill="currentColor"><path d="M12.3 1.5h2.2L9.7 7l5.7 7.5h-4.5L7.4 9.8l-4 4.7H1.2l5.2-6L1 1.5h4.6l3.2 4.3zm-.8 11.7h1.2L4.6 2.7H3.3z"/></svg></a> -->
                <a href="#" aria-label="Instagram"><svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.4"><rect x="2" y="2" width="12" height="12" rx="3.6"/><circle cx="8" cy="8" r="2.8"/><circle cx="11.6" cy="4.4" r=".8" fill="currentColor" stroke="none"/></svg></a>
                <!-- <a href="#" aria-label="YouTube"><svg viewBox="0 0 16 16" fill="currentColor"><path d="M15.2 4.9a1.9 1.9 0 00-1.3-1.3C12.7 3.2 8 3.2 8 3.2s-4.7 0-5.9.4A1.9 1.9 0 00.8 4.9C.4 6.1.4 8 .4 8s0 1.9.4 3.1a1.9 1.9 0 001.3 1.3c1.2.4 5.9.4 5.9.4s4.7 0 5.9-.4a1.9 1.9 0 001.3-1.3c.4-1.2.4-3.1.4-3.1s0-1.9-.4-3.1zM6.5 10.3V5.7L10.4 8z"/></svg></a> -->
                <!-- <a href="#" aria-label="LinkedIn"><svg viewBox="0 0 16 16" fill="currentColor"><path d="M4.1 14H1.5V6h2.6zM2.8 4.9A1.5 1.5 0 112.8 2a1.5 1.5 0 010 2.9zM14.5 14h-2.6V9.9c0-1-.3-1.7-1.2-1.7-.7 0-1.1.4-1.3 1a1.6 1.6 0 00-.1.6V14H6.8V6h2.5v1.1a2.6 2.6 0 012.3-1.3c1.7 0 2.9 1.1 2.9 3.4z"/></svg></a> -->
            </div>
            </div>

            <div class="foot__col">
            <h4>Company</h4>
            <ul>
                <li><a href="#">About us</a></li>
                <li><a href="#">Careers</a></li>
                <li><a href="#">Offers</a></li>
                <li><a href="#">Pricing</a></li>
                <li><a href="{{ route('frontend.partner.register') }}">Become partner</a></li>
                <li><a href="#">Contact us</a></li>
            </ul>
            </div>

            <div class="foot__col">
            <h4>Buy &amp; sell</h4>
            <ul>
                <!-- <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/listing.html?view=all">All used cars</a></li>
                <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/listing.html?view=all&section=new">New arrivals</a></li>
                <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/listing.html?view=all&section=featured">Featured cars</a></li>
                <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/listing.html?view=all&section=commercial">Mini commercial</a></li>
                <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/sell-your-car.html">Sell your car</a></li>
                <li><a href="https://shikharbhatnaggar.github.io/publicautoos360/request-free-assessment.html">Free assessment</a></li> -->
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'Hatchback']) }}">Hatchbacks</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'Sedan']) }}">Sedans</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'body' => 'SUV']) }}">SUVs</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Electric']) }}">Electric</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Diesel']) }}">Diesel</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'CNG']) }}">CNG</a></li>
                <li><a href="{{ route('frontend.vehicles.listing', ['view' => 'all', 'fuel' => 'Petrol']) }}">Petrol</a></li>
                <li><a href="#">Sell your car</a></li>
                <li><a href="#">Free assessment</a></li>
            </ul>
            </div>

            <div class="foot__col">
            <h4>Help &amp; support</h4>
            <ul>
                <li><a href="#">FAQs</a></li>
                <li><a href="#">Finance &amp; EMI</a></li>
                <!-- <li><a href="#">RC transfer status</a></li> -->
                <li><a href="#">Warranty terms</a></li>
                <li><a href="#">Privacy policy</a></li>
                <li><a href="#">Terms of use</a></li>
            </ul>
            </div>
        </div>

        <!-- 11 · Copyright -->
        <div class="copy">
            <div class="wrap copy__in">
            <p>&copy; <span id="year">{{ date('Y') }} </span> AutoOS360 Private Limited. All rights reserved. | Powered by <a href="https://shiventech.co.in">Shiventech Consulting</a></p>
            <div class="copy__links">
                <a href="#">Privacy</a><a href="#">Terms</a><a href="#">Cookies</a><a href="#">Sitemap</a>
            </div>
            </div>
        </div>
    </footer>

    
    <script src="{{ asset('frontend/js/home.js') }}"></script>
    @stack('scripts') {{-- Allows pages to stack target page controller script tags cleanly --}}
</body>
</html>
