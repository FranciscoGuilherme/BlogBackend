FROM webdevops/php-nginx:7.4

# ----------------------------------------------
# ---------[Adição de variáveis no ENV]---------
# ----------------------------------------------

ENV WEB_DOCUMENT_ROOT /app/public
ENV WEB_PHP_TIMEOUT 7200
ENV FPM_PROCESS_IDLE_TIMEOUT 7200
ENV FPM_REQUEST_TERMINATE_TIMEOUT 7200
ENV COMPOSER_VERSION 2

# ----------------------------------------------
# ----------[Espelhamento no conteiner]---------
# ----------------------------------------------

COPY . /app

# ----------------------------------------------
# ----------[Configuração do ambiente]----------
# ----------------------------------------------

RUN chmod +x /app/bin/console
RUN mkdir -p /app/config/jwt
RUN unlink /usr/local/bin/composer
RUN ln -s /usr/local/bin/composer${COMPOSER_VERSION:-2} /usr/local/bin/composer
RUN cd app && composer install

# ----------------------------------------------
# ------------[Configuração do JWT]-------------
# ----------------------------------------------

ONBUILD COPY private.pem /app/config/jwt/private.pem
ONBUILD COPY public.pem  /app/config/jwt/public.pem

# ----------------------------------------------
# ------[Exposição das portas do container]-----
# ----------------------------------------------

EXPOSE 9000 80 443

WORKDIR /app