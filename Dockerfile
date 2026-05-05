FROM php:8.2-cli

WORKDIR /var/www/html

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev \
    libsqlite3-dev sqlite3 \
    && docker-php-ext-install pdo pdo_sqlite mbstring

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite \
    && chmod 777 /var/www/html/database/database.sqlite

EXPOSE 8000

CMD php artisan migrate --force && php artisan db:seed --force && php artisan serve --host=0.0.0.0 --port=8000
