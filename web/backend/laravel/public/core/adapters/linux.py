import sys
import subprocess

ssid = sys.argv[1] if len(sys.argv) > 1 else ""

subprocess.run(
    ["nmcli", "dev", "wifi", "connect", ssid],
    timeout=10,
    check=False
)
