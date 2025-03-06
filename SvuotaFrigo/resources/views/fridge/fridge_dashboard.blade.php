@extends('layouts.app')
<style>

.nav {
    position: relative;
    width: 100%;
    height: 120px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5em 1.5em;
    background: linear-gradient(90deg, #00c6ff, #007bff);
    box-shadow: 0px 4px 4px rgb(0, 200, 255);
}


.logo {
    height: 90px;
    margin-left: 50px; 
}

.nav-right a, .nav-right button {
    padding: 10px 20px;
    color: white;
    font-size: 16px;
    font-weight: bold;
    background: transparent;
    border: none;
    border-radius: 5px;
    position: relative;
    transition: all 0.3s ease-in-out;
    text-decoration: none;
    overflow: hidden;
}

.nav-right a::before, .nav-right button::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 10px;
    opacity: 0;
    transition: opacity 0.3s ease-in-out, transform 0.3s ease-in-out;
    transform: scale(0.8);
}

.nav-right a:hover::before, .nav-right button:hover::before {
    opacity: 1;
    transform: scale(1);
}

.nav-right a:hover, .nav-right button:hover {
    color: white; 
}


.btnfridge {
    font-size: 14px;
    font-weight: bold;
    text-decoration: none;
    color: white;
    background: rgba(255, 255, 255, 0.2);
    padding: 10px 20px;
    border-radius: 25px;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    transition: all 0.3s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0px;
    left: -20px; 
    position: relative;
}

.btnfridge:hover {
    background: white;
    color: #007bff;
}


</style>
@section('content')
<div class="nav">

        <div class="nav-left">
            <img src="{{ asset('images/waisteless.png') }}" alt="Logo" class="logo">   
        </div>
        <div>
            <a href="{{ route('user.dashboard') }}" class="btnfridge">
                <i class="bi bi-house-door"></i> Vai alla Dashboard
            </a>
            <form action="{{ route('logout') }}" method="POST" style="display: inline;">
                @csrf
                <a type="submit" class="btnfridge" style="margin-left: 10px;"> <i class="bi bi-box-arrow-right"></i>
                </i> Logout </a>
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

    <div id="overlay"></div> <!-- Overlay come sibling -->
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/fridge_script.js') }}"></script>
@endpush

@section('styles')
    <link href="{{ asset('css/fridge_dashboard.css') }}" rel="stylesheet">
@endsection
