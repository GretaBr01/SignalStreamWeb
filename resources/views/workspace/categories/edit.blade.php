@extends('workspace.master_workspace')

@section('title', 'Modifica Categoria')

@section('main_content')
<div class="container mt-4">
    <h3 class="mb-4">Modifica Categoria</h3>

    <form action="{{ route('categories.update', $category->id) }}" method="POST" enctype="multipart/form-data" class="card shadow p-4">
        @csrf
        @method('PUT')

        {{-- Nome --}}
        <div class="mb-3">
            <label for="name" class="form-label">Nome Categoria</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                   value="{{ old('name', $category->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Descrizione --}}
        {{-- <div class="mb-3">
            <label for="description" class="form-label">Descrizione (opzionale)</label>
            <textarea name="description" id="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
        </div> --}}

        {{-- Immagine --}}
        <div class="mb-3">
            <label for="image" class="form-label">Immagine (opzionale)</label>
            @if ($category->image)
                <div class="mb-2">
                    <img src="{{ route('category.image', ['path' => $category->image]) }}" alt="Immagine attuale"
                         class="img-thumbnail" style="max-height: 200px;">
                </div>
            @endif
            <input type="file" name="image" id="image" accept="image/*" class="form-control">
            <small class="text-muted">Lascia vuoto per mantenere l'immagine attuale</small>
        </div>

        {{-- Azioni --}}
        <div class="mt-4 d-flex justify-content-between">
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Annulla
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-save"></i> Salva modifiche
            </button>
        </div>
    </form>
</div>
@endsection
