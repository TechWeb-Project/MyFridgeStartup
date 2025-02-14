@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between g-4">
        @include('fridge.product_details')      <!-- Andre -->
        @include('fridge.real_fridge')          <!-- Endi -->
        @include('fridge.recipes_generator')    <!-- Nico -->
    </div>
</div>
@endsection



