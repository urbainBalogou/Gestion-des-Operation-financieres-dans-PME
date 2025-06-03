@extends('layouts.app')

@section('contenu')
<div class="container">
    <h2>Modifier le taux de TVA</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.tva.update') }}">
        @csrf
        <div class="form-group">
            <label for="taux">Taux de TVA (%)</label>
            <input type="number" step="0.01" min="0" max="100" name="taux" class="form-control" value="{{ old('taux', $tva->taux ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-primary mt-2">Mettre à jour</button>
    </form>
</div>
@endsection
