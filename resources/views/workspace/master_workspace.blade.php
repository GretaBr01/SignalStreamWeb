@extends('layouts.master')

@section('title', __('messages.title_workspace'))

@section('body')
<div class="workspace">
    <div class="col-md-3 col-lg-2 sidebar">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
            <a href="{{ route('workspace.series') }}" class="nav-link">Storico Dati</a>
            </li>
            @if(auth()->user()->role !== 'admin')
                <li class="nav-item mb-2">
                <a href="{{ route('workspace.acquisizione') }}" class="nav-link">Acquisizione Realtime</a>
                </li>
            @else
                <li class="nav-item mb-2">
                <a href="{{ route('categories.index') }}" class="nav-link">Gestione Categorie</a>
                </li>
            @endif
            <li class="nav-item mb-2">
                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('users.index') }}" class="nav-link">Gestione Utenti</a>
                @else
                    <a href="{{ route('user.edit', auth()->user()->id) }}" class="nav-link">Profilo Utente</a>
                @endif
            </li>
        </ul>
    </div>

    <main class="col-md-9 col-lg-10 p-4 main-content">
      @yield('main_content')
    </main>
</div>

@endsection