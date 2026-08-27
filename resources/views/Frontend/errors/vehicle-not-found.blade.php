@extends('frontend.layouts.app')

@section('title', 'Vehicle Not Found — AutoOS360')

@section('content')
<div class="wrap" style="padding-block: 80px;">
  <div class="notfound" style="text-align: center;">
    <h1 class="sec__title" style="font-size: 32px; font-weight: 700; margin-bottom: 12px;">We couldn't find that vehicle</h1>
    <p class="sec__sub" style="margin-bottom: 24px; color: var(--muted);">It may have been sold recently, or your shared resource link pattern is incomplete.</p>
    <a class="btn btn--primary" href="{{ url('/listing/all') }}">Browse all cars</a>
  </div>
</div>
@endsection
