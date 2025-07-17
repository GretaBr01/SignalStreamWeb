@extends('workspace.master_workspace')

@section('title', __('messages.title_workspace'))

@section('main_content')
@if(auth()->user()->role === 'admin')
<div class="mb-4">
    <a href="{{ route('users.index') }}" class="btn btn-outline-purple rounded-pill px-4">
        ← Torna indietro
    </a>
</div>
@endif
<div class="container mt-4">
    @if(session('success'))
        <div class="alert alert-success" id="success-alert">
            {{ session('success') }}
        </div>

        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if (alert) {
                    alert.classList.add('fade');
                    setTimeout(() => alert.remove(), 500);
                }
            }, 5000); // 5 secondi
        </script>

        <style>
            .alert.fade {
                opacity: 0;
                transition: opacity 0.5s ease-out;
            }
        </style>
    @endif
    <div class="card shadow-sm">
        <div class="card-header bg-purple text-white">
            <h5 class="mb-0">Modifica Profilo</h5>
        </div>

        <div class="card-body">
            <p class="text-muted mb-4">
                Puoi aggiornare solo i campi che desideri modificare. Lascia vuoti gli altri campi.
            </p>

            <form action="{{ route('user.update', $user->id) }}" method="POST">
                @csrf
                @if(isset($user))
                    @method('PUT')
                @endif

                @if(auth()->user()->role === 'admin')
                    <div class="mb-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control" name="name" id="name"
                            value="{{ $user->name }}" placeholder="Inserisci un nuovo nome (opzionale)">
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" id="email"
                            value="{{ $user->email }}" placeholder="Inserisci un nuova mail (opzionale)">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Ruolo</label>
                        <select name="role" id="role" class="form-select">
                            <option value="">-- Tutti i ruoli --</option>
                            @foreach($roles as $roleOption)
                                <option value="{{ $roleOption }}" {{ $user->role === $roleOption ? 'selected' : '' }}>
                                    {{ ucfirst($roleOption) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="mb-3">
                    <label for="age" class="form-label">Età</label>
                    <input type="number" class="form-control" name="age" id="age" min="1"
                        value="{{ old('age', $user->age) }}" placeholder="Inserisci una nuova età (opzionale)">
                </div>

                <div class="mb-3">
                    <label for="gender" class="form-label">Sesso</label>
                    <select name="gender" id="gender" class="form-select">
                        <option value="">-- Nessuna modifica --</option>
                        <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Maschio</option>
                        <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Femmina</option>
                        <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Altro</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="sport" class="form-label">Sport praticato</label>
                    <input type="text" class="form-control" name="sport" id="sport"
                        value="{{ old('sport', $user->sport) }}" placeholder="Es. Calcio, Corsa, Yoga...">
                </div>

                <div class="mb-3">
                    <label for="training_duration" class="form-label">Tempo di pratica sportiva</label>
                    <input type="text" class="form-control" name="training_duration" id="training_duration"
                        value="{{ old('training_duration', $user->training_duration) }}" placeholder="Es. 3 anni, 6 mesi...">
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
