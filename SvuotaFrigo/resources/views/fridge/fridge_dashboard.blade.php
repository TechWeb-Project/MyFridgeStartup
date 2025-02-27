<style>
.fridge-container {
    display: flex;
    justify-content: space-between;
    gap: 20px;
    flex-wrap: nowrap; /* Impedisce che vadano sotto */
}

.fridge-section {
    flex: 1;
    min-width: 30%;
    max-width: 32%; /* Mantiene tutto allineato */
}

.btn-custom {
    padding: 12px 25px;
    font-size: 16px;
    font-weight: bold;
    border-radius: 8px;
    transition: all 0.3s ease-in-out;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.2);
}

.btn-dashboard {
    background: linear-gradient(135deg, #007bff, #00c6ff);
    color: white;
    border: none;
}

.btn-dashboard:hover {
    background: linear-gradient(135deg, #0056b3, #0084ff);
    transform: scale(1.08);
    box-shadow: 3px 3px 15px rgba(0, 0, 0, 0.3);
}

.btn-logout {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-logout:hover {
    background-color: #b02a37;
    transform: scale(1.05);
}
</style>

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

    <h2 class="text-center">Il Tuo Frigo Virtuale</h2>
    <div class="row">
    <div class="fridge-container">
    <div id="products_div" class="fridge-section">
        @include('fridge.product_details')
    </div>
    
    <div id="details_div" class="fridge-section">
        @include('fridge.real_fridge')
    </div>
    
    <div id="recipes_generator" class="fridge-section">
        @include('fridge.recipes_generator')
    </div>
</div>
    </div>
</div>
@endsection