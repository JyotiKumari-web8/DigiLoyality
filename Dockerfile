FROM php:8.3-apache

# Install SQLite and PDO extensions
RUN apt-get update && apt-get install -y \
    libsqlite3-dev \
    && docker-php-ext-install pdo pdo_sqlite pdo_mysql \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Point Apache document root to public/
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy project files
WORKDIR /var/www/html
COPY . /var/www/html/

# Permissions for SQLite database file and directory
RUN chown -R www-data:www-data /var/www/html/config \
    && chmod -R 775 /var/www/html/config

EXPOSE 80
CMD ["apache2-foreground"]
