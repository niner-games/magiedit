#!/bin/bash

clear

GRAY='\033[1;30m'
GREEN='\033[0;32m'
NC='\033[0m'
BOLD='\033[1m'

read -p "Do you want to run this script? [Y/N]: " confirm
case "$confirm" in
    [yY])
        ;;
    *)
        exit 0
        ;;
esac

set -e

echo -e "\n${GRAY}Pulling latest changes from Git...${NC}\n"

git pull origin main

echo -e "\n${GRAY}Installing Composer dependencies (PHP 8.3)...${NC}\n"

php83 /home/niner/bin/composer install --no-dev --optimize-autoloader

echo -e "\n${GRAY}Building Laravel Cache...${NC}\n"

php83 artisan optimize:clear
php83 artisan config:cache
php83 artisan route:cache

echo -e "\n${GRAY}Optimizing Filament views...${NC}\n"

php83 artisan filament:optimize

echo -e "\n${GRAY}Running Database migrations...${NC}\n"

php83 artisan migrate --force

echo -e "\n${GREEN}${BOLD}Deployment completed successfully!${NC}\n"
