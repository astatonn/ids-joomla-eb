# Ambiente local para experimentar o pacote. Por padrão usa a imagem oficial mais recente do Joomla.
# Para outra versão: JOOMLA_IMAGE=joomla:5.4-php8.3-apache docker compose up --build
ARG JOOMLA_IMAGE=joomla:php8.3-apache
FROM ${JOOMLA_IMAGE}

# O pacote completo tem cerca de 8 MB; o padrão do PHP (2 MB) impediria o envio pelo painel.
RUN printf 'upload_max_filesize = 32M\npost_max_size = 34M\n' > /usr/local/etc/php/conf.d/ids-joomla-eb.ini

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
