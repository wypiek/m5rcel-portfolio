FROM php:8.3-apache

RUN a2enmod headers \
 && sed -i 's/^ServerTokens .*/ServerTokens Prod/; s/^ServerSignature .*/ServerSignature Off/' /etc/apache2/conf-available/security.conf \
 && echo 'expose_php = Off' > /usr/local/etc/php/conf.d/site.ini

COPY deploy/apache.conf /etc/apache2/sites-available/000-default.conf
# Pass GITHUB_TOKEN through from the container environment.
RUN sed -i 's|# SetEnv GITHUB_TOKEN.*|PassEnv GITHUB_TOKEN|' /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/m5rcel
COPY app app
COPY bin bin
COPY public public
RUN mkdir -p cache && chown www-data:www-data cache

ENV GITHUB_TOKEN=""
EXPOSE 80
