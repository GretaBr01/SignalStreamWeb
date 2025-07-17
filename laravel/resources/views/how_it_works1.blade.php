@extends('layouts.master')

@section('title', 'Come funziona il sito')

@section('body')
<div class="container py-4 page-content">
    <h1 class=" mb-4 text-purple">Come funziona il sito</h1>

    <p>Benvenuto nel nostro sistema di <strong>registrazione e visualizzazione dei segnali EMG e IMU</strong> in tempo reale. Questo sito è progettato per utenti che vogliono raccogliere dati biomedici per scopi di ricerca, analisi o classificazione dei gesti.</p>

    <hr>

    <h3>📡 Acquisizione in tempo reale</h3>
    <p>Puoi connetterti a un dispositivo (come un sensore EMG/IMU) specificando l’indirizzo IP. Una volta stabilita la connessione, inizierai a ricevere i dati in tempo reale, che verranno mostrati su grafici dinamici:</p>
    <ul>
        <li><strong>EMG (elettromiografia):</strong> 4 canali</li>
        <li><strong>IMU (unità di misura inerziale):</strong> Accelerometro e giroscopio</li>
    </ul>

    <hr>

    <h3>💾 Salvataggio dei dati</h3>
    <p>Durante l’acquisizione puoi interrompere la sessione in qualsiasi momento. Se hai raccolto dati, ti verrà chiesto di:</p>
    <ul>
        <li>Selezionare la <strong>categoria del gesto</strong> eseguito</li>
        <li>Aggiungere eventuali <strong>note personali</strong></li>
    </ul>
    <p>I dati EMG e IMU vengono salvati in formato CSV e associati al tuo profilo utente.</p>

    <hr>

    <h3>📂 Storico delle serie</h3>
    <p>Nella sezione "Le mie serie" puoi:</p>
    <ul>
        <li>Visualizzare tutte le serie salvate</li>
        <li>Filtrare per categoria o utente (se sei admin)</li>
        <li>Scaricare i file CSV dei dati</li>
        <li>Modificare le note associate</li>
        <li>Eliminare serie non più necessarie</li>
    </ul>

    <hr>

    <h3>👤 Chi può usare il sito?</h3>
    <p>Il sito è accessibile solo tramite autenticazione. Esistono due ruoli:</p>
    <ul>
        <li><strong>Utente:</strong> può registrare, salvare e visualizzare le proprie serie</li>
        <li><strong>Admin:</strong> può visualizzare e gestire tutte le serie e gli utenti</li>
    </ul>

    <hr>

    <h3>📞 Supporto</h3>
    <p>Per assistenza o informazioni contatta l’amministratore del sistema.</p>
</div>
@endsection
