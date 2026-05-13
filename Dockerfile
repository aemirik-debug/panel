FROM serversideup/php:8.4-fpm-nginx

# Trafiğin bağlanacağı portu Railway arayüzünüzdeki 8000 olarak ayarlıyoruz
EXPOSE 8000

# Gerekli kurulumlar
USER root
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev php8.4-intl php8.4-gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# ServerSideUp imajının Nginx ayarlarında gömülü olan 8080 portunu 8000 ile değiştiriyoruz.
RUN find /etc/nginx -type f -exec sed -i 's/8080/8000/g' {} +

# Dosyaları aktarıyoruz
USER www-data
COPY --chown=www-data:www-data . /var/www/html

# Composer ile kurulum
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Klasör ve veritabanı ayarları
RUN mkdir -p database storage/framework/sessions storage/framework/views storage/framework/cache/data bootstrap/cache
RUN chmod -R 777 storage database bootstrap/cache

# Migration işlemi
ENV AUTORUN_LARAVEL_MIGRATION=true
