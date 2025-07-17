@extends('workspace.master_workspace')

@section('title', 'Conferma Modifica Utente')

@section('main_content')
<div class="container mt-4">
    <div class="card">
        <div class="card-header bg-warning text-dark">
            <h5>Conferma Modifiche</h5>
        </div>
        <div class="card-body">
            <p>Stai per aggiornare i dati dell'utente <strong>{{ $user->name }}</strong>. Confermi?</p>

            <ul class="list-group mb-3">
                @foreach($validated as $key => $value)
                    <li class="list-group-item">
                        <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>

            @if($key === 'role' && strtolower($value) === 'admin')
                <span class="badge bg-danger text-uppercase px-3 py-2">
                    <i class="bi bi-exclamation-triangle-fill me-1"></i> {{ strtoupper($value) }}
                </span>
            @else
                <span>{{ $value ?? '—' }}</span>
            @endif
                    </li>
                @endforeach
            </ul>

            <form method="POST" action="{{ route('user.update', $user->id) }}">
                @csrf
                @method('PUT')
                @foreach($validated as $key => $value)
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <input type="hidden" name="confirm" value="1">
                <div class="d-flex gap-2">
                    <a href="{{ url()->previous() }}" class="btn btn-secondary">Annulla</a>
                    <button type="submit" class="btn btn-primary">Conferma e Salva</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
