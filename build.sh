#!/bin/bash

CONTAINER_NAME="panda_php_app"
docker exec -it $CONTAINER_NAME composer install
docker exec -it $CONTAINER_NAME sh -c "cp /var/www/.env.example /var/www/.env"
docker exec -it $CONTAINER_NAME php artisan key:generate

echo "Setup complete."