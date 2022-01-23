```
docker-compose up
```
Run inside php-fpm container
```
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
watch -n 42 php bin/console app:create-product
```
