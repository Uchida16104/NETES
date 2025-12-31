import sys
import platform
import subprocess

mode = sys.argv[1] if len(sys.argv) > 1 else ""

os = platform.system().lower()

def run(cmd):
    try:
        subprocess.run(cmd, timeout=10, check=False)
        return True
    except Exception:
        return False

if os == "darwin":
    run(["python3", "/Users/hirotoshiuchida/NETES/core/adapters/macos.py", mode])
elif os == "linux":
    run(["python3", "/Users/hirotoshiuchida/NETES/core/adapters/linux.py", mode])
elif os == "windows":
    run(["python3", "/Users/hirotoshiuchida/NETES/core/adapters/windows.py", mode])

sys.exit(0)