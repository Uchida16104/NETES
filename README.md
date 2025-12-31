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

Rust Engine (Daemon)
  -> writes status.json / web.log
Laravel Web (Viewer only)
  -> reads files
Frontend (HTMX / Tailwind / Vue)
  -> renders state

## Components

### Rust Engine
- Runs continuously
- Executes recovery strategies
- Calls Python / C++ internally
- Writes /tmp/netes/status.json

### Laravel Backend
- /status : read-only JSON
- /logs   : recent logs
- /java   : development only

### Frontend
- HTMX polling
- Tailwind terminal UI
- Vue.js optional

## Languages and Roles

Rust: core engine  
Python: network adapters  
C++: low-level helpers  
Java: GUI tools  
PHP: web viewer  
JS: UI rendering  

## Security Model

- No command execution from web
- No HTTP control of engine
- File-based IPC only

## Intended Use Cases

- Network recovery in offline environments
- Headless systems
- Safe observability of critical processes
