#!/bin/sh
set -eu

APP_DATA_DIR=storage/app/docker
APP_KEY_FILE="$APP_DATA_DIR/app.key"
DATABASE_FILE="$APP_DATA_DIR/database.sqlite"

mkdir -p "$APP_DATA_DIR"

if [ -z "${APP_KEY:-}" ]; then
    if [ ! -s "$APP_KEY_FILE" ]; then
        php artisan key:generate --show > "$APP_KEY_FILE.tmp"
        mv "$APP_KEY_FILE.tmp" "$APP_KEY_FILE"
    fi

    APP_KEY="$(cat "$APP_KEY_FILE")"
    export APP_KEY
fi

touch "$DATABASE_FILE"
php artisan migrate --force

exec php artisan serve --host=0.0.0.0 --port=8000
