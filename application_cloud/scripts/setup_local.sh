#!/application_cloud/env bash
echo "download laravel dependencies"
cd server
composer install

echo "migrate database"
php artisan migrate

echo "passport for JWT token generate keys"
php artisan passport:install

echo "move to web and download dependencies"
cd ..
cd web
npm i -g parcel-bundler
npm i

echo "if all went well start server"
cd ..
npm i
npm run dev