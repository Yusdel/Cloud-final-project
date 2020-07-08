## IMPORTANTE
PER IMPOSTARE L'AMBIENTE IN MODO AUTOMATICO SEGUIRE LE ISTRUZIONI DEL FILE "readme_setup_local", prima però assicurarsi di possedere i seguenti requisiti di sistema

- Node.JS v12.18.2 LTS
- MySql
- PHP ^7.3
- Composer

## Informazioni generali  
Il sito essendo incompleto è lento, quindi durante la fase di test da browser avere pazienza, le chiamate durano circa 2/3 secondi

Per il frontend essendo puro JS-CSS-HTML viene installato parcel come bundler per eseguire l'app in locale, se si dispone o si usa un'altro bundler o si vuole avviare in altro modo eliminare lo script presente in /scripts/setup_local.sh e sostituire il comando dell'avvio del proprio bundler (o eliminarlo) IN package.json->scripts->dev

Questo applicativo usa il gestore di pacchetti Node - npm 

Nelle cartelle SERVER e WEB ci sono dei readme utili alla comprensione del codice scritto

## concurrently
this npm is necessary to run both server together
## postdev script i don't know yet why not work well