#!/usr/bin/env bash
set -euo pipefail

is_true() {
    case "${1:-}" in
        1|true|TRUE|True|yes|YES|Yes|on|ON|On)
            return 0
            ;;
        *)
            return 1
            ;;
    esac
}

initialize_storage() {
    mkdir -p /var/www/storage

    if [ -d /opt/storage-template ] && [ ! -f /var/www/storage/.docker-initialized ]; then
        cp -a /opt/storage-template/. /var/www/storage/
        touch /var/www/storage/.docker-initialized
    fi

    mkdir -p \
        /var/www/storage/app/public \
        /var/www/storage/framework/cache \
        /var/www/storage/framework/cache/data \
        /var/www/storage/framework/sessions \
        /var/www/storage/framework/views \
        /var/www/storage/logs \
        /var/www/bootstrap/cache

    touch /var/www/storage/logs/laravel.log
}

prepare_sqlite_database() {
    if [ "${DB_CONNECTION:-sqlite}" != "sqlite" ]; then
        return 0
    fi

    local db_path="${DB_DATABASE:-/var/www/storage/app/database.sqlite}"
    local image_seed_db="/var/www/database/database.sqlite"

    mkdir -p "$(dirname "$db_path")"

    if [ ! -s "$db_path" ]; then
        if [ -s "$image_seed_db" ] && [ "$db_path" != "$image_seed_db" ]; then
            cp "$image_seed_db" "$db_path"
        else
            touch "$db_path"
        fi
    fi
}

ensure_storage_link() {
    if [ ! -L /var/www/public/storage ]; then
        php artisan storage:link >/dev/null 2>&1 || true
    fi
}

run_migrations() {
    if ! is_true "${RUN_MIGRATIONS:-true}"; then
        return 0
    fi

    local max_attempts="${MIGRATION_MAX_ATTEMPTS:-12}"
    local retry_delay="${MIGRATION_RETRY_DELAY:-5}"
    local attempt=1

    until php artisan migrate --force; do
        if [ "$attempt" -ge "$max_attempts" ]; then
            echo "Migration failed after ${max_attempts} attempts."
            return 1
        fi

        echo "Migration attempt ${attempt} failed. Retrying in ${retry_delay} seconds..."
        attempt=$((attempt + 1))
        sleep "$retry_delay"
    done
}

run_optimization() {
    if is_true "${RUN_OPTIMIZE:-true}"; then
        php artisan optimize
    fi
}

fix_permissions() {
    chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
}

initialize_storage
prepare_sqlite_database
fix_permissions
ensure_storage_link
run_migrations
run_optimization

exec "$@"
