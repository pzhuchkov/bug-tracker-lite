# See https://github.com/docker-library/php/blob/4677ca134fe48d20c820a19becb99198824d78e3/7.0/fpm/Dockerfile
FROM php:7.4-fpm

MAINTAINER Petr Zhuchkov <dp.helsing@eggheads.solutions>

ARG USER_UID

RUN apt-get update && apt-get install -y \
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
    libmagickwand-dev imagemagick


# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --version=1.10.19 \
    && composer --version

# Set timezone
RUN rm /etc/localtime && ln -s /usr/share/zoneinfo/Europe/Moscow /etc/localtime && "date"
# install libzip
RUN apt-get install -y \
        libzip-dev \
        libonig-dev \
        zip \
  && docker-php-ext-install zip

# Install mysql
RUN docker-php-ext-install pdo pdo_mysql mysqli \
    && apt-get install -y libpq-dev \

# Install pdo + pgsql
#    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
#    && docker-php-ext-install pdo pdo_pgsql pgsql \

# Install mbstring
    && docker-php-ext-install mbstring

# Install mcrypt
#    && docker-php-ext-install -j$(nproc) iconv mcrypt \
# Install GD
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ \
    && docker-php-ext-install -j$(nproc) gd \

# Install SimpleXML
    && docker-php-ext-install simplexml \

# Install soap
#    && docker-php-ext-install soap \

# Install Intl
    && docker-php-ext-configure intl && docker-php-ext-install intl \

# Install bcmath
    && docker-php-ext-install bcmath \

# Install redis
    && pecl install -o -f redis \
    &&  rm -rf /tmp/pear \
    &&  docker-php-ext-enable redis \

# Install imagick
#    && apt-get install -y libmagickwand-6.q16-dev --no-install-recommends \
#    && ln -s /usr/lib/x86_64-linux-gnu/ImageMagick-6.8.9/bin-Q16/MagickWand-config /usr/bin \
#    && pecl install imagick \
#    && echo "extension=imagick.so" > /usr/local/etc/php/conf.d/ext-imagick.ini \

    && pecl install imagick \
    && docker-php-ext-enable imagick \


# Install xdebug
    && pecl install xdebug-2.9.8 && docker-php-ext-enable xdebug \
    && echo "error_reporting = E_ALL" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_enable=1" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_connect_back=0" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.idekey=\"PHPSTORM\"" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.remote_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "memory_limit=4048M" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \
    && echo "date.timezone=Europe/Moscow" >> /usr/local/etc/php/conf.d/docker-php-ext.ini \

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

# For nodejs & bower
RUN apt-get install software-properties-common -y

# Node js step 1
#RUN curl -sL https://deb.nodesource.com/setup_9.x | bash -

# Nodejs step 2
#RUN apt-get install nodejs -y

# For nodejs & bower
RUN apt-get install software-properties-common -y

# Node js step 1
RUN curl -sL https://deb.nodesource.com/setup_12.x | bash - \
     && apt install nodejs -y

# Bower install
RUN npm install -g bower

RUN \
    apt-get install libldap2-dev -y && \
    rm -rf /var/lib/apt/lists/* && \
    docker-php-ext-configure ldap --with-libdir=lib/x86_64-linux-gnu/ && \
    docker-php-ext-install ldap

RUN usermod -u ${USER_UID} www-data

WORKDIR /var/www/cake
