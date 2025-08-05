#!/bin/bash

echo "Running migrations on testing database"
./vendor/bin/sail artisan migrate --env=testing

echo "Seeding testing database"
./vendor/bin/sail artisan db:seed --env=testing

echo "Running tests"
./vendor/bin/sail test
