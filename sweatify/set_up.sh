#!/bin/bash
echo "Starting Sweatify setup..."

# 1. Copy .env if it doesn't exist
if [ ! -f .env ]; then
  echo "Copying .env file..."
  cp .env.example .env
else
  echo ".env file already exists, skipping copy."
fi

# 2. Start Docker containers
echo "Starting Docker containers with Sail..."
./vendor/bin/sail up -d

# 3. Install PHP dependencies
echo "Installing PHP dependencies..."
./vendor/bin/sail composer install

# 4. Install JS dependencies
echo "Installing Node dependencies..."
./vendor/bin/sail npm install

# 5. Build frontend assets
echo "Building frontend assets..."
./vendor/bin/sail npm run build

# 6. Generate app key
echo "Generating app key..."
./vendor/bin/sail artisan key:generate

# 7. Run migrations and seeders
echo "Migrating and seeding database..."
./vendor/bin/sail artisan migrate --seed

# 8. Import exercise data from JSON
echo "Importing exercise data..."
./vendor/bin/sail artisan app:store-exercise-data

echo "Setup complete! You can now visit http://localhost in your browser."
