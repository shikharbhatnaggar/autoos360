@extends('frontend.layouts.app')

@section('title', 'Become a Partner — AutoOS360')

@section('content')
<div class="wrap">
  <!-- Breadcrumbs Track Navigation Links -->
  <nav class="crumbs" aria-label="Breadcrumb">
    <a href="{{ url('/') }}">Home</a> <span>/</span> <strong>Become Partner</strong>
  </nav>

  <!-- Interactive Dynamic Validation Message Blocks Layer -->
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

  <!-- Refined Presentation Columns Layout Structure -->
  <div class="partner-split">
    
    <!-- Left Column: Premium Value Pitch Banner Gradient Card -->
    <section class="partner-banner">
      <p class="partner-hero-card__kicker">For Dealers & Fleet Owners</p>
      <h1 class="partner-hero-card__title">List your stock where buyers already are.</h1>
      <p class="partner-hero-card__sub">
        Partner dealerships get 200-point inspection support, priority finance approvals, and a tracking listing feed that integrates with your current inventory DMS backend systems.
      </p>
      
      <ul class="bullets">
        <li>
          <span class="partner-hero-card__check">✓</span>
          <div><b>18 Days Avg. Turnaround</b> — Sell your tracked warehouse cars up to three times faster than offline classified listings.</div>
        </li>
        <li>
          <span class="partner-hero-card__check">✓</span>
          <div><b>₹0 Initial Setup Fees</b> — Enjoy free unlimited inventory sync and multi-image storage uploads for your first 90 days.</div>
        </li>
        <li>
          <span class="partner-hero-card__check">✓</span>
          <div><b>140+ Dealers Active</b> — Join an accredited aggregator community covering major commercial regions across India.</div>
        </li>
      </ul>
    </section>

    <!-- Right Column: White Sheet Form Onboarding Processing Panel -->
    <main class="partner-form-card">
      <h2 class="partner-form-card__title">Become Our Partner</h2>
      <p class="partner-form-card__subtitle">
        Submit your dealership information, and our regional team will call you within 24 business hours.
      </p>

      <form action="{{ route('frontend.partner.store') }}" method="POST" id="partnerForm">
        @csrf
        <input type="hidden" name="stateNameHidden" id="stateNameHidden" value="{{ old('stateNameHidden') }}">

        <!-- Dynamic Subscription Package Selector Row -->
        <div class="form-group-wrap">
          <label class="form-label">Select Your Partnership Plan</label>
          <div class="tier-grid">
            
            <label class="tier-card {{ old('selected_plan', $activePlan) === 'free' ? 'is-active' : '' }}">
              <input type="radio" name="selected_plan" value="free" {{ old('selected_plan', $activePlan) === 'free' ? 'checked' : '' }} onchange="toggleTierHighlight(this)">
              <span class="tier-card__name">Starter / Free</span>
              <span class="tier-card__price">₹0 <span>/ Year</span></span>
              <span class="tier-card__meta">6% Closing Fee • 7 Cars Max</span>
            </label>

            <label class="tier-card {{ old('selected_plan', $activePlan) === 'pkg2' ? 'is-active' : '' }}">
              <input type="radio" name="selected_plan" value="pkg2" {{ old('selected_plan', $activePlan) === 'pkg2' ? 'checked' : '' }} onchange="toggleTierHighlight(this)">
              <span class="tier-card__name">Pro Growth</span>
              <span class="tier-card__price">₹8,400 <span>/ Year</span></span>
              <span class="tier-card__meta">3% Closing Fee • 30 Cars Max</span>
            </label>

            <label class="tier-card {{ old('selected_plan', $activePlan) === 'pkg3' ? 'is-active' : '' }}">
              <input type="radio" name="selected_plan" value="pkg3" {{ old('selected_plan', $activePlan) === 'pkg3' ? 'checked' : '' }} onchange="toggleTierHighlight(this)">
              <span class="tier-card__name">Elite Pro</span>
              <span class="tier-card__price">₹24,000 <span>/ Year</span></span>
              <span class="tier-card__meta">1% Closing Fee • Unlimited Cars</span>
            </label>

          </div>
        </div>

        <!-- Single Column Layout Field Row -->
        <div class="form-group-wrap">
          <label for="fullName" class="form-label">Dealership / Owner Name</label>
          <input type="text" id="fullName" name="fullName" value="{{ old('fullName') }}" required class="form-input" placeholder="e.g. Apex Premium Wheels">
        </div>

        <!-- Dual Columns Nested Row Structure Block -->
        <div class="form-row-split">
          <div class="form-group-wrap">
            <label for="phoneNo" class="form-label">Phone Number</label>
            <input type="tel" id="phoneNo" name="phoneNo" value="{{ old('phoneNo') }}" required pattern="[0-9]{10}" class="form-input" placeholder="10-digit mobile number">
          </div>

          <div class="form-group-wrap">
            <label for="emailAddr" class="form-label">Email Address <span class="form-label__opt">(Optional)</span></label>
            <input type="email" id="emailAddr" name="emailAddr" value="{{ old('emailAddr') }}" class="form-input" placeholder="name@dealership.com">
          </div>
        </div>

        <!-- Single Column Website Layout Field Row -->
        <div class="form-group-wrap">
          <label for="webUrl" class="form-label">Website URL <span class="form-label__opt">(Optional)</span></label>
          <input type="url" id="webUrl" name="webUrl" value="{{ old('webUrl') }}" class="form-input" placeholder="https://yourdealerwebsite.com">
        </div>

        <!-- Location Dropdowns Block split -->
        <div class="form-row-split">
          <div class="form-group-wrap">
            <label for="countryStatic" class="form-label">Country</label>
            <input type="text" id="countryStatic" value="India" readonly class="form-input form-input--disabled">
          </div>

          <div class="form-group-wrap">
            <label for="stateSel" class="form-label">State</label>
            <select id="stateSel" name="stateCode" required class="form-select">
              <option value="" disabled selected>Select State</option>
              @foreach($statesList as $state)
                <option value="{{ $state['iso2'] }}" {{ old('stateCode') === $state['iso2'] ? 'selected' : '' }}>{{ $state['name'] }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <!-- Nested Dropdown Block Area -->
        <div class="form-row-split" style="margin-bottom: 28px;">
          <div class="form-group-wrap">
            <label for="citySel" class="form-label">City</label>
            <select id="citySel" name="cityName" required disabled class="form-select">
              <option value="" disabled selected>Select City</option>
            </select>
          </div>
          <div class="form-group-wrap type-desktop"></div> <!-- Structural element container to align items evenly -->
        </div>

        <button type="submit" class="btn btn--primary btn--block partner-submit-btn">Submit Application</button>
      </form>
    </main>

  </div>
</div>
@endsection

@push('scripts')
<script>
  /**
   * Toggle visual selection highlight states on radio target containers dynamically
   */
  function toggleTierHighlight(radioNode) {
    document.querySelectorAll('.tier-card').forEach(function(card) {
      card.classList.remove('is-active');
    });
    if (radioNode.checked) {
      radioNode.closest('.tier-card').classList.add('is-active');
    }
  }

  document.addEventListener('DOMContentLoaded', function () {
    var stateSelect = document.getElementById('stateSel');
    var citySelect = document.getElementById('citySel');
    var hiddenStateName = document.getElementById('stateNameHidden');
    
    var CSC_API_KEY = '136b81a1ddecfa599361f165fd9cf5a2da3dae84ed88469e3b888d8d5cf814fc'; 
    var DEFAULT_COUNTRY = 'IN';
    
    /**
     * 2. Asynchronous State Fetch Handler
     */
    async function loadStatesPipeline(countryCode) {
        try {
        stateSelect.innerHTML = '<option value="" disabled selected>Loading States...</option>';
        
        var response = await fetch('https://api.countrystatecity.in/v1/countries/' + countryCode + '/states', {
            headers: { 'X-CSCAPI-KEY': CSC_API_KEY } // Decodes transit verification headers
        });

        if (!response.ok) throw new Error('Network responded with an evaluation error.');
        var statesList = await response.json();

        // Format clean listing options mapping items inside dropdown node layouts
        stateSelect.innerHTML = '<option value="" disabled selected>Select State</option>';
        statesList.sort((a, b) => a.name.localeCompare(b.name)).forEach(function (stateItem) {
            var opt = document.createElement('option');
            opt.value = stateItem.iso2; // Store standard short ISO token identifier
            opt.textContent = stateItem.name;
            stateSelect.appendChild(opt);
        });
        } catch (err) {
        console.error('Failed to parse regional state array bounds data:', err);
        stateSelect.innerHTML = '<option value="" disabled selected>Error loading states</option>';
        }
    }
    
    loadStatesPipeline(DEFAULT_COUNTRY);

    async function loadCitiesPipeline(countryCode, stateCode) {
        
        try {
        citySelect.disabled = true;
        citySelect.innerHTML = '<option value="" disabled selected>Loading Cities...</option>';

        var response = await fetch('https://api.countrystatecity.in/v1/countries/' + countryCode + '/states/' + stateCode + '/cities', {
            headers: { 'X-CSCAPI-KEY': CSC_API_KEY }
        });

        if (!response.ok) throw new Error('City channel transmission failed.');
        var citiesList = await response.json();

        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        
        if (citiesList.length === 0) {
            var opt = document.createElement('option');
            opt.value = "Other";
            opt.textContent = "Other / General";
            citySelect.appendChild(opt);
        } else {
            citiesList.sort((a, b) => a.name.localeCompare(b.name)).forEach(function (cityItem) {
            var opt = document.createElement('option');
            opt.value = cityItem.name;
            opt.textContent = cityItem.name;
            citySelect.appendChild(opt);
            });
        }
        citySelect.disabled = false;
        } catch (err) {
        console.error('Failed to isolate municipal bounds criteria items:', err);
        citySelect.innerHTML = '<option value="" disabled selected>Error loading cities</option>';
        }
    }

    /**
     * 4. Interactive Dropdown Selection Listeners
     */
    if (stateSelect) {
        stateSelect.addEventListener('change', function () {
        var selectedStateCode = stateSelect.value;
        if (selectedStateCode) {
            loadCitiesPipeline(DEFAULT_COUNTRY, selectedStateCode);
        }
        });
    }

    @if(old('stateCode'))
      hiddenStateName.value = stateSelect.options[stateSelect.selectedIndex].text;
      updateCitiesList("{{ old('stateCode') }}", "{{ old('cityName') }}");
    @endif
  });
</script>
@endpush
