# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.4-fpm

MAINTAINER Petr Zhuchkov <dp.helsing@eggheads.solutions>

ARG USER_UID

RUN apt-get update --fix-missing && apt-get install -y \
    git \
    unzip \
    redis-tools \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libmcrypt-dev \
    vim \
    libxml2-dev \
    libbz2-dev \
    re2c \
    libpng++-dev \
    libicu-dev \
    libjpeg-dev \
    libvpx-dev \
    zlib1g-dev \
    libgd-dev \
    libssl-dev \
    libtidy-dev \
    libxslt1-dev \
    libmagic-dev \
    libexif-dev \
    file \
    git \
    curl \
    wget \
    build-essential \
    devscripts \
    lintian \
    diffutils \
    patch \
    patchutils \
    apt-transport-https \
    libmagickwand-dev imagemagick \
    libzip-dev \
    libonig-dev \
    zip

# Install mysql
RUN docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get install -y libpq-dev \
# Install pdo + pgsql
#    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
#    && docker-php-ext-install pdo pdo_pgsql pgsql \
# Install mbstring
    && docker-php-ext-install mbstring \
    && docker-php-ext-install zip
# Install mcrypt
#    && docker-php-ext-install -j$(nproc) iconv mcrypt \
# Install GD
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \
# Install SimpleXML
    && docker-php-ext-install simplexml \
# Install Intl
    && docker-php-ext-configure intl && docker-php-ext-install intl \
# Install bcmath
    && docker-php-ext-install bcmath \
# Install redis
    && pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \
# Install imagick
    && pecl install imagick \
    && docker-php-ext-enable imagick \
# Install pcntl extension
    && docker-php-ext-install pcntl \
# Activate opcache
    && docker-php-ext-install opcache

RUN { \
		echo 'opcache.memory_consumption=128'; \
		echo 'opcache.interned_strings_buffer=8'; \
		echo 'opcache.max_accelerated_files=4000'; \
		echo 'opcache.revalidate_freq=5'; \
		echo 'opcache.fast_shutdown=1'; \
		echo 'opcache.enable_cli=1'; \
	} > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN apt-get update && pecl install xdebug-3.0.4 && docker-php-ext-enable xdebug
RUN echo "error_reporting=E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-error.ini
RUN echo "[xdebug]" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Set timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime && "date"

RUN usermod -u ${USER_UID} www-data

WORKDIR /var/www/cake
