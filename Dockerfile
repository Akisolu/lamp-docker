FROM alpine:3.20

# Instalar Apache, PHP y herramientas en una sola capa optimizada
RUN apk add --no-cache \
    bash \
    curl \
    apache2 \
    php83-apache2 \
    php83 \
    php83-mysqli \
    php83-pdo_mysql \
    php83-mbstring \
    php83-json \
    php83-openssl \
    php83-curl \
    php83-zlib \
    php83-xml \
    php83-phar \
    php83-intl \
    && mkdir -p /var/www/localhost/htdocs
    
WORKDIR /var/www/localhost/htdocs

EXPOSE 80

CMD ["httpd", "-D", "FOREGROUND"]