
# Sweatify

## Overview
Sweatify is a fitness application designed for gym lovers and newcomers alike who want to transform their lifestyle. This registration-based app provides intelligent workout plans, personalized coaching, and progress tracking to help users achieve their fitness goals, whether it be losing weight, building muscle, or maintaining a healthy lifestyle. Sweatify consolidates all essential fitness features into one platform, eliminating the need for multiple fitness apps.

## Features
- **User Authentication**: Secure authentication with Laravel Breeze and Sanctum.
- **Custom Workout Plans**: Personalized exercise routines based on user preferences.
- **Progress Tracking**: Monitor and analyze fitness progress over time.
- **Exercise Library**: Extensive database of exercises categorized by body part, equipment, and target muscle.
- **Responsive UI**: Built using Blade templates for a seamless experience across devices.

## Tech Stack
- **Backend**: Laravel (PHP) with Eloquent ORM for database management
- **Frontend**: Blade templates
- **Authentication**: Laravel Breeze and Sanctum
- **Database**: MySQL with Eloquent ORM
- **API**: Custom-built REST API
- **Containerization**: Docker (optional for deployment)

## Installation
### Prerequisites
- **PHP** >= 8.2
- **Composer** (PHP dependency manager)
- **MySQL** (or Dockerized MySQL instance)
- **Node.js & NPM** (for frontend assets)

### Setup Steps
1. Clone the repository:
    ```bash
    git clone https://github.com/schuschii/sweatify
    cd sweatify
    ```
2. Install dependencies:
    ```bash
    composer install
    npm install && npm run dev
    ```
3. Set up environment variables:
    ```bash
    cp .env.example .env
    php artisan key:generate
    ```
4. Configure the database in `.env` and run migrations:
    ```bash
    php artisan migrate --seed
    ```
5. Run custom artisan command to import exercise data from json file to database
   ```bash
    php artisan app:store-exercise-data
   ```
6. Start the local development server:
    ```bash
    php artisan serve
    ```
7. (Optional) Run tests:
    ```bash
    php artisan test
    ```

## Business Model
Sweatify operates on a free-to-use model where users gain full access to the platform upon registration. The app is designed to provide value without requiring subscriptions or in-app purchases.

## Contribution
Thanks to all the contributors!  
See [contributors](https://github.com/schuschii/sweatify/graphs/contributors)

## License
This project is open-sourced under the MIT License.

## Contact
For any inquiries or support, reach out:
- **GitHub**: [contact](https://github.com/schuschii/sweatify)

---
### Additional Documentation
For API endpoints and in-depth product details, refer to the **[Product Design Document (PDD)](docs/PDD.md)**.


