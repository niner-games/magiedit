@echo off
cls

set "reply="
set /p "reply=Do you want to run this deploy script? [y/N]: "
if /i not "%reply%"=="Y" (
    exit /b 0
)

cls

echo.
echo STEP 1. Pulling latest changes from Git...
echo.

git pull origin main

if %errorlevel% neq 0 goto error

echo.
echo STEP 2. Installing Composer dependencies...
echo.

composer install --no-dev --optimize-autoloader

if %errorlevel% neq 0 goto error

echo.
echo STEP 3. Building Laravel cache...
echo.

php artisan config:cache

if %errorlevel% neq 0 goto error

php artisan route:clear

if %errorlevel% neq 0 goto error

php artisan view:cache

if %errorlevel% neq 0 goto error

echo.
echo STEP 4. Optimizing Filament views...
echo.

php artisan filament:optimize

if %errorlevel% neq 0 goto error

echo.
echo STEP 5. Enabling maintenance mode...
echo.

php artisan down --retry=60

if %errorlevel% neq 0 goto error

echo.
echo STEP 6. Running database migrations...
echo.

php artisan migrate --force

if %errorlevel% neq 0 goto error

echo.
echo STEP 7. Disabling maintenance mode...
echo.

php artisan up

if %errorlevel% neq 0 goto error

echo.
echo Deployment COMPLETED successfully!
echo.

goto end

:error

echo.
echo Disabling maintenance mode...
php artisan up >nul 2>&1

echo.
echo Deployment FAILED! Check error messages above.
echo.

exit /b 1

:end
