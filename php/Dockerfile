FROM php:8.4-fpm-alpine

RUN apk add --no-cache nginx supervisor libzip-dev sqlite-dev oniguruma-dev \
    && docker-php-ext-install pdo_sqlite zip mbstring

RUN mkdir -p /var/www/sdkarjo2 /run/nginx /var/log/supervisor

COPY . /var/www/sdkarjo2

RUN chmod 777 /var/www/sdkarjo2/data && \
    chmod -R 777 /var/www/sdkarjo2/public/uploads

COPY nginx.conf /etc/nginx/http.d/default.conf
COPY supervisord.conf /etc/supervisord.conf

EXPOSE 7723

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
