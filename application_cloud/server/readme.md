## Files di lavoro principali
- routes/api.php
- app/
    - Http/
        - Controllers/Api/
            - AuthController.php
            - AzureController.php
            - HomeController.php
            - PasswordResetController.php
        - Traits/
            - Azure.php
            - AzureStorageSAS.php

    - Notifications/
            - PasswordResetRequest.php
            - PasswordResetSuccess.php
            - SignupActivate.php

    - PasswordReset.php

- database/migrations/
    - create_users_table.php

## breve descrizione Architettura e lavori completati
L'applicativo vuole presentarsi con una architettura Headless REST con backend e frontend completamente dissociati.

La percentuale di sviluppo stimata è dell'70%, vista la mancanza di: 
    - imlementazione nel db dei campi utili alla ricerca
    - gestione più accurata delle risposte API
    - try-catch o gestione errori nelle connessioni con il cloud
    - corretto utilizzo dei verbi Http (da controllare)
    - implementazione di sistema di sicurezza maggiore, oltre al JWT (es. controllo IP client, inserendo magari tali dati nel Payload del token e controllando le chiamate in arrivo e ricalcolando l'hash si potrebbe aumentare la sicurezza, se il client non usa vpn o altro)
    - ... nuovi eventuali errori o mancanze riscontrate nei test da parte degli users

Le chiamate ad Azure sono complete al 90% secondo le nostre stime, e nei due file di Trait creati sono raccolte in toto le connessioni al cloud e le varie azioni. (Manca solo la delete container dello user nel caso di cancellazione dal sito)

È stato usato l'SDK di Azure per PHP in quasi la totalità dei casi, eccetto per la creazione degli url SAS dove è stata fatta una chiamata API v3 seguendo le istruzioni in Azure.
È stata scelta questa metodologia di condivisione delle immagini per problemi tecnici con AzureAD.
Di fatto questo metodo risulta lento, visto che bisogna generare manualmente ogni volta gli url di accesso privato alle singole immagini per inviarle all'utente loggato che può così accedere alle sue immagini in sicurezza. Inoltre non è fattibile in produzione secondo i nostri calcoli.

## Flusso login - registrazione - logout
L'utente che si registra deve prima di continuare confermare l'email ricevuta poi sarà abilitato a fare il login e a caricare o eliminare foto.
Al momento della conferma dell'account (email confirm), viene generato in automatico un container Azure DEDICATO, dove caricare le proprie foto. Ogni utente presenta così il proprio container privato.

Per la protezione delle API e il controllo del routing con autorizzazione al momento del login viene generato un Token JWT dalla durata limitata che viene passato al client. Nelle chiamate successive sarà necessario inserire il token nell'header per poter accedere alle operazioni di caricamento, eliminazione e analisi delle foto. (È stato usato laravel:passport)

Al momento del logout il token viene cancellato sia lato backend che frontend.










