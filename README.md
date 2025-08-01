# Motore API Dinamico per Laravel

Questo progetto è un'implementazione di un server API RESTful costruito con Laravel. La sua caratteristica principale è un "motore" dinamico e parametrico in grado di eseguire operazioni di inserimento e aggiornamento su diversi modelli/tabelle del database, selezionati tramite un parametro inviato nella chiamata API.

Il sistema è pensato per essere agnostico rispetto ai modelli sottostanti, rendendolo flessibile ed estensibile. L'autenticazione è gestita tramite **Laravel Sanctum**.

---

## Caratteristiche Principali

- **Selezione Dinamica del Modello**: L'API determina su quale modello Eloquent operare basandosi su un parametro `tab` nel payload.
- **Autenticazione Sicura**: Endpoint protetti da token API generati con Laravel Sanctum.
- **Operazioni di Inserimento**: Supporto per l'inserimento di uno o più record con una singola chiamata.
- **Operazioni di Aggiornamento Flessibili**:
  - Aggiornamento di un singolo campo.
  - Aggiornamento di campi multipli in blocco (evoluzione).
- **Struttura Estensibile**: Aggiungere il supporto per una nuova tabella richiede solo di creare il modello/migration e aggiungerlo a un singolo file di configurazione.

---

## Installazione e Setup

Per avviare il progetto in un ambiente di sviluppo locale, seguire questi passaggi.

### Prerequisiti

- PHP >= 8.2
- Composer
- Un server Database (es. MySQL, PostgreSQL, SQLite)
- Io per semplicità l'ho fatto con SQLite

### Guida all'Installazione

1.  **Clonare il repository:**

    ```bash
    git clone [https://github.com/carlomarcon/api-data-manager.git](https://github.com/carlomarcon/api-data-manager.git)
    cd api-data-manager
    ```

2.  **Installa le dipendenze di PHP:**

    ```bash
    composer install
    ```

3.  **Crea il file di configurazione d'ambiente:**

    ```bash
    cp .env.example .env
    ```

4.  **Generare la chiave dell'applicazione:**

    ```bash
    php artisan key:generate
    ```

5.  **Configura il Database:**
    Apri il file `.env` e inserisci le credenziali corrette per il database locale (`DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`).
    Ho utilizzato SQLite per questo progetto per semplicità.

6.  **Eseguire le Migrations:**

    ```bash
    php artisan migrate
    ```

7.  **Avviare il server di sviluppo:**
    ```bash
    php artisan serve
    ```
    L'API sarà ora disponibile all'indirizzo `http://127.0.0.1:8000`.

---

## Documentazione API

Tutte le richieste agli endpoint protetti devono includere i seguenti header:

- `Accept`: `application/json`
- `Authorization`: `Bearer TUO_TOKEN_API`
- IMPORTANTE nell'header mettere bearer/spazio/token, importante lo spazio prima del token

### Autenticazione

#### `POST /api/login`

Autentica un utente e restituisce un token API.

**Payload di Esempio:**

```json
{
  "email": "test@example.com",
  "password": "password"
}
```

#####

Tutte le altre rotte visibili in routes effettuano le azioni di insert, update e multiple update
