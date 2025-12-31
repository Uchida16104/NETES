import sys
import subprocess

ssid = sys.argv[1] if len(sys.argv) > 1 else ""

subprocess.run(
    ["networksetup", "-setairportnetwork", "en0", ssid],
    timeout=10,
    check=False
)
