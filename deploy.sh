#!/bin/bash

clear

GRAY='\033[1;30m'
GREEN='\033[0;32m'
RED='\033[0;31m'
BOLD='\033[1m'
RESET_BOLD='\033[22m'
NC='\033[0m'

read -p "Do you want to run this deploy script? [y/N]: " confirm
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
    echo -e "\n${RED}Deployment ${BOLD}FAILED${RESET_BOLD}! Check error messages above.${NC}\n"
    exit 1
}

echo -e "\n${GRAY}${BOLD}STEP 1.${RESET_BOLD} Pulling latest changes from Git...${NC}\n"

git pull origin main

echo -e "\n${GRAY}${BOLD}STEP 2.${RESET_BOLD} Installing Composer dependencies...${NC}\n"

php83 /home/niner/bin/composer install --no-dev --optimize-autoloader

echo -e "\n${GRAY}${BOLD}STEP 3.${RESET_BOLD} Building Laravel Cache...${NC}\n"

php83 artisan optimize:clear
php83 artisan config:cache
php83 artisan route:cache

echo -e "\n${GRAY}${BOLD}STEP 4.${RESET_BOLD} Optimizing Filament views...${NC}\n"

php83 artisan filament:optimize

echo -e "\n${GRAY}${BOLD}STEP 5.${RESET_BOLD} Running Database migrations...${NC}\n"

php83 artisan migrate --force

echo -e "\n${GREEN}Deployment ${BOLD}COMPLETED${RESET_BOLD} successfully!${NC}\n"
