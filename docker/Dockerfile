FROM php:7.4-apache

RUN mv /usr/local/etc/php/php.ini-development /usr/local/etc/php/php.ini

RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli

RUN echo "file_uploads = On\n" \
         "memory_limit = 500M\n" \
         "upload_max_filesize = 500M\n" \
         "post_max_size = 500M\n" \
         "max_execution_time = 600\n" \
         > /usr/local/etc/php/conf.d/uploads.ini

RUN pecl install xdebug \
    && echo 'zend_extension=/usr/local/lib/php/extensions/no-debug-non-zts-20190902/xdebug.so' >> /usr/local/etc/php/php.ini

# xdebug
ADD apache/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

#change default apache2 configuration
RUN a2enmod proxy proxy_http proxy_connect rewrite \
    && rm /etc/apache2/sites-available/default-ssl.conf
ADD ./apache/apache2.conf /etc/apache2/apache2.conf

WORKDIR /var/www/html

EXPOSE 80