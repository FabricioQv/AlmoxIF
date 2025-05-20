FROM php:8.1-apache

# Instala dependências do sistema necessárias para gd e zip
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql mysqli gd zip

# Ativa o mod_rewrite do Apache
RUN a2enmod rewrite

# Copia configuração customizada do virtual host
COPY vhost.conf /etc/apache2/sites-available/000-default.conf

# Copia os arquivos do projeto para a pasta padrão do Apache
COPY src/ /var/www/html/

# Permissões
RUN chown -R www-data:www-data /var/www/html
