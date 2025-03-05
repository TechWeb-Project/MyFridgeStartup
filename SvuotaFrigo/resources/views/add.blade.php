@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Aggiungi un nuovo alimento</h2>

        <!-- Se ci sono errori di validazione, mostrali -->
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ url('add') }}" method="POST">
            @csrf
            <div style="display: inline-block; width: 30%; margin-right: 10px;">
                <label for="nome">Nome alimento</label>
                <input type="text" name="nome_prodotto" id="nome_prodotto" class="form-control" required style="height: 35px;">
            </div>
            <div style="display: inline-block; width: 30%; margin-right: 10px;">
                <label for="categoria_id">Categoria</label>
                <select name="categoria_id" id="categoria_id" class="form-control" required style="height: 35px;">
                    @foreach ($categorie as $categoria)
                        <option value="{{ $categoria->id_categoria }}">{{ $categoria->nome_categoria }}</option>
                    @endforeach
                </select>
            </div>
            <div style="display: inline-block; width: 30%; margin-right: 10px;">
                <label for="durata_id">Durata</label>
                <select name="durata_id" id="durata_id" class="form-control" required style="height: 35px;">
                    @foreach ($durate as $durata)
                        <option value="{{ $durata->id_durata }}">{{ $durata->nome_durata }}</option>
                    @endforeach
                </select>
            </div>

           <!-- Aggiunta del campo quantità -->
            <div style="display: inline-block; width: 30%; margin-right: 10px;">
                <label for="quantita">Quantità</label>
                <input type="number" name="quantita" id="quantita" class="form-control" required style="height: 35px;" min="1" max="5000">
            
            </div>

            <!-- Aggiunta del campo unita_misura -->
            <div style="display: inline-block; width: 30%; margin-right: 10px;">
                <label for="unita_misura">Unità di misura</label>
                <select name="unita" id="unita" class="form-control" required style="height: 35px;">
                    @foreach (App\Constants\UnitaMisura::all() as $unita)
                        <option value="{{ $unita }}">{{ ucfirst($unita) }}</option>
                    @endforeach
                </select>
            </div>


            <div style="clear: both;"></div>
            <button type="submit" class="btn btn-primary" style="margin-top: 10px;">Aggiungi Alimento</button>
        </form>

        @if(session('success'))
            <div class="alert alert-success" style="margin-top: 10px;">
                {{ session('success') }}
            </div>
        @endif
    </div>
@endsection
