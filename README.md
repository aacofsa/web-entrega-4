Create an .env file similar to .env.example and put your DB credentials

Run:
composer install
php artisan jwt:secret
php artisan migrate --seed
php artisan serve