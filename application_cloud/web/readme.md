## if export not working use chrome please! Not all browsers support ES6 export

## Appunti sull'implementazione
Il front-end è stato realizzato in maniera approssimativa, di fatto non vi è alcuna gestione delle route vista l'implementazione in puro HTML-CSS-JavaScript. (si è fatto uso di dichiarazioni di tag <a> invisibili con href per creare l'albero delle connessioni utili al bundler per collegare le pagine html)
Inoltre le fetch risultano lente ed è stato fatto un uso minimale del sessionStorage salvando unicamente il token JWT anzicchè salvare anche gli altri dati tornati per evitare chiamate costanti al server con ovvio rallentamento nell'esecuzione.


La pagina iniziale è la index.html che rappresenta il login.

tree
- src/
    - fetches.js
    - focusImg.js
    - home.js
    - index.js
- styles/
    - style.css
    - style_home.css
- views/
    - focusImage.html
    - home.html
    - register.html

Nel file "fetches.js" troviamo tutte le chiamate API.
Ogni view presenta un proprio file js.

## NOTE CONCLUSIVE LAVORO FATTO 
Pur avendo avuto molto tempo, una gestione sbagliata di:
    - suddivisione dei ruoli e dei compiti
    - uso di strumenti di condivisione del progetto
    - tempo dedicato al progetto
    - comunicazione nel team, 
hanno portato ad una realizzazione parziale del lavoro. Tuttavia il gruppo ha deciso con questo progetto di testare le proprie capacità, usando tecnologie non studiate, implementare architetture non viste e impostando un'automazione nel setup dell'ambiente locale per facilitarne la condivisione. 

Ci scusiamo per il lavoro parziale effettuato!
