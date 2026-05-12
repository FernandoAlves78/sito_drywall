FROM php:8.3-fpm-bookworm

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y --no-install-recommends \
        nginx \
        supervisor \
        curl \
        git \
        unzip \
        zip \
        libzip-dev \
        libpng-dev \
        libjpeg-dev \
        libfreetype6-dev \
        libonig-dev \
        libicu-dev \
        libxml2-dev \
        libssl-dev \
        ca-certificates \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo_mysql \
        mbstring \
        bcmath \
        zip \
        gd \
        intl \
        opcache \
        exif \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY docker/app/php.ini /usr/local/etc/php/conf.d/zz-app.ini
COPY docker/app/nginx.conf /etc/nginx/sites-available/default
COPY docker/app/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

RUN ln -sf /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default \
    && rm -f /etc/nginx/sites-enabled/default.dpkg-dist || true

WORKDIR /var/www/html

RUN groupadd -r app -g 1000 || true \
    && useradd -u 1000 -g 1000 -m -s /bin/bash app || true \
    && mkdir -p /var/www/html /var/log/supervisor /run/php \
    && chown -R app:app /var/www/html

EXPOSE 80

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisor/conf.d/supervisord.conf"]
