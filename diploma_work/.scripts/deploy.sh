#!/bin/bash
set -e

echo "Deployment started ..."

# Enter maintenance mode or return true if already in maintenance mode
(php artisan down) || true

# Check for uncommitted changes and stash them
if [ -n "$(git status --porcelain)" ]; then
    git stash --include-untracked
    STASHED=true
else
    STASHED=false
fi

# Pull the latest version of the app
git pull origin main --no-edit

# Apply stashed changes, if any
if [ "$STASHED" = true ]; then
    git stash apply || true
    git stash drop || true
fi

# Install composer dependencies
composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader

# Clear the old cache
php artisan clear-compiled

# Recreate cache
php artisan optimize

# Run database migrations
php artisan migrate --force

# Exit maintenance mode
php artisan up

echo "Deployment finished!"
