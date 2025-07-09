@extends('workspace.master_workspace')

@section('title', 'Le mie serie')

@section('main_content')
<div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0">Le mie serie</h4>
        <a href="{{ route('series.create') }}" class="btn btn-success">
            <i class="bi bi-plus-lg"></i> Aggiungi Serie
        </a>
    </div>

    @if($series_list->isEmpty())
        <div class="alert alert-info">Non hai ancora serie disponibili.</div>
    @else
        <div class="list-group">
            @foreach($series_list as $serie)
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <!-- Nome serie cliccabile -->
                    <a href="{{ route('series.show', $serie->id) }}" class="text-decoration-none flex-grow-1 text-dark">
                        Serie <strong>{{ $serie->category->display_name }}</strong> - id {{ $serie->id }}
                    </a>

                    <!-- Pulsante elimina -->
                    <a href="{{ route('serie.destroy.confirm', ['id' => $serie->id]) }}" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-trash"></i> Elimina
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
