FROM php:8.2-fpm

# 必要なパッケージやPHPの拡張モジュールをインストール
RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mysqli

# Node.js のインストール
# NodeSource Node.js 14.x リポジトリの追加
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash -

# Node.js と npm のインストール
RUN apt-get install -y nodejs

WORKDIR /var/www/html

# index.php は docker-compose.yml でマウントされるため、ここではCOPYは不要

