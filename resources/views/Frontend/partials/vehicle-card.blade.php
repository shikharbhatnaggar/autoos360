<article class="vcard">
  <a class="vcard__link" href="{{ $v->seo_url }}">
    <div class="vcard__media">
      <img src="{{ $v->display_image }}" alt="{{ $v->model }}" class="vcard__img" loading="lazy">
      <div class="vcard__flags">
        @if($v->is_new_arrival)<span class="flag flag--new">New Arrival</span>@endif
        @if($v->is_featured)<span class="flag flag--feat">Featured</span>@endif
      </div>
    </div>
  </a>
  <div class="vcard__body">
    <div class="vcard__year">{{ $v->make_year }} • {{ $v->ownership }}</div>
    <h3 class="vcard__name"><a href="{{ $v->seo_url }}">{{ $v->model }}</a></h3>
    <ul class="vcard__meta">
      <li class="pill pill--fuel">{{ $v->fuel_type }}</li> 
      <li class="pill">{{ $v->transmission }}</li> 
      <li class="pill">{{ number_format($v->km_driven) }} km</li>
    </ul>
    <div class="vcard__foot">
      <p class="vcard__price"><b>₹{{ number_format($v->price) }}</b><span>EMI from ₹13,000/mo</span></p>
      <a class="vcard__go" href="{{ $v->seo_url }}" aria-label="View {{ $v->model }}">
        <svg viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3 8h10M9 4l4 4-4 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"></path></svg>
      </a>
    </div>
  </div>
</article>
