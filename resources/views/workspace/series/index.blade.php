@extends('workspace.master_workspace')

@section('title', 'Le mie serie')

@section('main_content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        @if(auth()->user()->role === 'admin')
            <h4 class="mb-0">Serie Registrate</h4>
        @else
            <h4 class="mb-0">Le mie serie</h4>
        @endif
        
        <a href="{{ route('series.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Aggiungi Serie
        </a>
    </div>

    @if($series_list->isEmpty())
        <div class="alert alert-info">Non hai ancora serie disponibili.</div>
    @else
    <form method="GET" class="mb-4">
    <div class="row">
        @if(auth()->user()->role === 'admin')
            <div class="col-md-4">
                <label for="user_id" class="form-label">Utente</label>
                <select name="user_id" id="user_id" class="form-select">
                    <option value="">-- Tutti gli utenti --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        @endif

        <div class="col-md-4">
            <label for="category_id" class="form-label">Categoria</label>
            <select name="category_id" id="category_id" class="form-select">
                <option value="">-- Tutte le categorie --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->display_name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filtra</button>
        </div>
    </div>
</form>

        <div class="list-group">
            @foreach($series_list as $serie)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <!-- Nome serie cliccabile -->
                    <a href="{{ route('series.show', $serie->id) }}" class="text-decoration-none flex-grow-1 text-dark">
                        Serie <strong>{{ $serie->category->display_name }}</strong> - id {{ $serie->id }}
                    </a>

                    <div class="d-flex gap-2">
                        @if(auth()->user()->role === 'admin')
                            @if($serie->emgSamples->isNotEmpty())
                                <a href="{{ route('series.download.emg', ['id' => $serie->id]) }}" class="btn btn-outline-dark btn-sm">
                                    <i class="bi bi-download"></i> EMG
                                </a>
                            @endif

                            @if($serie->imuSamples->isNotEmpty())
                                <a href="{{ route('series.download.imu', ['id' => $serie->id]) }}" class="btn btn-outline-dark btn-sm">
                                    <i class="bi bi-download"></i> IMU
                                </a>
                            @endif
                        @endif

                        <!-- Pulsante elimina -->
                        <a href="{{ route('serie.destroy.confirm', ['id' => $serie->id]) }}" class="btn btn-outline-danger btn-sm">
                            <i class="bi bi-trash"></i> Elimina
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
