@extends('layouts.master')

@section('title', 'Come funziona il sito')

@section('body')
<div class="container py-5 page-content">
    <h1 class="mb-4 text-primary fw-bold">
        <i class="bi bi-graph-up-arrow me-2"></i>Come funziona il sito
    </h1>

    <div class="alert alert-info shadow-sm">
        Benvenuto nel nostro sistema di <strong>registrazione e visualizzazione dei segnali EMG e IMU</strong> in tempo reale! Questo strumento è pensato per supportare <strong>ricerca, analisi e classificazione dei gesti</strong> con un’interfaccia semplice e intuitiva.
    </div>

    <hr class="my-4">

    <h3 class="text-secondary">
        <i class="bi bi-broadcast-pin me-2"></i>Acquisizione in tempo reale
    </h3>
    <p>Connettiti a un dispositivo inserendo l’indirizzo IP. Una volta stabilita la connessione, i grafici iniziano ad aggiornarsi in diretta:</p>
    <ul class="list-group list-group-flush mb-3">
        <li class="list-group-item"><span class="badge bg-danger me-2">EMG</span>4 canali di elettromiografia</li>
        <li class="list-group-item"><span class="badge bg-info text-dark me-2">IMU</span>Accelerometro e giroscopio su 3 assi</li>
    </ul>

    <div class="alert alert-warning small">
        Puoi avviare o interrompere la ricezione dati in qualsiasi momento.
    </div>

    <hr class="my-4">

    <h3 class="text-secondary">
        <i class="bi bi-save2 me-2"></i>Salvataggio dei dati
    </h3>
    <p>Alla fine della sessione ti verrà chiesto di:</p>
    <ul class="list-unstyled ms-3">
        <li><i class="bi bi-tag me-2 text-success"></i>Selezionare la <strong>categoria</strong> del gesto</li>
        <li><i class="bi bi-pencil-square me-2 text-primary"></i>Aggiungere <strong>note personali</strong> (facoltative)</li>
    </ul>
    <p>I dati verranno salvati automaticamente in <span class="badge bg-secondary">formato CSV</span>, all'interno del tuo profilo.</p>

    <hr class="my-4">

    <h3 class="text-secondary">
        <i class="bi bi-folder2-open me-2"></i>Storico delle serie
    </h3>
    <p>Accedendo alla sezione <strong>"Le mie serie"</strong> puoi:</p>
    <ul class="list-group list-group-flush mb-3">
        <li class="list-group-item">👀 Visualizzare tutte le sessioni salvate</li>
        <li class="list-group-item">🔍 Filtrare per <strong>categoria</strong> o <strong>utente</strong> (solo admin)</li>
        <li class="list-group-item">⬇️ Scaricare i dati EMG e IMU</li>
        <li class="list-group-item">📝 Modificare note e descrizioni</li>
        <li class="list-group-item">🗑 Eliminare serie non più utili</li>
    </ul>

    <hr class="my-4">

    <h3 class="text-secondary">
        <i class="bi bi-person-check me-2"></i>Chi può usare il sito?
    </h3>
    <p>Il sito è riservato agli utenti registrati. Esistono due ruoli principali:</p>
    <ul class="list-group list-group-flush mb-3">
        <li class="list-group-item"><strong>Utente</strong> – può registrare, salvare e gestire solo le proprie serie</li>
        <li class="list-group-item"><strong>Admin</strong> – ha accesso completo a tutte le serie e può gestire gli utenti</li>
    </ul>

    <hr class="my-4">

    <h3 class="text-secondary">
        <i class="bi bi-question-circle me-2"></i>Supporto
    </h3>
    <p>Hai bisogno di aiuto? Contatta l’amministratore tramite l’email indicata nella sezione profilo oppure scrivici nella sezione contatti.</p>
</div>
@endsection
