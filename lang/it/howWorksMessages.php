<?php

return [
    'title' => 'Come funziona il sito',
    'intro' => 'Benvenuto nel nostro sistema di <strong>registrazione e visualizzazione dei segnali EMG e IMU</strong> in tempo reale. Questo sito è progettato per utenti che vogliono raccogliere dati biomedici per scopi di ricerca, analisi o classificazione dei gesti, in particolare per applicazioni come il riconoscimento di segnali da parte di ciclisti.',
    'acquisition' => '📡 Acquisizione in tempo reale',
    'acquisition_description' => 'Puoi connetterti a un dispositivo (come un sensore EMG/IMU) specificando l’indirizzo IP. Una volta stabilita la connessione, inizierai a ricevere i dati in tempo reale, che verranno mostrati su grafici dinamici:',
    'emg' => '<strong>EMG (elettromiografia):</strong> fino a 4 canali, raccolti da muscoli come bicipite e tricipite',
    'imu' => '<strong>IMU (unità di misura inerziale):</strong> accelerometro ±4g e giroscopio ±2000 dps',
    'hardware_note' => 'Il sistema è basato su <strong>Arduino Nano RP2040 Connect</strong> per l\'acquisizione e su un <strong>Raspberry Pi 3</strong> per l\'elaborazione e la classificazione in tempo reale.',

    'saving' => '💾 Salvataggio dei dati',
    'saving_description' => 'Durante l’acquisizione puoi interrompere la sessione in qualsiasi momento. Se hai raccolto dati, ti verrà chiesto di:',
    'gesture_category' => 'Selezionare la <strong>categoria del gesto</strong> eseguito',
    'personal_notes' => 'Aggiungere eventuali <strong>note personali</strong>',
    'saving_format' => 'I dati EMG e IMU vengono salvati in formato CSV e associati al tuo profilo utente.',

    'history' => '📂 Storico delle serie',
    'history_description' => 'Nella sezione "Le mie serie" puoi:',
    'history_items' => [
        'Visualizzare tutte le serie salvate',
        'Filtrare per categoria o utente (se sei admin)',
        'Scaricare i file CSV dei dati',
        'Modificare le note associate',
        'Eliminare serie non più necessarie',
    ],

    'recognition' => '🤖 Riconoscimento dei gesti',
    'recognition_description' => 'Il sistema è in grado di riconoscere <strong>gesti del braccio comunemente usati dai ciclisti</strong> per segnalare manovre come:',
    'gestures' => [
        'Svolta a destra / sinistra',
        'Fermata o rallentamento',
        'Segnalazione ostacoli (buche, detriti)',
        'Pericolo imminente',
    ],
    'recognition_accuracy' => 'La classificazione avviene tramite modelli <strong>machine learning</strong> (TensorFlow Lite) addestrati su un dataset personalizzato. Il sistema raggiunge un’<strong>accuratezza del 96%</strong> usando solo i segnali EMG di bicipite e tricipite.',

    'who' => '👤 Chi può usare il sito?',
    'who_description' => 'Il sito è accessibile solo tramite autenticazione. Esistono due ruoli:',
    'roles' => [
        '<strong>Utente:</strong> può registrare, salvare e visualizzare le proprie serie',
        '<strong>Admin:</strong> può visualizzare e gestire tutte le serie e gli utenti',
    ],

    'hardware' => '⚙️ Componenti hardware',
    'hardware_list' => [
        '<strong>Arduino Nano RP2040 Connect</strong>: MCU con Wi-Fi, Bluetooth e IMU integrata',
        '<strong>Olimex Shield EKG/EMG</strong>: adattatore per elettrodi e filtraggio',
        '<strong>Raspberry Pi 3 Model B</strong>: unità di elaborazione e classificazione',
    ],
    'hardware_note_2' => 'Il sistema è completamente wireless e alimentato a batteria per garantire portabilità e facilità d’uso in movimento.',

    'support' => '📞 Supporto',
    'support_text' => 'Per assistenza o informazioni contatta l’amministratore del sistema.',

    'architecture' => '🖼️ Architettura del sistema',
    'architecture_description' => 'Il sistema EMGesture è composto da più moduli indossabili: un\'unità di acquisizione con Arduino Nano RP2040, una scheda EMG Olimex, un modulo IMU e un Raspberry Pi 3 per l\'elaborazione.',
    'architecture_caption' => 'Figura: Architettura del sistema EMGesture (fonte: poster I2MTC 2025)',
    'architecture_cta' => 'Puoi consultare il codice, la documentazione tecnica, e gli esempi di dataset direttamente sulla repository ufficiale:',
    'github_button' => '🔗 Vai alla Documentazione GitHub',
];
