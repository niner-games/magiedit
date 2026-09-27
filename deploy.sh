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

GRAY='\033[1;30m'
GREEN='\033[0;32m'
RED='\033[0;31m'
BOLD='\033[1m'
RESET_BOLD='\033[22m'
NC='\033[0m'

read -p "Do you want to run this script? [Y/N]: " confirm

case "$confirm" in
    [yY])
        ;;
    *)
        echo -e "\nDeployment cancelled."
        exit 0
        ;;
esac

clear

set -e
trap 'error_handler' ERR

error_handler() {
    echo -e "\n\n\n${RED}Deployment ${BOLD}FAILED${RESET_BOLD}! Check error messages above.${NC}\n"
    exit 1
}

echo -e "\n${GRAY}${BOLD}STEP 1.${RESET_BOLD} Pulling latest changes from Git...${NC}\n"

git pull origin main

echo -e "\n${GRAY}${BOLD}STEP 2.${RESET_BOLD} Installing Composer dependencies...${NC}\n"

$COMPOSER_BIN install --no-dev --optimize-autoloader

echo -e "\n${GRAY}${BOLD}STEP 3.${RESET_BOLD} Building Laravel Cache...${NC}\n"

$PHP_BIN artisan optimize:clear
$PHP_BIN artisan config:cache
$PHP_BIN artisan route:cache

echo -e "\n${GRAY}${BOLD}STEP 4.${RESET_BOLD} Optimizing Filament views...${NC}\n"

$PHP_BIN artisan filament:optimize

echo -e "\n${GRAY}${BOLD}STEP 5.${RESET_BOLD} Running Database migrations...${NC}\n"

$PHP_BIN artisan migrate --force

echo -e "\n\n\n${GREEN}Deployment ${BOLD}COMPLETED${RESET_BOLD} successfully!${NC}\n"
