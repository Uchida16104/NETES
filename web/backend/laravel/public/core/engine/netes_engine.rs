mod state;
mod retry;

use state::NetState;
use retry::retry;

use std::env;
use std::process::Command;
use std::fs;
use std::thread;
use std::time::{Duration, SystemTime, UNIX_EPOCH};

const STATUS_DIR: &str = "/tmp/netes";
const STATUS_FILE: &str = "/tmp/netes/status.json";

fn main() {
    let args: Vec<String> = env::args().collect();

    if args.len() > 1 {
        match args[1].as_str() {
            "start" => daemon(),
            "status" => print_status(),
            _ => eprintln!("Unknown command"),
        }
    } else {
        daemon();
    }
}

fn daemon() {
    loop {
        run_once();
        thread::sleep(Duration::from_secs(5));
    }
}

fn run_once() {
    let mut state = NetState::TrySame;

    loop {
        state = match state {
            NetState::TrySame => {
                write_status("TrySame");
                if retry(3, connect_same) {
                    NetState::Done
                } else {
                    NetState::TryKnown
                }
            }
            NetState::TryKnown => {
                write_status("TryKnown");
                if retry(3, connect_known) {
                    NetState::Done
                } else {
                    NetState::TryVirtual
                }
            }
            NetState::TryVirtual => {
                write_status("TryVirtual");
                if connect_virtual() {
                    NetState::Done
                } else {
                    NetState::Notify
                }
            }
            NetState::Notify => {
                write_status("Notify");
                notify_mobile();
                NetState::Done
            }
            NetState::Done => {
                write_status("Done");
                break;
            }
        };
    }
}

fn print_status() {
    match fs::read_to_string(STATUS_FILE) {
        Ok(s) => println!("{}", s.trim()),
        Err(_) => println!("unknown"),
    }
}

fn write_status(state: &str) {
    let _ = fs::create_dir_all(STATUS_DIR);

    let timestamp = SystemTime::now()
        .duration_since(UNIX_EPOCH)
        .map(|d| d.as_secs())
        .unwrap_or(0);

    let json = format!(
        "{{\"state\":\"{}\",\"timestamp\":{}}}",
        state, timestamp
    );

    let _ = fs::write(STATUS_FILE, json);
}

fn connect_same() -> bool {
    call_python("same")
}

fn connect_known() -> bool {
    call_python("known")
}

fn connect_virtual() -> bool {
    call_python("virtual")
}

fn call_python(mode: &str) -> bool {
    Command::new("python3")
        .arg("../../../../../../core/adapters/auto.py")
        .arg(mode)
        .status()
        .map(|s| s.success())
        .unwrap_or(false)
}

fn notify_mobile() {
    let _ = Command::new("curl")
        .arg("-X")
        .arg("POST")
        .arg("https://example.push/notify")
        .status();
}
