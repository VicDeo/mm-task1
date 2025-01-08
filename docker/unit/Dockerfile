FROM unit:php8.3

RUN apt update && apt install -y git zip libzip-dev \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install zip

# Install Composer globally
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add Composer to the PATH
ENV PATH="$PATH:/usr/local/bin"

WORKDIR /www

EXPOSE 80
