FROM alpine:3.20
RUN echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/main/" > /etc/apk/repositories && \
    echo "https://dl-cdn.alpinelinux.org/alpine/v$(cut -d'.' -f1,2 /etc/alpine-release)/community/" >> /etc/apk/repositories && \
    echo "https://dl-cdn.alpinelinux.org/alpine/edge/testing/" >> /etc/apk/repositories
RUN apk update && apk upgrade && apk  add --no-cache  \
    apache2 \
    php82 \
    php82-apache2 \
    php82-fpm \
    php82-bcmath \
    php82-ctype \
    php82-curl \
    php82-dom \
    php82-fileinfo \
    php82-gd \
    php82-iconv \
    php82-intl \
    php82-json \
    php82-mbstring \
    php82-mysqlnd \
    php82-opcache \
    php82-pdo \
    php82-pdo_mysql \
    php82-phar \
    php82-simplexml \
    php82-session \
    php82-tokenizer \
    php82-xml \
    php82-zip \
    curl \
    git \
    unzip \
        && echo "ServerName 127.0.0.1" >> /etc/apache2/httpd.conf \

RUN chmod 770 -R /usr/bin/

RUN    curl -sS https://getcomposer.org/installer | php82 -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /var/www/localhost/htdocs/

COPY ./docker/httpd.conf /etc/apache2/httpd.conf

RUN sed -i '/LoadModule rewrite_module/s/^#//g' /etc/apache2/httpd.conf

EXPOSE 80

CMD ["httpd", "-D", "FOREGROUND"]