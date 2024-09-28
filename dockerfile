FROM joomla:php8.3-apache
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    bash \
    curl 
COPY ids-joomla-eb/ /var/www/html/tmp  
#RUN chown -R www-data:www-data /var/www/html/tmp
WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]