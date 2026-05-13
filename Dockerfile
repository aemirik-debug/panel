FROM serversideup/php:8.4-fpm-nginx

# Trafiğin bağlanacağı portu 8080 (ServerSideUp varsayılanı) olarak ayarlıyoruz
EXPOSE 8080

# Gerekli kurulumlar (Node.js dahil)
USER root
RUN apt-get update && apt-get install -y sqlite3 libsqlite3-dev nodejs npm \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Eksik PHP uzantılarını kur (intl = Filament, gd = Excel/Görsel)
RUN install-php-extensions intl gd

# Dosyaları aktarıyoruz
USER www-data
COPY --chown=www-data:www-data . /var/www/html

# Composer ve NPM (Vite) kurulumları
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts
RUN npm install && npm run build

# Klasör ve veritabanı ayarları
RUN mkdir -p database storage/framework/sessions storage/framework/views storage/framework/cache/data bootstrap/cache
RUN touch database/database.sqlite
RUN chmod -R 777 storage database bootstrap/cache

# Migration işlemi
ENV AUTORUN_LARAVEL_MIGRATION=true
