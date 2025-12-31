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
