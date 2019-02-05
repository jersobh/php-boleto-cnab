################################################################################
# Base image
################################################################################

FROM debian:jessie

################################################################################
# Build instructions
################################################################################

# Remove default nginx configs.
RUN rm -f /etc/nginx/conf.d/*

RUN chmod o+r /etc/resolv.conf


# Install packages
RUN echo deb http://ftp.br.debian.org/debian jessie main | tee /etc/apt/sources.list.d/debian.list
RUN echo deb-src http://ftp.br.debian.org/debian jessie main | tee /etc/apt/sources.list.d/debian.list
RUN echo deb http://ftp.br.debian.org/debian jessie-updates main | tee /etc/apt/sources.list.d/debian.list
RUN echo deb-src http://ftp.br.debian.org/debian jessie-updates main | tee /etc/apt/sources.list.d/debian.list
RUN echo deb http://security.debian.org/ jessie/updates main | tee /etc/apt/sources.list.d/debian.list
RUN echo deb-src http://security.debian.org/ jessie/updates main | tee /etc/apt/sources.list.d/debian.list

RUN apt-get update && apt-get upgrade -y
RUN apt-cache search php5


RUN apt-get install -my \
  git \
  supervisor \
  curl \
  wget \
  php5-curl \
  php5-fpm \
  php5-gd \
  php5-mongo \
  php5-memcached \
  php5-mysql \
  php5-mcrypt \
  php5-sqlite \
  php5-xdebug \
  php5-apcu \
  nginx

RUN apt-get install -my pkg-config libssl-dev

# Ensure that PHP5 FPM is run as root.
RUN sed -i "s/user = www-data/user = root/" /etc/php5/fpm/pool.d/www.conf
RUN sed -i "s/group = www-data/group = root/" /etc/php5/fpm/pool.d/www.conf

# Pass all docker environment
RUN sed -i '/^;clear_env = no/s/^;//' /etc/php5/fpm/pool.d/www.conf

# Get access to FPM-ping page /ping
RUN sed -i '/^;ping\.path/s/^;//' /etc/php5/fpm/pool.d/www.conf
# Get access to FPM_Status page /status
RUN sed -i '/^;pm\.status_path/s/^;//' /etc/php5/fpm/pool.d/www.conf

# Prevent PHP Warning: 'xdebug' already loaded.
# XDebug loaded with the core
RUN sed -i '/.*xdebug.so$/s/^/;/' /etc/php5/mods-available/xdebug.ini

# Install HHVM
#RUN apt-key adv --recv-keys --keyserver hkp://keyserver.ubuntu.com:80 0x5a16e7281be7a449
#RUN apt-key adv --keyserver hkp://keyserver.ubuntu.com:80 --recv 0C49F3730359A14518585931BC711F9BA15703C6
#RUN echo "deb http://repo.mongodb.org/apt/debian jessie/mongodb-org/3.4 main" | tee /etc/apt/sources.list.d/mongodb-org-3.4.list

RUN apt-get update && apt-get install -y php5-dev php5-cli php-pear php5-mongo curl nano unzip
#RUN pecl install mongo
#RUN pecl install mongodb-1.1.9

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


RUN mkdir -p /session

WORKDIR 'www/api'

COPY './www/api/composer.json' '/www/api'

RUN composer install

# Add configuration files
COPY conf/nginx.conf /etc/nginx/
COPY conf/supervisord.conf /etc/supervisor/conf.d/
COPY conf/php.ini /etc/php5/fpm/conf.d/40-custom.ini

RUN service nginx reload
################################################################################
# Volumes
################################################################################

VOLUME ["/var/www", "/etc/nginx/conf.d"]

################################################################################
# Ports
################################################################################

EXPOSE 80 443 9000

################################################################################
# Entrypoint
################################################################################

ENTRYPOINT ["/usr/bin/supervisord"]
