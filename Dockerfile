FROM ubuntu:22.04

RUN apt update && apt install -y \
  curl build-essential rustc cargo \
  python3 python3-pip \
  php php-cli php-mbstring composer \
  nodejs npm openjdk-17-jre network-manager

WORKDIR /app
COPY . .

RUN composer install
CMD ["php", "artisan", "serve", "--host=0.0.0.0"]
