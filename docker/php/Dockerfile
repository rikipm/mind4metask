FROM php:8.3-fpm-alpine

ARG UID
ARG GID

COPY --from=composer:2.2 /usr/bin/composer /usr/local/bin/composer

RUN apk --no-cache add postgresql-dev

RUN docker-php-ext-install pgsql pdo_pgsql

RUN apk add --update nodejs npm

RUN addgroup \
    --gid "$GID" \
    "user" \
&&  adduser \
    --disabled-password \
    --gecos "" \
    --ingroup "user" \
    --uid "$UID" \
    user

USER "$UID:$GID"
