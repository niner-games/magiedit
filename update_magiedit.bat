@echo off
cls

set "reply="
set /p "reply=Do you want to run this script? [Y/N]: "
if /i not "%reply%"=="Y" (
    exit /b 0
)

echo.
echo Pulling latest changes from Git...
echo.

git pull origin main

if %errorlevel% neq 0 goto error

echo.
echo Installing Composer dependencies...
echo.

composer install --no-dev --optimize-autoloader

if %errorlevel% neq 0 goto error

echo.
echo Building Laravel Cache...
echo.

php artisan optimize:clear
php artisan optimize
php artisan view:cache

if %errorlevel% neq 0 goto error

echo.
echo Optimizing Filament views...
echo.

php artisan filament:optimize

if %errorlevel% neq 0 goto error

echo.
echo Running Database migrations...
echo.

php artisan migrate --force

if %errorlevel% neq 0 goto error

echo.
echo Deployment completed successfully!
echo.

goto end

:error

echo.
echo Deployment FAILED! Check error messages above.
echo.

exit /b 1

:end
