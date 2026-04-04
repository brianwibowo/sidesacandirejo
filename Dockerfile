FROM php:8.1-apache

# Install ekstensi mysqli
RUN docker-php-ext-install mysqli

# Copy kode aplikasi
COPY . /var/www/html/

WORKDIR /var/www/html

EXPOSE 80