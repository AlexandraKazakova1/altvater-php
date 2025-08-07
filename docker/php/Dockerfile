FROM php:8.4.11-fpm

# Install modules
RUN apt-get update && apt-get install -y \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libicu-dev \
        wget \
        git \
        build-essential \
        libpng-dev \
        locales \
        zip \
        libzip-dev \
        jpegoptim optipng pngquant gifsicle \
        unzip \
            --no-install-recommends
RUN apt-get install -y libonig-dev
RUN docker-php-ext-install zip intl
RUN docker-php-ext-install mbstring pdo_mysql exif
RUN docker-php-ext-install  pcntl
RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
  && NPROC="$(grep -c ^processor /proc/cpuinfo 2>/dev/null || 1)" \
  && docker-php-ext-install "-j${NPROC}" bcmath exif gd mysqli

COPY ./install-composer.sh /
COPY ./php.ini /usr/local/etc/php/
COPY ./www.conf /usr/local/etc/php/

RUN apt-get purge -y g++ \
    && apt-get autoremove -y \
    && rm -r /var/lib/apt/lists/* \
    && rm -rf /tmp/* \
    && sh /install-composer.sh \
    && rm /install-composer.sh
RUN docker-php-ext-install mysqli
RUN docker-php-ext-enable mysqli
RUN usermod -u 1000 www-data

VOLUME /root/.composer
WORKDIR /app

EXPOSE 9000
CMD ["php-fpm"]
