FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
WORKDIR /app

RUN apt update && apt install -y \
    software-properties-common \
    curl \
    build-essential \
    python3 \
    python3-pip \
    nodejs \
    npm \
    openjdk-17-jre \
    network-manager

RUN add-apt-repository ppa:ondrej/php -y \
 && apt update \
 && apt install -y \
    php8.2 \
    php8.2-cli \
    php8.2-mbstring \
    php8.2-xml \
    php8.2-curl \
    php8.2-zip \
    php8.2-bcmath \
    php8.2-intl \
    composer \
 && update-alternatives --set php /usr/bin/php8.2 \
 && rm -rf /var/lib/apt/lists/*

RUN apt update && apt install -y rustc cargo

RUN npm install -g tailwindcss vite @vue/cli

COPY . .

WORKDIR /app/web/backend/laravel
RUN composer install --no-dev --optimize-autoloader
RUN php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
