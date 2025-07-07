@extends('workspace.master_workspace')

@section('title', __('messages.title_workspace'))

@section('main_content')
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-purple text-white">
            <h5 class="mb-0">Modifica Profilo</h5>
        </div>

        <div class="card-body">
            <p class="text-muted mb-4">
                Puoi aggiornare solo i campi che desideri modificare. Lascia vuoti gli altri campi.
            </p>

            <form action="{{ route('users.update', Auth::id()) }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                <div class="mb-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" name="name" id="name"
                        value="{{ old('name', Auth::user()->name) }}" placeholder="Inserisci un nuovo nome (opzionale)">
                </div>

                <div class="mb-3">
                    <label for="age" class="form-label">Età</label>
                    <input type="number" class="form-control" name="age" id="age" min="1"
                        value="{{ old('age', Auth::user()->age) }}" placeholder="Inserisci una nuova età (opzionale)">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Sesso</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">-- Nessuna modifica --</option>
                        <option value="male" {{ old('gender', Auth::user()->gender) === 'male' ? 'selected' : '' }}>Maschio</option>
                        <option value="female" {{ old('gender', Auth::user()->gender) === 'female' ? 'selected' : '' }}>Femmina</option>
                        <option value="other" {{ old('gender', Auth::user()->gender) === 'other' ? 'selected' : '' }}>Altro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sport" class="form-label">Sport praticato</label>
                    <input type="text" class="form-control" name="sport" id="sport"
                        value="{{ old('sport', Auth::user()->sport) }}" placeholder="Es. Calcio, Corsa, Yoga...">
                </div>

                <div class="mb-3">
                    <label for="training_duration" class="form-label">Tempo di pratica sportiva</label>
                    <input type="text" class="form-control" name="training_duration" id="training_duration"
                        value="{{ old('training_duration', Auth::user()->training_duration) }}" placeholder="Es. 3 anni, 6 mesi...">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                </div>
            </form>
        </div>
    </div>
</div>
@if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
