@extends('frontend.layouts.app')

@section('title', 'AutoOS360 — Buy Inspected Used Cars & Commercial Vehicles')

@section('content')
<!-- 3 · Hero Section -->
<section class="hero">
  <div class="wrap hero__in">
    <div>
      <span class="hero__eyebrow"><span class="hero__dot"></span>Live Stock Active</span>
      <h1>Find a car that fits your <em>lifestyle</em></h1>
      <p class="hero__sub">Browse fully certified, warranty-backed cars and passenger MUV fleets hosted across premium dealer lots near you.</p>
      <div class="hero__btns">
        <a class="btn btn--light" href="{{ route('frontend.vehicles.listing', ['view' => 'all']) }}">Explore all cars</a>
        <a class="btn btn--outline-light" href="{{ route('frontend.partner.register') }}">Sell as partner</a>
      </div>
      <div class="hero__stats">
        <div class="hero__stat"><b id="heroCount">{{ $totalAllCars }}</b><span>Cars Available</span></div>
        <div class="hero__stat">
          <b id="heroEmi">₹{{ number_format($heroEmi) }}/mo</b>
          <span>Indicative EMI entry benchmarks</span>
        </div>
      </div>
    </div>
    <div class="hero__art">
      <div class="hero__glow"></div>
      <img id="heroCar" class="hero__car" src="{{ asset('frontend/images/hero-car.png') }}" alt="Featured Banner Asset">
        <div class="hero__chip hero__chip--a">
          <i><svg viewBox="0 0 20 20" fill="none"><path d="M10 2.5l6 2.2v5c0 4-2.6 6.7-6 7.8-3.4-1.1-6-3.8-6-7.8v-5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"></path><path d="M7 10l2 2 4-4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
          <span>Accident-free<small>Verified history</small></span>
        </div>
        <div class="hero__chip hero__chip--b">
          <i><svg viewBox="0 0 20 20" fill="none"><path d="M6 4h8M6 7.5h8M6 4c4 0 5.5 1.4 5.5 3.5S10 11 6 11l7 5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round"></path></svg></i>
          <span id="heroEmi">EMI from ₹5,100<small>60 months, no guarantor</small></span>
        </div>
    </div>
  </div>
</section>

<!-- 4 · Vehicle Type Category Selector Selector Grid Layout -->
<section class="sec sec--tight">
  <div class="wrap">
    <div class="sec__head">
      <div>
        <h2 class="sec__title">Start with a body type</h2>
        <p class="sec__sub">Live inventory metric parameters compiled directly out of dealer lots.</p>
      </div>
      <a class="viewall" href="{{ route('frontend.vehicles.listing', ['view' => 'all']) }}">Browse all stock 
        <svg viewBox="0 0 16 16" fill="none" width="14" height="14"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>
      </a>
    </div>
    
    <div class="types" id="types">
      @foreach($bodyTypes as $t)
        <a class="type" href="{{ $t['href'] }}">
          <span class="type__ic">
            {!! $BODY_ART_MARKUP[$t['art']] ?? '' !!}
          </span>
          <span class="type__name">{{ $t['name'] }}</span>
          <span class="type__count">{{ $t['n'] }} available</span>
        </a>
      @endforeach
    </div>
  </div>
</section>

<!-- 5 · New Arrivals Live Carousel Track -->
<section class="sec sec--alt">
  <div class="wrap">
    <div class="sec__head">
      <div>
        <h2 class="sec__title">Fresh New Arrivals</h2>
        <p class="sec__sub">Inspected over the last fortnight — these high-demand options move quickly.</p>
      </div>
    </div>
    <div class="rail" data-rail>
      <div class="rail__track" id="railNew">
        @foreach($newArrivals as $v)
          <div role="listitem">
            @include('frontend.partials.vehicle-card', ['v' => $v])
          </div>
        @endforeach
      </div>
      <button class="rail__nav rail__nav--prev" type="button" aria-label="Previous">‹</button>
      <button class="rail__nav rail__nav--next" type="button" aria-label="Next">›</button>
    </div>
  </div>
</section>

<!-- 6 · Two promo banners -->
  <section class="sec">
    <div class="wrap duo">
      <article class="promo promo--sell" id="sell">
        <div class="promo__art"><img id="promoSell" alt="Car being handed over for sale" width="320" height="200"></div>
        <div class="promo__body">
          <span class="promo__kicker">Sell in 48 hours</span>
          <h3>Sell your car at a price you agree to</h3>
          <p>One inspection, one firm offer, payment the same day it's picked up.</p>
          <a class="btn btn--primary" href="#">Sell your car</a>
        </div>
      </article>

      <article class="promo promo--check" id="assess">
        <div class="promo__art"><img id="promoCheck" alt="Vehicle undergoing inspection" width="320" height="200"></div>
        <div class="promo__body">
          <span class="promo__kicker">Free · 45 minutes</span>
          <h3>Free assessment for your vehicle</h3>
          <p>A 200-point check at your home, with a written condition report you keep.</p>
          <a class="btn btn--primary" href="#">Book free assessment</a>
        </div>
      </article>
    </div>
  </section>

<!-- 7 · Featured Showroom Carousel Rail Layout -->
<section class="sec">
  <div class="wrap">
    <div class="sec__head">
      <div>
        <h2 class="sec__title">Featured Inspector Picks</h2>
        <p class="sec__sub">Low-owner, low-kilometre certified vehicles rated highest by our inspection teams.</p>
      </div>
    </div>
    <div class="rail" data-rail>
      <div class="rail__track" id="railFeatured">
        @foreach($featured as $v)
          <div role="listitem">
            @include('frontend.partials.vehicle-card', ['v' => $v])
          </div>
        @endforeach
      </div>
      <button class="rail__nav rail__nav--prev" type="button" aria-label="Previous">‹</button>
      <button class="rail__nav rail__nav--next" type="button" aria-label="Next">›</button>
    </div>
  </div>
</section>

<!-- 8 · Commercial Fleet Carousels Track -->
<section class="sec sec--alt" style="border-bottom: 1px solid var(--line);">
  <div class="wrap">
    <div class="sec__head">
      <div>
        <h2 class="sec__title">Mini Commercial Fleets</h2>
        <p class="sec__sub">High-capacity MUV passenger options built for long-route duty and high utility.</p>
      </div>
    </div>
    <div class="rail" data-rail>
      <div class="rail__track" id="railCommercial">
        @foreach($commercial as $v)
          <div role="listitem">
            @include('frontend.partials.vehicle-card', ['v' => $v])
          </div>
        @endforeach
      </div>
      <button class="rail__nav rail__nav--prev" type="button" aria-label="Previous">‹</button>
      <button class="rail__nav rail__nav--next" type="button" aria-label="Next">›</button>
    </div>
  </div>
</section>

<!-- 9 · Become partner CTA -->
  <section class="sec sec--tight" id="partner">
    <div class="wrap">
      <div class="partner">
        <div class="partner__body">
          <p class="partner__kicker">For dealers &amp; fleet owners</p>
          <h2>List your stock where buyers already are</h2>
          <p>Partner dealerships get inspection support, finance tie-ups and a listing feed that syncs with your DMS. No listing fee for the first 90 days.</p>
          <div class="partner__btns">
            <a class="btn btn--light" href="{{ route('frontend.pricing') }}">See Pricing</a>
            <a class="btn btn--outline-light" href="#">Talk to the team</a>
          </div>
        </div>
        <div class="partner__facts">
          <div class="partner__fact"><b>18 days</b><span>Average time to sell</span></div>
          <div class="partner__fact"><b>₹0</b><span>Listing fee for 90 days</span></div>
          <div class="partner__fact"><b>140+</b><span>Dealers already onboard</span></div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('scripts')
<script>
  // Preservation of legacy touch-slide/rail animations bindings across all rendered blocks
  document.querySelectorAll('[data-rail]').forEach(window.GS.initRail);
  document.querySelectorAll('.rail__nav--prev').forEach(function (b) { b.innerHTML = window.GS.icons.chevL; });
  document.querySelectorAll('.rail__nav--next').forEach(function (b) { b.innerHTML = window.GS.icons.chevR; });
</script>
@endpush
