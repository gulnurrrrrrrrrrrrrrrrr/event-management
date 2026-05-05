FROM php:8.2-cli

WORKDIR /app

COPY . .

RUN apt-get update && apt-get install -y \
    unzip git curl libpng-dev libonig-dev libxml2-dev \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql pgsql mbstring

RUN curl -sS https://getcomposer.org/installer | php -- \
    --install-dir=/usr/local/bin --filename=composer

RUN composer install --no-dev --optimize-autoloader

EXPOSE 8000

CMD ["bash", "start.sh"]
