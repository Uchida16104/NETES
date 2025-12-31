#!/bin/sh
set +e

OS="$(uname | tr '[:upper:]' '[:lower:]')"

echo "[NETES] bootstrap start ($OS)"

if echo "$OS" | grep -q "darwin"; then
  echo "[NETES] macOS detected"

  command -v brew >/dev/null 2>&1 || {
    echo "[NETES] installing Homebrew (if possible)"
    /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/HEAD/install.sh)" || true
  }

  brew install rust python@3 php node openjdk || true
  brew install composer || true

fi

if echo "$OS" | grep -q "linux"; then

  if command -v apt >/dev/null 2>&1; then
    echo "[NETES] Debian/Ubuntu detected"

    sudo apt update || true
    sudo apt install -y \
      curl build-essential \
      rustc cargo \
      python3 python3-pip \
      php php-cli php-mbstring \
      composer \
      nodejs npm \
      openjdk-17-jre \
      network-manager || true
  fi

  if command -v dnf >/dev/null 2>&1; then
    echo "[NETES] Red Hat detected"

    sudo dnf install -y \
      curl gcc-c++ \
      rust cargo \
      python3 \
      php php-cli php-mbstring \
      composer \
      nodejs npm \
      java-17-openjdk \
      NetworkManager || true
  fi
fi

if echo "$OS" | grep -q "mingw\|msys\|cygwin"; then
  echo "[NETES] Windows detected"

  command -v winget >/dev/null 2>&1 && {
    winget install Rustlang.Rust || true
    winget install Python.Python.3 || true
    winget install OpenJDK.OpenJDK || true
    winget install PHP.PHP || true
    winget install OpenJS.NodeJS || true
  }

fi

command -v rustc >/dev/null 2>&1 || {
  curl https://sh.rustup.rs | sh -s -- -y || true
}

command -v npm >/dev/null 2>&1 && {
  npm install -g tailwindcss || true
}

echo "[NETES] bootstrap completed safely"
exit 0

