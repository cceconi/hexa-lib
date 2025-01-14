ARG php_version="7.4"

FROM php:${php_version}-cli

ENV COMPOSER_ALLOW_SUPERUSER=1

WORKDIR /var/app/hexa-lib

# Needed for some install
RUN mkdir -p /usr/share/man/man1

#Instalation des dépendances et composer
RUN apt-get update \
  && apt-get install -y git zlib1g-dev libzip-dev unzip \
  && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
  && apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# PHP Extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync && \
  install-php-extensions xdebug zip apcu sockets opcache

RUN { \
  echo "xdebug.mode = debug,coverage,develop"; \
  echo "xdebug.output_dir = /var/app/xdebug"; \
  } >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

RUN { \
  echo 'memory_limit = -1'; \
  } > /usr/local/etc/php/conf.d/docker-php-memlimit.ini

ENV PROMPT_COMMAND='history -a'
