@extends('frontend.layouts.app')

@section('title', $vehicle->make_year . ' ' . $vehicle->model . ' — AutoOS360')

@section('content')
<div class="wrap">
  <!-- Breadcrumb Navigation Links Block -->
  <nav class="crumbs" aria-label="Breadcrumb" style="padding-block: 16px;">
    <a href="{{ url('/') }}">Home</a> / 
    <a href="{{ $crumbList['href'] }}">{{ $crumbList['text'] }}</a> / 
    <strong>{{ $vehicle->model }}</strong>
  </nav>

  <!-- Primary Showcase Interface Grid Split System -->
  <div class="vd" id="vd">
    
    <!-- Left Column Feature Area: Media & Specifications Layout -->
    <div>
      <!-- Server-Side Rendered Image Gallery Block -->
      <div class="gallery">
        <div class="gallery__main">
            $car->display_image = 'https://shikharbhatnaggar.github.io/publicautoos360/'.$primaryImg['path'];
          <img id="gMain" alt="{{ $vehicle->make_year }} {{ $vehicle->model }}" src="{{ 'https://shikharbhatnaggar.github.io/publicautoos360/'.$vehicle->primary_image_path }}">
          <div class="gallery__flags">
            @if($vehicle->is_new_arrival)<span class="flag flag--new">New arrival</span>@endif
            @if($vehicle->is_featured)<span class="flag flag--feat">Featured</span>@endif
            <span class="flag flag--body">{{ $vehicle->body_type }}</span>
          </div>
        </div>
        
        <div class="gallery__thumbs" role="tablist" aria-label="Vehicle photos">
          @foreach($vehicle->images_collection as $index => $img)
            <button class="gthumb {{ $img['is_primary'] || ($index === 0 && !$vehicle->images_collection->contains('is_primary', true)) ? 'is-on' : '' }}" 
                    role="tab" type="button" 
                    data-fullsrc="{{ $img['path'] }}" 
                    title="{{ $img['type'] }} view">
              <img alt="{{ $img['type'] }} view" loading="lazy" src="{{ 'https://shikharbhatnaggar.github.io/publicautoos360/'.$img['path'] }}">
            </button>
          @endforeach
        </div>
      </div>

      <!-- Core Spec Overview Card Panel -->
      <div class="panel">
        <div class="vd__title"><h1>{{ $vehicle->make_year }} {{ $vehicle->model }}</h1></div>
        <p class="vd__subline">
          <span>{{ $vehicle->body_type }}</span><span class="dot"></span>
          <span>{{ $vehicle->formatted_km }}</span><span class="dot"></span>
          <span>{{ $vehicle->ownership }}</span><span class="dot"></span>
          <span>{{ $vehicle->registration_no ?? 'Unregistered' }}</span>
        </p>
        <div class="keyspecs">
          <div class="keyspec"><i>⚡</i><b>{{ $vehicle->fuel_type }}</b><span>Fuel type</span></div>
          <div class="keyspec"><i>⚙️</i><b>{{ $vehicle->transmission }}</b><span>Transmission</span></div>
          <div class="keyspec"><i>📊</i><b>{{ $vehicle->formatted_km }}</b><span>Odometer</span></div>
          <div class="keyspec"><i>👤</i><b>{{ $vehicle->ownership }}</b><span>Ownership</span></div>
        </div>
      </div>

      <!-- Tabular Specification Engine Details Block -->
      <div class="panel">
        <h2>Full specification</h2>
        <dl class="spectable">
          <div class="specrow"><dt>Model</dt><dd>{{ $vehicle->model }}</dd></div>
          <div class="specrow"><dt>Body type</dt><dd>{{ $vehicle->body_type }}</dd></div>
          <div class="specrow"><dt>Manufacture year</dt><dd>{{ $vehicle->make_year }}</dd></div>
          <div class="specrow"><dt>Kilometres driven</dt><dd>{{ $vehicle->formatted_km }}</dd></div>
          <div class="specrow"><dt>Fuel type</dt><dd>{{ $vehicle->fuel_type }}</dd></div>
          <div class="specrow"><dt>Transmission</dt><dd>{{ $vehicle->transmission }}</dd></div>
          <div class="specrow"><dt>Ownership</dt><dd>{{ $vehicle->ownership }}</dd></div>
          <div class="specrow">
            <dt>Engine capacity</dt>
            <dd>{{ $vehicle->fuel_type === 'Electric' ? 'Not applicable' : ($vehicle->engine_cc ? $vehicle->engine_cc . ' cc' : 'N/A') }}</dd>
          </div>
          @if($vehicle->engine_power_ps)
            <div class="specrow"><dt>Max power</dt><dd>{{ $vehicle->engine_power_ps }} PS</dd></div>
          @endif
          @if($vehicle->mileage_claimed)
            <div class="specrow">
              <dt>{{ $vehicle->fuel_type === 'Electric' ? 'Claimed range' : 'Claimed mileage' }}</dt>
              <dd>{{ $vehicle->mileage_claimed }}{{ $vehicle->fuel_type === 'Electric' ? ' km/charge' : ' kmpl' }}</dd>
            </div>
          @endif
          @if($vehicle->seating_capacity)
            <div class="specrow"><dt>Seating capacity</dt><dd>{{ $vehicle->seating_capacity }} seater</dd></div>
          @endif
          @if($vehicle->fuel_tank)
            <div class="specrow">
              <dt>{{ $vehicle->fuel_type === 'Electric' ? 'Battery' : 'Fuel tank' }}</dt>
              <dd>{{ $vehicle->fuel_type === 'Electric' ? 'High-voltage pack' : $vehicle->fuel_tank . ' litres' }}</dd>
            </div>
          @endif
          <div class="specrow"><dt>Colour</dt><dd>{{ $vehicle->colour }}</dd></div>
          <div class="specrow"><dt>Insurance</dt><dd>{{ $vehicle->insurance_type }}</dd></div>
          <div class="specrow"><dt>Stock status</dt><dd>{{ ucfirst($vehicle->status) }}</dd></div>
        </dl>
      </div>

      <!-- Dynamic Features Check List Block -->
      @if(!empty($vehicle->features))
        <div class="panel">
          <h2>Features</h2>
          <ul class="taglist">
            @foreach($vehicle->features as $feature)
              <li>✓ {{ $feature }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Verified Inspection Checkpoints Block -->
      @if(!empty($vehicle->inspection_highlights))
        <div class="panel">
          <h2>Inspection highlights</h2>
          <ul class="hilite">
            @foreach($vehicle->inspection_highlights as $highlight)
              <li><i>✓</i><span>{{ $highlight }}</span></li>
            @endforeach
          </ul>
        </div>
      @endif
    </div>

    <!-- Right Column Sidebar: Commercial Transaction & Interactive QR Actions -->
    <aside class="buybox">
      <div class="pricecard">
        <!-- High-Utility Local-Render QR Box -->
        <div class="qr-print-box" style="text-align:center; padding:20px; border:1.5px dashed var(--line); border-radius:var(--radius-l); margin-bottom:20px; background:#fff;">
          <p style="font-size:13px; font-weight:600; color:var(--ink); margin-bottom:12px;">Showroom QR</p> 
          <div id="jsQrTarget" style="display:flex; justify-content:center; margin:0 auto;"></div>
        </div>

        <p class="pricecard__label">Fixed price, no haggling</p>
        <p class="pricecard__amt">{{ $vehicle->formatted_price }}</p>
        <p class="pricecard__note">{{ $vehicle->formatted_price }} · RTO and insurance transfer included</p>
        
        <div class="emi">
          <i>₹</i>
          <div><b>₹{{ number_format($indicativeEmi) }}/month</b><span>60 months at 9.5% p.a., indicative</span></div>
        </div>
        
        <div class="pricecard__btns">
          <a class="btn btn--primary btn--block" href="{{ url('/book-test-drive?id=' . $vehicle->id) }}">Book a Test Drive</a>
          <a class="btn btn--ghost btn--block" href="{{ url('/finance') }}">Check finance eligibility</a>
        </div>
      </div>

      <!-- Trust Assurance Seals Panel Container -->
      <div class="assure">
        <h3>What you get with this car</h3>
        <ul style="display:grid; gap:10px">
          <li><i>🛡️</i>200-point inspection report</li>
          <li><i>🔄</i>7-day money-back promise</li>
          <li><i>📅</i>1-year warranty, extendable</li>
          <li><i>👤</i>Free RC transfer assistance</li>
        </ul>
      </div>

      <!-- Core Stock Inventory Database Records Summary Matrix -->
      <div class="idcard">
        <div><span>Stock ref</span><b>{{ $vehicle->sr_no }}</b></div>
        <div><span>Memo no.</span><b>{{ $vehicle->memo_no }}</b></div>
        <div><span>Listing ID</span><b>#{{ $vehicle->id }}</b></div>
        <div><span>Seats</span><b>{{ $vehicle->seating_capacity ?? 5 }}</b></div>
      </div>
    </aside>

  </div>
</div>
@endsection

@push('scripts')
<!-- Pure Client Side Offline Canvas QR Renderer Generation Script Asset Dependency -->
<script src="https://cloudflare.com" integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4vNcpmBAUs6QN4Qz35jnEXcNgm33vKPHW3lif7Dd64el3gA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // 1. High-Performance Thumbnail Photo Interactivity Toggles
    var mainImg = document.getElementById('gMain');
    var thumbs = document.querySelectorAll('.gthumb');

    thumbs.forEach(function (btn) {
      btn.addEventListener('click', function () {
        thumbs.forEach(t => t.classList.remove('is-on'));
        btn.classList.add('is-on');
        if (mainImg) {
          mainImg.src = btn.getAttribute('data-fullsrc');
        }
      });
    });

    // 2. Offline Showroom QR Engine Compiler Initialization Matrix
    var qrAnchor = document.getElementById('jsQrTarget');
    if (qrAnchor && typeof QRCode !== 'undefined') {
        // alert("{{ url('/showroom-scan/' . \Illuminate\Support\Str::slug($vehicle->model) . '-' . $vehicle->id) }}");
      new QRCode(qrAnchor, {
        text: "{{ url('/showroom-scan/' . \Illuminate\Support\Str::slug($vehicle->model) . '-' . $vehicle->id) }}",
        width: 120,
        height: 120,
        colorDark: "#0F1023", // Syncs seamlessly with CSS hex ink configuration parameter
        colorLight: "#FFFFFF",
        correctLevel: QRCode.CorrectLevel.H
      });
    }
  });

    // =========================================================================
    // PURE JAVASCRIPT QR GENERATOR (100% FREE FOREVER & OFFLINE)
    // =========================================================================
    var qrRenderAnchor = document.getElementById('jsQrTarget');
    if (qrRenderAnchor && typeof QRCode !== 'undefined') {
        new QRCode(qrRenderAnchor, {
            text: showroomTargetLink, // The string URL target text to compile
            width: 100,               // Dimension width parameters
            height: 100,              // Dimension height parameters
            colorDark: "#0F1023",     // Matches your theme variable "--ink" hex color value
            colorLight: "#FFFFFF",    // Background block isolation grid color
            correctLevel: QRCode.CorrectLevel.H // High error correction level for easy camera scanning
        });
        console.log('[DEBUG] Pure client-side QR code generated locally:', showroomTargetLink);
    } else {
        console.error('[DEBUG] qrcode.min.js library failed to load from CDN channels.');
    }
</script>
@endpush
