FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
&& pecl install redis \
&& docker-php-ext-enable redis

WORKDIR /var/www

COPY --chown=www-data:www-data . /var/www

USER www-data

EXPOSE 9000
CMD ["php-fpm"]
