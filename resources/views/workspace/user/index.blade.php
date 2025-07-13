@extends('workspace.master_workspace')

@section('title', 'Utenti Registrati')

@section('main_content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Elenco Utenti</h4>

        {{-- <a href="{{ route('series.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Aggiungi Utente
        </a> --}}
    </div>

    
    <div class="mb-3 mt-3">
        <input type="text" id="search-email" class="form-control" placeholder="Cerca per email...">
    </div>

    <ul id="search-results" class="list-group mb-4 d-none"></ul>

    <form method="GET" class="mb-4">
        <div class="row">
            <div class="col-md-6">
                <label for="role" class="form-label">Ruolo</label>
                <select name="role" id="role" class="form-select">
                    <option value="">-- Tutti i ruoli --</option>
                    @foreach($roles as $roleOption)
                        <option value="{{ $roleOption }}" {{ request('role') === $roleOption ? 'selected' : '' }}>
                            {{ ucfirst($roleOption) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6 d-flex align-items-end">
                <button type="submit" class="btn btn-primary">Filtra</button>
            </div>
        </div>
    </form>

    @if($users->isEmpty())
        <div class="alert alert-info">Nessun utente trovato con i criteri selezionati.</div>
    @else
        <div class="list-group">
            @foreach($users as $user)
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <strong>{{ $user->name }}</strong> <br>
                    Email: {{ $user->email }} <br>
                    Ruolo: <span class="badge bg-secondary">{{ $user->role }}</span>
                </div>
                <div class="d-flex gap-2">
                    <!-- Bottone modifica -->
                    @if(auth()->user()->id !== $user->id)
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-outline-warning btn-sm">
                            <i class="bi bi-pencil-square"></i> Modifica
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="bi bi-person-lock"></i> Modifica
                        </button>
                    @endif

                    <!-- Pulsante elimina -->
                    {{-- @if(auth()->user()->id !== $user->id)
                        <a href="{{ route('user.destroy.confirm', $user->id) }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i> Elimina
                        </a>
                    @else
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="bi bi-person-lock"></i> Elimina
                        </button>
                    @endif --}}
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>


<script>
    $('#search-email').on('keyup', function () {
        let query = $(this).val();

        if (query.length < 1) {
            $('#search-results').addClass('d-none').empty();
            return;
        }

        $.ajax({
            url: '{{ route('users.search') }}',
            method: 'GET',
            data: { email: query },
            success: function (data) {
                $('#search-results').removeClass('d-none').empty();
                
                if (data.length === 0) {
                    $('#search-results').append('<li class="list-group-item">Nessun utente trovato</li>');
                    return;
                }

                data.forEach(function (user) {
                    $('#search-results').append(`
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${user.name}</strong><br>
                                Email: ${user.email}<br>
                                Ruolo: <span class="badge bg-secondary">${user.role}</span>
                            </div>
                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-warning">Modifica</a>
                        </li>
                    `);
                });
            }
        });
    });
</script>
@endsection
