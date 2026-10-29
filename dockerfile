# Ambiente local para experimentar o pacote. Por padrão usa a imagem oficial mais recente do Joomla 5.4.
# Para outra versão: JOOMLA_IMAGE=joomla:5.4-php8.3-apache docker compose up --build
ARG JOOMLA_IMAGE=joomla:6.2.0-php8.5-apache
FROM ${JOOMLA_IMAGE}

# CVE-2026-109618 e CVE-2026-109619 (PHP2Shell): a imagem base precisa ter o patch.
# O teste impede que uma imagem local em cache, ou uma substituição de JOOMLA_IMAGE, volte a introduzir a CVE.
RUN php -r '$minimum = ["8.2" => "8.2.35", "8.3" => "8.3.36", "8.4" => "8.4.27", "8.5" => "8.5.12"]; $branch = PHP_MAJOR_VERSION . "." . PHP_MINOR_VERSION; if (!isset($minimum[$branch]) || version_compare(PHP_VERSION, $minimum[$branch], "<")) { fwrite(STDERR, "PHP " . PHP_VERSION . " is vulnerable to PHP2Shell (CVE-2026-109618/CVE-2026-109619). Use a patched Joomla/PHP image.\n"); exit(1); }'

# O pacote completo tem cerca de 8 MB; o padrão do PHP (2 MB) impediria o envio pelo painel.
# Desabilitar o progresso de upload é uma medida complementar indicada pelo PHP, sem substituir o patch.
COPY docker/php/conf.d/ids-joomla-eb.ini /usr/local/etc/php/conf.d/zz-ids-joomla-eb.ini

WORKDIR /var/www/html
EXPOSE 80
CMD ["apache2-foreground"]
