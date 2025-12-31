FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
WORKDIR /app

RUN tree -L 4 web

RUN apt update && apt install -y \
    curl \
    build-essential \
    rustc \
    cargo \
    python3 \
    python3-pip \
    php \
    php-cli \
    php-mbstring \
    php-xml \
    php-curl \
    composer \
    nodejs \
    npm \
    openjdk-17-jre \
    network-manager \
    && rm -rf /var/lib/apt/lists/*

RUN npm install -g tailwindcss vite @vue/cli

COPY . .

WORKDIR /app/web/background/laravel
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
