# In terminal, run: chmod +x build.sh

# run in terminal for test: sh build.sh

#!/bin/bash

# This is a custom build script for a Laravel project on Digital Ocean App Platform.
# It installs the required PHP exif extension.

# Update package list
apt-get update

# Install PHP exif extension
apt-get install -y php8.2-exif

# Create or Recreate the storage symlink
# php artisan storage:link

# Optimize the application
php artisan config:cache
php artisan cache:clear
php artisan route:cache
php artisan view:cache
php artisan route:clear