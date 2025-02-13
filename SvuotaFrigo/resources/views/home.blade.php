@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="row">
            @foreach ($prodotti as $prodotto)
                    @if ($prodotto)
                        <!-- Controlla se il prodotto non è null -->
                        <li>{{ $prodotto->nome_prodotto }} -
                            Scadenza: {{ $prodotto->data_scadenza }}
                            Categoria:: {{ $prodotto->categoria }}

                        </li>
                    @else
                        <li>Prodotto non disponibile</li>
                    @endif
                @endforeach

                @foreach ($prodotti as $prodotto)
                    <!-- Carta -->
                    <div class="col-12 col-sm-6 col-md-4 col-lg-4 mb-4">
                        <div class="card" style="width: 18rem;">
                            <img src="{{ asset('images/' . $prodotto->categoria->immagine_standard) }}"
                                 class="card-img-top"
                                 alt="Immagine della carta" style="width: 100%; height: 150px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $prodotto->nome_prodotto  }}</h5>
                                <p class="card-text">
                                    Categoria={{ $prodotto->categoria }}
                                    <br>
                                    Scadenza: {{ $prodotto->data_scadenza }}
                                    <br>
                                    IMG: {{ $prodotto->categoria->immagine_standard }}
                                </p>
                                <p class="card-text"></p>
                            </div>
                        </div>
                    </div>
                @endforeach
        </div>
    </div>
@endsection
