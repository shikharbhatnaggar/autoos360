@extends('frontend.layouts.app')

@section('title', 'Become a Partner — AutoOS360')

@push('styles')
<style>
    /* Custom style overrides matching your core typography tokens */
    .pkg-hero { text-align: center; padding-block: 52px 38px; background: var(--bg-alt); border-bottom: 1px solid var(--line); }
    .pkg-hero h1 { font-size: clamp(26px, 3.8vw, 38px); font-weight: 700; margin-bottom: 10px; }
    .pkg-hero p { color: var(--muted); font-size: 15.5px; max-width: 62ch; margin-inline: auto; line-height: 1.6; }
    
    /* Optimized 3-Column Plan Layout Box Constraints */
    .pricing-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 24px; padding-block: 48px; align-items: stretch; }
    .pcard { background: #fff; border: 1px solid var(--line); border-radius: var(--radius-l); padding: 36px 28px; display: flex; flex-direction: column; position: relative; transition: transform 0.2s, box-shadow 0.2s; }
    .pcard:hover { transform: translateY(-4px); box-shadow: var(--shadow-l); }
    
    /* Ultimate high conversion anchor treatment for Package 3 */
    .pcard--featured { border-color: var(--brand); box-shadow: var(--shadow); background: #fff; }
    .pcard--featured::before { content: "POPULAR"; position: absolute; top: -12px; left: 50%; transform: translateX(-50%); background: var(--brand); color: #fff; font-size: 10.5px; font-weight: 700; padding: 5px 14px; border-radius: 999px; letter-spacing: 0.06em; }
    
    .pcard__name { font-family: var(--font-display); font-size: 19px; font-weight: 700; margin-bottom: 6px; color: var(--ink); }
    .pcard__price { margin-bottom: 24px; display: flex; align-items: baseline; gap: 4px; }
    .pcard__price var { font-family: var(--font-display); font-size: 36px; font-weight: 700; font-style: normal; color: var(--ink); letter-spacing: -0.02em; }
    .pcard__price span { font-size: 13.5px; color: var(--muted); }
    
    .pcard__features { display: grid; gap: 14px; margin-bottom: 34px; padding-top: 20px; border-top: 1px dashed var(--line); }
    .pcard__features li { display: flex; gap: 11px; font-size: 14px; color: var(--ink-2); align-items: flex-start; line-height: 1.4; }
    .pcard__features i { color: var(--green); font-weight: bold; font-style: normal; flex: none; font-size: 15px; }
    
    /* Strike-through feature modifier rules */
    .pcard__features .strike { color: var(--muted-2); }
    .pcard__features .strike span { text-decoration: line-through; opacity: 0.65; color: var(--muted); }
    .pcard__features .strike i { color: var(--muted-2); opacity: 0.5; }
    
    /* Interactive Live ROI Calculator Dashboard Widgets Styles */
    .roi-box { background: var(--brand-tint); border: 1px solid var(--brand-tint-2); border-radius: var(--radius-l); padding: 28px; margin-bottom: 48px; display: grid; grid-template-columns: 1.1fr 0.9fr; gap: 32px; align-items: center; }
    .roi-left h3 { font-size: 20px; font-weight: 700; margin-bottom: 6px; color: var(--brand-ink); }
    .roi-left p { font-size: 13.5px; color: var(--ink-2); margin-bottom: 16px; }
    .slider-container { display: flex; flex-direction: column; gap: 8px; }
    .slider-label { display: flex; justify-content: space-between; font-weight: 600; font-size: 14px; }
    .slider-input { width: 100%; accent-color: var(--brand); cursor: pointer; height: 6px; background: var(--line); border-radius: 3px; appearance: none; }
    .roi-right { background: #fff; padding: 20px; border-radius: var(--radius); text-align: center; border: 1px solid var(--brand-tint-2); box-shadow: var(--shadow-s); }
    .roi-right small { font-size: 12px; text-transform: uppercase; font-weight: 700; color: var(--muted); letter-spacing: 0.05em; }
    .roi-right div { font-family: var(--font-display); font-size: 32px; font-weight: 700; color: var(--green); margin-block: 4px; }
    .roi-right p { font-size: 12.5px; color: var(--ink-2); margin: 0; }

    @media (max-width: 960px) { .pricing-grid { grid-template-columns: 1fr; gap: 28px; } .roi-box { grid-template-columns: 1fr; gap: 20px; } }
</style>
@endpush

@section('content')
<div class="wrap">
    <!-- Breadcrumbs Track Navigation Links -->
    <nav class="crumbs" aria-label="Breadcrumb">
        <a href="{{ url('/') }}">Home</a> <span>/</span> <strong>Pricing</strong>
    </nav>

    <main class="wrap">
        <div class="pricing-grid">
        
        <!-- Package 1: Free Tier -->
        <article class="pcard">
            <h2 class="pcard__name">Starter</h2>
            <div class="pcard__price"><var>₹0</var> <span>/ Year</span></div>
            <ul class="pcard__features">
            <li><i>✓</i> <span><b>7 Cars</b> active listing capacity</span></li>
            <li><i>✓</i> <span><b>1 Shed</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>7 Brokers</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>6% Processing Fee</b> on closure</span></li>
            <li><i>✓</i> <span>Standard lead delivery alerts</span></li>
            <li><i>✓</i> <span>Dedicated CRM access</span></li>
            <li><i>✓</i> <span>1 User panel access</span></li>
            <li class="strike"><i>×</i> <span>Dedicated Listing Page</span></li>
            <li class="strike"><i>×</i> <span>Verified buyer badge tags</span></li>
            <li class="strike"><i>×</i> <span>Dedicated CRM priority support</span></li>
            <li class="strike"><i>×</i> <span>AI Enabled Smart Leads</span></li>
            </ul>
            <a href="http://localhost/autoos360/become-partner.html?plan=free" class="btn btn--ghost btn--block">Start Free Listing</a>
        </article>

        <!-- Package 2: Mid Tier -->
        <article class="pcard pcard--featured">
            <h2 class="pcard__name">Growth</h2>
            <div class="pcard__price"><var>₹8,400</var> <span>/ Year</span></div>
            <ul class="pcard__features">
            <li><i>✓</i> <span><b>30 Cars</b> active listing capacity</span></li>
            <li><i>✓</i> <span><b>5 Sheds</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>30 Brokers</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>3% Processing Fee</b> on closure</span></li>
            <li><i>✓</i> <span>Standard lead delivery alerts</span></li>
            <li><i>✓</i> <span>Dedicated CRM access</span></li>
            <li><i>✓</i> <span>3 Users panel access</span></li>
            <li class="strike"><i>×</i> <span>Dedicated Listing Page</span></li>
            <li class="strike"><i>×</i> <span>Verified buyer badge tags</span></li>
            <li class="strike"><i>×</i> <span>Dedicated CRM priority support</span></li>
            <li class="strike"><i>×</i> <span>AI Enabled Smart Leads</span></li>
            </ul>
            <a href="http://localhost/autoos360/become-partner.html?plan=pkg2" class="btn btn--ghost btn--block">Select Growth</a>
        </article>

        <!-- Package 3: Premium Tier (Value Anchor) -->
        <article class="pcard pcard--elite">
            <h2 class="pcard__name">Elite Pro</h2>
            <div class="pcard__price"><var>₹24,000</var> <span>/ Year</span></div>
            <ul class="pcard__features">
            <li><i>✓</i> <span><b>Unlimited Cars</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>Unlimited Sheds</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>Unlimited Brokers</b> listing capacity</span></li>
            <li><i>✓</i> <span><b>1% Processing Fee</b> on closure</span></li>
            <li><i>✓</i> <span>Standard lead delivery alerts</span></li>
            <li><i>✓</i> <span>Dedicated CRM access</span></li>
            <li><i>✓</i> <span>7 Users panel access</span></li>
            <li><i>✓</i> <span>Dedicated Listing Page</span></li>
            <li><i>✓</i> <span>Verified buyer badge tags</span></li>
            <li><i>✓</i> <span>Dedicated CRM priority support</span></li>
            <li><i>✓</i> <span>AI Enabled Smart Leads</span></li>
            </ul>
            <a href="http://localhost/autoos360/become-partner.html?plan=pkg3" class="btn btn--primary btn--block">Go Premium Elite</a>
        </article>

        </div>
    </main>
</div>
@endsection