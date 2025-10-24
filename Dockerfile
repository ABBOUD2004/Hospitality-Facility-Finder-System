# استخدم PHP 8.3 مع Apache
FROM php:8.3-apache

# إعدادات البيئة
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

# انسخ ملفات المشروع إلى السيرفر
COPY . /var/www/html

# إعداد الـ Apache حتى يشير إلى مجلد public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# تثبيت التبعيات المطلوبة للـ Laravel
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev zip curl \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# تثبيت الحزم
RUN composer install --no-dev --optimize-autoloader

# ضبط التصاريح
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# تفعيل rewrite module
RUN a2enmod rewrite

# المنفذ
EXPOSE 80

CMD ["apache2-foreground"]
