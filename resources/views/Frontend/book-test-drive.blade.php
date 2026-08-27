@extends('frontend.layouts.app')

@section('title', 'Book a Test Drive — ' . $v->model)

@section('content')
<div class="wrap" style="padding-block: 28px;">
  <!-- Breadcrumb Navigation Component Tracking -->
  <nav class="crumbs" aria-label="Breadcrumb" style="padding-bottom: 20px;">
    <a href="{{ url('/') }}">Home</a> / <a href="{{ url('/listing') }}">Listing</a> / <strong>Test Drive</strong>
  </nav>

  <!-- Interactive Validation Toast Alerts Layer Blocks -->
  @if(session('success'))
    <div style="background: var(--green-bg); color: var(--green); padding: 14px 20px; border-radius: var(--radius); font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(18,183,106,0.2);">
      {{ session('success') }}
    </div>
  @endif

  @if(session('error'))
    <div style="background: #FCE8E6; color: var(--red); padding: 14px 20px; border-radius: var(--radius); font-weight: 600; margin-bottom: 24px; border: 1px solid rgba(224,72,59,0.2);">
      {{ session('error') }}
    </div>
  @endif

  <!-- Split Panel Flex Layout Sheet Columns Grid -->
  <div class="listing" style="margin-top: 0;">
    
    <!-- Left Sidebar Column: High Value Pre-rendered Car Preview Summary Card -->
    <aside class="filters" style="position: static; max-height: none; padding: 0; overflow: hidden; height: auto;">
      <article class="vcard" style="border: 0; border-radius: 0; transform: none; box-shadow: none;">
        <div class="vcard__media">
          <img src="{{ $v->display_image }}" alt="{{ $v->model }}" class="vcard__img">
        </div>
        <div class="vcard__body" style="padding: 20px;">
          <div class="vcard__year">{{ $v->make_year }} • {{ $v->ownership }}</div>
          <h2 class="vcard__name" style="font-size: 20px; min-height: auto; margin-bottom: 12px;">
            <a id="carLink" href="{{ $v->seo_url }}">{{ $v->model }}</a>
          </h2>
          <ul class="vcard__meta" style="margin-bottom: 16px;">
            <li class="pill pill--fuel">{{ $v->fuel_type }}</li>
            <li class="pill">{{ $v->transmission }}</li>
            <li class="pill">{{ number_format($v->km_driven) }} km</li>
          </ul>
          <div class="vcard__foot" style="padding-top: 16px;">
            <p class="vcard__price" style="margin: 0;">
              <b style="font-size: 22px;">₹{{ number_format($v->price) }}</b>
              <span>Fixed Price Assurance</span>
            </p>
          </div>
        </div>
      </article>
    </aside>

    <!-- Right Column: Clean Native HTML Validation Submission Form Layout -->
    <main class="panel" style="margin-top: 0; padding: 32px;">
      <h2 style="font-size: 24px; margin-bottom: 6px;">Book a Test Drive</h2>
      <p style="color: var(--muted); font-size: 14px; margin-bottom: 28px;">
        Enter your details below. The dealership hosting this vehicle will contact you to schedule your slot.
      </p>

      <form action="{{ route('frontend.vehicles.test-drive.submit') }}" method="POST">
        @csrf
        <!-- Secure Entity Identifier References -->
        <input type="hidden" name="vehicle_id" value="{{ $v->id }}">

        <div style="margin-bottom: 20px;">
          <label for="fullName" style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--ink-2);">Your Full Name</label>
          <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}" required 
                 style="width: 100%; height: 44px; border: 1px solid @error('fullName') var(--red) @else var(--line) @enderror; border-radius: 8px; padding-inline: 14px; background: #fff;" placeholder="Enter first and last name">
          @error('fullName') <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label for="mobileNo" style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--ink-2);">Mobile Number</label>
          <input type="tel" id="mobileNo" name="mobileNo" value="{{ old('mobileNo') }}" required pattern="[0-9]{10}"
                 style="width: 100%; height: 44px; border: 1px solid @error('mobileNo') var(--red) @else var(--line) @enderror; border-radius: 8px; padding-inline: 14px; background: #fff;" placeholder="10-digit mobile number">
          @error('mobileNo') <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <div style="margin-bottom: 20px;">
          <label for="emailAddr" style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--ink-2);">Email Address (Optional)</label>
          <input type="email" id="emailAddr" name="emailAddr" value="{{ old('emailAddr') }}"
                 style="width: 100%; height: 44px; border: 1px solid @error('emailAddr') var(--red) @else var(--line) @enderror; border-radius: 8px; padding-inline: 14px; background: #fff;" placeholder="name@example.com">
          @error('emailAddr') <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>
        
        <div style="margin-bottom: 24px;">
          <label for="buyerMsg" style="display: block; font-size: 13.5px; font-weight: 600; margin-bottom: 6px; color: var(--ink-2);">Message / Specific Requirements (Optional)</label>
          <textarea id="buyerMsg" name="buyerMsg" rows="4" 
                    style="width: 100%; border: 1px solid @error('buyerMsg') var(--red) @else var(--line) @enderror; border-radius: 8px; padding: 12px 14px; background: #fff; font-family: inherit; resize: vertical;" 
                    placeholder="Preferred time slots, query about document transfers, etc.">{{ old('buyerMsg') }}</textarea>
          @error('buyerMsg') <span style="color: var(--red); font-size: 12px; margin-top: 4px; display: block;">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn--primary" style="height: 46px; padding-inline: 32px; font-size: 15px;">Make Request</button>
      </form>
    </main>

  </div>
</div>
@endsection
