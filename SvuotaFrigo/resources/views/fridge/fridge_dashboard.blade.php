@extends('layouts.app')

@section('content')
<div class="navbar">

        <div class="navbar-left">
            <img src="{{ asset('images/waisteless.png') }}" alt="Logo" class="logo" style="margin-left: 23%;">   
        </div>
        <div class="navbar-right" style="display: flex; justify-content: center; align-items: center; gap: 10px;">

            <a href="{{ route('user.dashboard') }}" class="btnfridge">
                <i></i> Vai alla Dashboard
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
   
                <a href="#" onclick="document.getElementById('logout-form').submit();" class="btnfridge">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </form>

        </div>
 </div>

<div class="container mt-4">

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

    <div id="overlay"></div> 
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush

@section('styles')
    <link href="{{ asset('css/fridge_dashboard.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
@endsection
