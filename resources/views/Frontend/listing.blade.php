@extends('frontend.layouts.app')

@section('title', $cfg['title'] . ' — AutoOS360')

@section('content')

<!-- 🌟 LAYER 1: CONTENT BREADCRUMBS WRAPPER -->
<div class="wrap">
  <nav class="crumbs" aria-label="Breadcrumb" style="padding-bottom: 12px;">
    <a href="{{ url('/') }}">Home</a> / <strong>{{ $cfg['title'] }}</strong>
  </nav>
</div>

<!-- 🌟 LAYER 2: FULL-BLEED LIFESTYLE HERO BANNER (ANCHORED GRACEFULLY OVER THE SUNSET ASSET) -->
<div class="listhead-banner-container-lifestyle">
    <div class="wrap">
        <header class="listhead-banner-refined-glass font-sans">
            <div class="listhead-banner-refined-glass__content">
                <h1>{{ $cfg['title'] }}</h1>
                <p>{{ $cfg['sub'] }}</p>
            </div>
        </header>
    </div>
</div>

<div class="wrap">
  

  <!-- 1. Styled Slim Listing Hero Banner -->
  <!-- <header class="listhead-banner">
    <div class="listhead-banner__cta">
      <span class="hero__eyebrow"><span class="hero__dot"></span>Live Stock Active</span>
    </div>
  </header> -->

  <!-- Global Filter & Sorting Form Wrap Wrapper -->
  <form id="filterForm" method="GET" action="{{ url()->current() }}">
    <!-- Retain active view sub-context -->
    <input type="hidden" name="view" value="{{ $view }}">

    <!-- 2 & 3. Optimized Control Toolbar Panel Layout -->
    <div class="bar bar--refined">
      <div class="bar__left">
        <!-- Attractive Live Pill Counter Style -->
        <span class="pill-counter">
          <span class="pill-counter__num">{{ $vehicles->total() }}</span>
          <span class="pill-counter__txt">{{ Str::plural('car', $vehicles->total()) }} available</span><span class="hero__eyebrow"><span class="hero__dot"></span>Live Stock Active</span>
        </span>
        
        <!-- Active Filter Breadcrumb Chips Layout (SSR Powered) -->
        <!-- Locate the Active Filter Breadcrumb Chips loop in your file and update the array: -->
        <div class="chips" style="margin-bottom: 0;">
        @if($maxPriceInput < $priceCeil)
            <span class="chip">Under ₹{{ number_format($maxPriceInput) }}
            <button type="button" onclick="clearPriceFilter()">×</button>
            </span>
        @endif
        @foreach(['city', 'body', 'make', 'year', 'fuel', 'transmission', 'ownership'] as $group)
            @if(request()->has($group))
            @foreach((array) request($group) as $value)
                <span class="chip">{{ ucfirst($group === 'city' ? 'Location' : $group) }}: {{ $value }}
                <button type="button" onclick="removeFilter('{{ $group }}', '{{ $value }}')">×</button>
                </span>
            @endforeach
            @endif
        @endforeach
        </div>


      </div>

      <!-- Aligned Dropdown cleanly to the right side -->
      <div class="bar__right">
        <!-- <button class="btn btn--outline btn--sm type-mobile" id="openFilters" type="button">Filters</button> -->
        <div class="sortsel-wrapper type-desktop">
          <label for="sort" class="sortsel-label">Sort by</label>
          <select id="sort" name="sort" class="sortsel" onchange="this.form.submit()">
            <option value="new" {{ $sort === 'new' ? 'selected' : '' }}>Newest first</option>
            <option value="price-asc" {{ $sort === 'price-asc' ? 'selected' : '' }}>Price: low to high</option>
            <option value="price-desc" {{ $sort === 'price-desc' ? 'selected' : '' }}>Price: high to low</option>
            <option value="km-asc" {{ $sort === 'km-asc' ? 'selected' : '' }}>Kilometres: low to high</option>
            <option value="year-desc" {{ $sort === 'year-desc' ? 'selected' : '' }}>Year: newest first</option>
          </select>
        </div>
      </div>
    </div>

    <div class="listing">
      <!-- Left Sidebar: Filter Form Sheet Components -->
      <aside id="filters" class="filters">
        <!-- <div class="filters__head type-mobile">
          <h3>Filters</h3>
          <button id="closeFilters" type="button">×</button>
        </div> -->

        <div class="filters__body">
          <!-- <div class="filters__clear-wrap">
            <a href="{{ url()->current() }}?view={{ $view }}" class="btn-clear">Clear all filters</a>
          </div> -->

          <!-- Dynamic Pricing Range Component Tracker -->
          <div class="fgroup">
            <div class="fgroup__head" aria-expanded="true"><h4>Price Range</h4></div>
            <div class="fgroup__content">
              <div class="price-output">Up to <b id="priceOut">₹{{ number_format($maxPriceInput) }}</b></div>
              <input type="range" name="max_price" id="priceMax" min="{{ $priceFloor }}" max="{{ $priceCeil }}" step="25000" value="{{ $maxPriceInput }}" class="slider" onchange="this.form.submit()" oninput="document.getElementById('priceOut').textContent = '₹' + Number(this.value).toLocaleString('en-IN')">
              <div class="price-bounds">
                <span>₹{{ number_format($priceFloor) }}</span>
                <span>₹{{ number_format($priceCeil) }}</span>
              </div>
            </div>
          </div>

            <!-- 1. Body Type -->
            <div class="fgroup">
                <div class="fgroup__head" aria-expanded="true">
                <h4>Body Type</h4>
                <svg viewBox="0 0 12 12" width="12"><path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>
                </div>
                <div class="fgroup__body fscroll">
                @foreach($filterCounts['body'] ?? [] as $key => $count)
                    @php $checked = in_array($key, (array) request('body')) ? 'checked' : ''; @endphp
                    <label class="fopt">
                    <input type="checkbox" name="body[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                    <span>{{ $key }}</span><em>{{ $count }}</em>
                    </label>
                @endforeach
                </div>
            </div>

            <!-- 2. Make Brand -->
            <div class="fgroup">
                <div class="fgroup__head" aria-expanded="true">
                    <h4>Make Brand</h4>
                    <svg viewBox="0 0 12 12" width="12"><path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>
                </div>
                <div class="fgroup__body fscroll">
                @foreach($filterCounts['brand'] ?? [] as $key => $count)
                    @php $checked = in_array($key, (array) request('make')) ? 'checked' : ''; @endphp
                    <label class="fopt">
                    <input type="checkbox" name="make[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                    <span>{{ $key }}</span><em>{{ $count }}</em>
                    </label>
                @endforeach
                </div>
            </div>

            <!-- 3. Model Year -->
            <div class="fgroup">
                <div class="fgroup__head" aria-expanded="true">
                    <h4>Model Year</h4>
                </div>
                <div class="fgroup__body fscroll">
                @foreach($filterCounts['make_year'] ?? [] as $key => $count)
                    @php $checked = in_array($key, (array) request('year')) ? 'checked' : ''; @endphp
                    <label class="fopt">
                    <input type="checkbox" name="year[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                    <span>{{ $key }}</span><em>{{ $count }}</em>
                    </label>
                @endforeach
                </div>
            </div>

            <!-- 4. Fuel Type -->
            <div class="fgroup">
                <div class="fgroup__head" aria-expanded="true">
                    <h4>Fuel Type</h4>
                </div>
                <div class="fgroup__body fscroll">
                @foreach($filterCounts['fuel_type'] ?? [] as $key => $count)
                    @php $checked = in_array($key, (array) request('fuel')) ? 'checked' : ''; @endphp
                    <label class="fopt">
                    <input type="checkbox" name="fuel[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                    <span>{{ $key }}</span><em>{{ $count }}</em>
                    </label>
                @endforeach
                </div>
            </div>

          <!-- 5. Transmission -->
          <div class="fgroup">
            <div class="fgroup__head" aria-expanded="true"><h4>Transmission</h4></div>
            <div class="fgroup__body fscroll">
              @foreach($filterCounts['transmission'] ?? [] as $key => $count)
                @php $checked = in_array($key, (array) request('transmission')) ? 'checked' : ''; @endphp
                <label class="fopt">
                  <input type="checkbox" name="transmission[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                  <span>{{ $key }}</span><em>{{ $count }}</em>
                </label>
              @endforeach
            </div>
          </div>

          <!-- 6. Ownership History -->
          <div class="fgroup">
            <div class="fgroup__head" aria-expanded="true"><h4>Ownership</h4></div>
            <div class="fgroup__body fscroll">
              @foreach($filterCounts['ownership'] ?? [] as $key => $count)
                @php $checked = in_array($key, (array) request('ownership')) ? 'checked' : ''; @endphp
                <label class="fopt">
                  <input type="checkbox" name="ownership[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                  <span>{{ $key }}</span><em>{{ $count }}</em>
                </label>
              @endforeach
            </div>
          </div>


          <div class="fgroup">
            <div class="fgroup__head" aria-expanded="true"><h4>Ownership</h4></div>
            <div class="fgroup__body fscroll">
              @foreach($filterCounts['ownership'] ?? [] as $key => $count)
                @php $checked = in_array($key, (array) request('ownership')) ? 'checked' : ''; @endphp
                <label class="fopt">
                  <input type="checkbox" name="ownership[]" value="{{ $key }}" {{ $checked }} onchange="this.form.submit()">
                  <span>{{ $key }}</span><em>{{ $count }}</em>
                </label>
              @endforeach
            </div>
          </div>

            <!-- ==========================================
                DEALERSHIP CITY LOCATION ACCORDION FILTER 
            =========================================== -->
            <div class="fgroup">
                <div class="fgroup__head" aria-expanded="true">
                    <h4>City Location</h4>
                    <svg viewBox="0 0 12 12" width="12"><path d="M2 4.5L6 8.5L10 4.5" stroke="currentColor" stroke-width="1.6" fill="none"/></svg>
                </div>
                <div class="fgroup__body fscroll">
                @foreach($filterCounts['city'] ?? [] as $cityName => $count)
                    @if(!empty(trim($cityName)))
                        @php 
                            $checked = in_array($cityName, (array) request('city')) ? 'checked' : ''; 
                        @endphp
                        <label class="fopt">
                            <input type="checkbox" name="city[]" value="{{ $cityName }}" {{ $checked }} onchange="this.form.submit()">
                            <span>{{ $cityName }}</span>
                            <em>{{ $count }}</em>
                        </label>
                    @endif
                @endforeach
                </div>
            </div>
        </div>
      </aside>

      <!-- Right Feed Grid Output Block area -->
        <main class="grid">
            @if($vehicles->count() > 0)
            @foreach($vehicles as $v)
                @include('frontend.partials.vehicle-card', ['v' => $v])
            @endforeach
            
            <div class="pagination-wrap" style="grid-column: 1 / -1;">
                {{ $vehicles->links() }}
            </div>
            @else
            <div class="empty" style="grid-column: 1 / -1;">
                <h3>No cars match these filters</h3>
                <p>Widen your range bounds or reset criteria parameters to evaluate alternative stocks.</p>
                <a href="{{ url()->current() }}?view={{ $view }}" class="btn btn--primary">Reset All Filters</a>
            </div>
            @endif
        </main>
    </div>
  </form>
</div>
@endsection

@push('scripts')
<script>
  // Simplified Native Interactive Sheet Handlers
  document.querySelectorAll('.fgroup__head').forEach(function (h) {
    h.addEventListener('click', function () {
      var closed = h.closest('.fgroup').classList.toggle('is-closed');
      h.setAttribute('aria-expanded', closed ? 'false' : 'true');
    });
  });

  // Mobile Drawer Toggle Blocks
  var panel = document.getElementById('filters');
  document.getElementById('openFilters')?.addEventListener('click', function () { 
    panel.classList.add('is-open'); 
    document.body.style.overflow = 'hidden'; 
  });
  document.getElementById('closeFilters')?.addEventListener('click', function () { 
    panel.classList.remove('is-open'); 
    document.body.style.overflow = ''; 
  });

  // Dynamic Chip Interaction Removal Subroutine Helpers
  function removeFilter(name, value) {
    var form = document.getElementById('filterForm');
    var checkbox = form.querySelector('input[name="' + name + '[]"][value="' + value + '"]');
    if (checkbox) {
      checkbox.checked = false;
      form.submit();
    }
  }

  function clearPriceFilter() {
    var form = document.getElementById('filterForm');
    var priceSlider = document.getElementById('priceMax');
    if (priceSlider) {
      priceSlider.value = priceSlider.max;
      form.submit();
    }
  }
</script>
@endpush
