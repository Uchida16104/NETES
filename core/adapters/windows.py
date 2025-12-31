import sys
import subprocess

profile = sys.argv[1] if len(sys.argv) > 1 else ""

subprocess.run(
    ["netsh", "wlan", "connect", f"name={profile}"],
    timeout=10,
    check=False
)
