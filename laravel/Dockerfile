FROM php:8.3-fpm

# Установка системных зависимостей
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# Установка стандартных расширений
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# УСТАНОВКА PCOV
RUN pecl install pcov \
    && echo "extension=pcov.so" > /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini

# Убедимся, что файл создан
RUN cat /usr/local/etc/php/conf.d/docker-php-ext-pcov.ini

# Установка Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Рабочая директория
WORKDIR /var/www/html

# Изменение порта FPM (если нужно)
RUN sed -i 's/listen = .*/listen = 0.0.0.0:9000/' /usr/local/etc/php-fpm.d/www.conf

CMD ["php-fpm"]
