FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive
ENV COMPOSER_ALLOW_SUPERUSER=1
ENV APP_ENV=production
ENV APP_DEBUG=false

RUN apt update && apt install -y \
    software-properties-common \
    curl \
    ca-certificates \
    build-essential \
    git \
    unzip \
    python3 \
    python3-pip \
    nodejs \
    npm \
    openjdk-17-jre \
    network-manager \
 && rm -rf /var/lib/apt/lists/*

RUN add-apt-repository ppa:ondrej/php -y \
 && apt update \
 && apt install -y \
    php8.4 \
    php8.4-cli \
    php8.4-common \
    php8.4-mbstring \
    php8.4-xml \
    php8.4-curl \
    php8.4-zip \
    php8.4-bcmath \
    php8.4-intl \
    php8.4-sqlite3 \
    composer \
 && update-alternatives --set php /usr/bin/php8.4 \
 && rm -rf /var/lib/apt/lists/*

RUN apt update && apt install -y rustc cargo \
 && rm -rf /var/lib/apt/lists/*

RUN npm install -g tailwindcss vite @vue/cli

WORKDIR /app

COPY . .

WORKDIR /app/web/backend/laravel

RUN mkdir -p database \
 && touch database/database.sqlite \
 && chmod 777 database/database.sqlite

RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader

RUN php artisan key:generate --force \
 && php artisan storage:link \
 && php artisan config:clear \
 && php artisan route:clear \
 && php artisan view:clear

EXPOSE 8000

CMD sh -c "\
php artisan migrate --force || true && \
php artisan serve --host=0.0.0.0 --port=8000 \
"
