# NETES – Network Emergency & Recovery System

## Overview

NETES is a daemon-based network recovery engine with a secure web-based monitoring interface.

Its primary purpose is to restore network connectivity automatically in failure scenarios while allowing operators to observe the system state safely and in real time through a web UI.

NETES strictly separates execution from observation.

## Core Principles

- The engine runs as a persistent daemon
- The web interface never executes OS commands
- State is shared via files only
- High-frequency access is safe
- No process spawning from web requests

## Architecture

```
+--------------------+
|   Rust Engine      |
|  (Daemon Process)  |
+---------+----------+
          |
          | writes
          v
+--------------------+
| status.json        |
| web.log            |
+---------+----------+
          |
          | reads
          v
+--------------------+
| Laravel Web        |
| (Viewer Only)      |
+---------+----------+
          |
          | renders
          v
+--------------------+
| Web UI             |
| HTMX + Tailwind    |
| Vue.js (optional)  |
+--------------------+
```

## Components

### Rust Engine
- Runs continuously
- Executes recovery strategies
- Calls Python / C++ internally
- Writes /tmp/netes/status.json
Example state file:
```
{
  "state": "Done",
  "timestamp": "2025-12-30 22:11:45"
}
```

### Laravel Backend
- /status : read-only JSON
- /logs   : recent logs
- /java   : development only

### Frontend
- HTMX polling
- Tailwind terminal UI
- Vue.js optional
Example:
```
<pre
  hx-get="/status"
  hx-trigger="every 2s"
  class="bg-black text-green-400 font-mono p-4">
</pre>
```

## Usage

The dashboard of backend (Ex: https://dashboard.render.com/web/srv-xxxxxxxxxxxxxxxxxxxxxx/deploys/dep-oooooooooooooooooo) responded by writing the API link when the user clicked either the “Run Python,” “Run Rust,” or “Run Java” button on the frontend.

## Steps

### 1. Clone or Fork the Repository

You can either clone the repository for local inspection, or fork it if you plan to deploy it yourself.

```bash
git clone https://github.com/Uchida16104/NETES.git
```

If you are deploying your own instance, fork the repository first, then clone your fork.

---

### 2. Deploy the Frontend to Vercel

Create a new project on Vercel and configure it as follows:

* Root Directory: ``` web/frontend ```

* Framework Preset: Other

* Build Command: ``` npm run build ```

* Output Directory: ``` dist ```

* Install Command: ``` npm install ```

Environment Variables (Vercel)

Set the following environment variable:

```
VITE_API_BASE=https://<your-project-name>.onrender.com/api
```

This value is injected at build time, so redeploy the project after changing it.

---

### 3. Deploy the Backend to Render (Docker)

Create a new Web Service on Render using Docker.

* Root Directory: (leave empty)

* Region: Oregon

* Instance Type: Free (optional)

* Dockerfile Path: ``` ./Dockerfile ```

* Docker Command: (leave empty)

* Pre-Deploy Command: (leave empty)

Environment Variables (Render)

Set the following variables:

```
APP_DEBUG=false
APP_ENV=production
CACHE_STORE=file
DB_CONNECTION=sqlite
DB_DATABASE=/app/web/backend/laravel/database/database.sqlite
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
```

Render will build and start the Laravel API using the Dockerfile.

---

### 4. Verify the Connection

Once both services are deployed:

* The frontend (Vercel) should access the backend via ``` VITE_API_BASE ```

* The backend API should be reachable at:

```
https://<your-project-name>.onrender.com/api
```

If CORS and environment variables are correctly configured, the frontend and backend will communicate successfully.

## Caution

### 1. Frontend and Backend Are Deployed Separately

This project uses a **fully separated architecture**:

- Frontend: Vite SPA deployed on Vercel
- Backend: Laravel API deployed on Render
- Database: SQLite file inside the Render container

They are connected only via HTTP API calls.

---

### 2. SQLite Persistence on Render Free Plan

When using the Render Free plan:

- The SQLite database file **may not be persistent**
- Data can be lost when the service restarts or sleeps

For production use, consider switching to an external database service.

---

### 3. Dockerfile Responsibilities

The Dockerfile must ensure:

- The SQLite file exists (`database.sqlite`)
- Proper write permissions for `storage` and `bootstrap/cache`
- A valid `APP_KEY` is generated (or provided)

If any of these are missing, the backend may fail to start.

---

### 4. git push --force-with-lease

``` git push --force-with-lease ``` should only be used when you intentionally rewrite commit history.

For normal development and deployment, a standard ``` git push ``` is sufficient.

---

### 5. Environment Variables Are Mandatory

Missing or incorrect environment variables on either Vercel or Render will cause build or runtime failures.

Always redeploy after updating environment variables.


## Links
* [frontend](https://netes.vercel.app)
* [backend](https://netes.onrender.com)

## Languages and Roles

| Language | Responsibility                          |
| -------- | --------------------------------------- |
| Rust     | Core engine, state machine              |
| Python   | Network adapters                        |
| C++      | Low-level or performance-critical tasks |
| Java     | GUI and auxiliary tools                 |
| PHP      | Web-based state viewer                  |
| JS       | UI rendering only                       |

## Security Model

- No command execution from web
- No HTTP control of engine
- File-based IPC only

## Intended Use Cases

- Network recovery in offline environments
- Headless systems
- Safe observability of critical processes

## Developed
by *Hirotoshi Uchida*
