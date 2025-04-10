FROM joomla:php8.3-apache

# Instalar pacotes adicionais
RUN apt-get update && \
    apt-get install -y --no-install-recommends \
    bash \
    curl

# Criar um arquivo customizado de configuração do PHP para permitir uploads de até 20 MB
RUN echo "upload_max_filesize = 20M\npost_max_size = 20M" > /usr/local/etc/php/conf.d/custom-php.ini

# Definir o diretório de trabalho
WORKDIR /var/www/html

# Expor a porta 80
EXPOSE 80

# Comando padrão de execução
CMD ["apache2-foreground"]
