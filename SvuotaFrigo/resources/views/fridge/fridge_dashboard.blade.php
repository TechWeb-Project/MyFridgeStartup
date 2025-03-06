@extends('layouts.app')
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
