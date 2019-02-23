FROM php:7.2.15-cli
RUN apt-get update && apt-get install -y wget && rm -rf /var/lib/apt/lists/*r
RUN docker-php-ext-install bcmath calendar
RUN wget https://getcomposer.org/composer.phar && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer
WORKDIR /var/www/lendinvest