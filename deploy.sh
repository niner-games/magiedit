#!/bin/bash

PHP_CMD="${1:-php83}"

if [ "$PHP_CMD" = "php" ]; then
    PHP_BIN="php"
    COMPOSER_BIN="composer"
else
    PHP_BIN="php83"
    COMPOSER_BIN="php83 /home/niner/bin/composer"
fi

clear

GREEN='\033[0;32m'
GRAY='\033[1;30m'
RED='\033[0;31m'
NC='\033[0m'

read -p "Do you want to run this script? [Y/N]: " confirm

case "$confirm" in
    [yY])
        ;;
    *)
        exit 0
        ;;
esac

clear

set -e
trap 'error_handler' ERR

error_handler() {
    echo -e "\n${RED}Deployment FAILED! Check error messages above.${NC}\n"
    exit 1
}

echo -e "\n${GRAY}STEP 1. Pulling latest changes from Git...${NC}\n"

git pull origin main

echo -e "\n${GRAY}STEP 2. Installing Composer dependencies...${NC}\n"

$COMPOSER_BIN install --no-dev --optimize-autoloader

echo -e "\n${GRAY}STEP 3. Building Laravel Cache...${NC}\n"

$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache

echo -e "\n${GRAY}STEP 4. Optimizing Filament views...${NC}\n"

$PHP_BIN artisan filament:optimize

echo -e "\n${GRAY}STEP 5. Running Database migrations...${NC}\n"

$PHP_BIN artisan migrate --force

echo -e "\n${GREEN}Deployment COMPLETED successfully!${NC}\n"
