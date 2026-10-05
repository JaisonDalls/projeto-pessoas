#!/bin/sh
set -eu

APP_DATA_DIR=storage/app/docker
APP_KEY_FILE="$APP_DATA_DIR/app.key"
DATABASE_FILE="$APP_DATA_DIR/database.sqlite"

mkdir -p "$APP_DATA_DIR"

if [ -z "${APP_KEY:-}" ]; then
    if [ "${APP_ENV:-local}" = production ]; then
        echo "APP_KEY must be set when APP_ENV=production." >&2
        exit 1
    fi

    if [ ! -s "$APP_KEY_FILE" ]; then
        php artisan key:generate --show > "$APP_KEY_FILE.tmp"
        mv "$APP_KEY_FILE.tmp" "$APP_KEY_FILE"
    fi

    APP_KEY="$(cat "$APP_KEY_FILE")"
    export APP_KEY
fi

if [ "${DB_CONNECTION:-sqlite}" = sqlite ]; then
    touch "$DATABASE_FILE"
fi

php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port="${PORT:-10000}"
