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
    run(["python3", "./macos.py", mode])
elif os == "linux":
    run(["python3", "./linux.py", mode])
elif os == "windows":
    run(["python3", "./windows.py", mode])

sys.exit(0)
