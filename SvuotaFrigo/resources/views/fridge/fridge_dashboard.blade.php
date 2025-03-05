@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between mb-3">
        <a href="{{ route('user.dashboard') }}" class="btn btn-custom btn-dashboard">
            <i class="bi bi-house-door"></i> <span style="margin-left: 8px;">Dashboard</span>
        </a>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-custom btn-logout">
                <i class="bi bi-box-arrow-right"></i> <span style="margin-left: 8px;">Logout</span>
            </button>
        </form>
    </div>

    <div class="row">
</div>
    
<div class="fridge-container">
    <div id="details_div" class="fridge-section">
        @include('fridge.real_fridge')
    </div>

    <div id="products_div" class="fridge-section">
        @include('fridge.product_details')
    </div>

    <div id="recipes_generator" class="fridge-section sidebar">
        <button id="toggle_sidebar" class="sidebar-toggle">☰</button>
        @include('fridge.recipes_generator')
    </div>

    <div id="overlay"></div> <!-- Overlay come sibling -->
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush

@section('styles')
    <link href="{{ asset('css/fridge_dashboard.css') }}" rel="stylesheet">
@endsection
