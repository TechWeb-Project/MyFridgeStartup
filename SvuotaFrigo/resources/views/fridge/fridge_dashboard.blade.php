@extends('layouts.app')

@section('content')
<div class="fridge-container">

    <div id="details_div" class="fridge-section">
        @include('fridge.real_fridge')
    </div>

    <div id="products_div" class="fridge-section">
        @include('fridge.product_details')
    </div>
        
    <div id="recipes_generator" class="fridge-section">
        @include('fridge.recipes_generator')
    </div>
</div>
@endsection

@push('scripts')
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush

@section('styles')
    <link href="{{ asset('css/fridge_dashboard.css') }}" rel="stylesheet">
@endsection
