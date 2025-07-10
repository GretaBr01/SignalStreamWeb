@extends('workspace.master_workspace')

@section('title', 'Gestione Categorie')

@section('main_content')
<div class="container mt-4">
    <h2 class="mb-4">Gestione Categorie</h2>

    {{-- Creazione Nuova Categoria --}}
    <div class="card mb-4 border-success">
        <div class="card-header bg-success text-white">
            <strong>Nuova Categoria</strong>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('categories.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-4">
                        <label for="name" class="form-label">Nome Categoria</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-4">
                        <label for="image" class="form-label">Immagine</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>
                    
                    {{-- <div class="col-md-4">
                        <label for="description" class="form-label">Descrizione</label>
                        <input type="text" name="description" class="form-control">
                    </div> --}}
                </div>
                <div class="mt-3">
                    <button class="btn btn-success"><i class="bi bi-plus-circle"></i> Crea</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Lista Categorie --}}
    <div class="card">
        <div class="card-header">
            <strong>Categorie Esistenti</strong>
        </div>
        <div class="card-body">
            <div class="row row-cols-1 row-cols-md-3 g-4">
                @foreach ($categories as $category)
                    <div class="col">
                        <div class="card h-100 shadow-sm d-flex flex-column">
                            @if ($category->image)
                            <img src="{{ route('category.image', ['path' => $category->image]) }}" class="card-img-top" alt="Categoria">
                                {{-- <img src="{{ asset('storage/' . $category->image) }}" class="card-img-top" alt="Immagine categoria"> --}}
                            @else
                                <div class="text-center card-body flex-grow-1 p-4 text-muted">Nessuna immagine</div>
                            @endif

                            {{-- <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                                <p class="card-text">{{ $category->description ?? '—' }}</p>
                            </div> --}}

                            <div class="card-footer d-flex mt-auto justify-content-between">
                                {{-- Modifica --}}
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-pencil-square"></i> Modifica
                                </a>
                                <h5 class="card-title">{{ $category->name }}</h5>
                                {{-- Elimina solo se non ha serie --}}
                                @if (count($category->serie) === 0)

                                    <a href="{{ route('categories.destroy.confirm', $category->id) }}" class="btn btn-outline-danger btn-sm">
                                        <i class="bi bi-trash"></i> Elimina
                                    </a>
                                @else
                                    <button class="btn btn-secondary btn-sm" disabled>
                                        <i class="bi bi-trash"></i> Elimina
                                    </button>
                                    {{-- <span class="text-muted small">In uso</span> --}}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($categories->isEmpty())
                <p class="text-center text-muted mt-4">Nessuna categoria presente.</p>
            @endif
        </div>
    </div>
</div>
@endsection
