# Image Gallery

A Laravel + Filament based image gallery.

## Requirements

-   PHP 8.2+
-   Composer
-   Node.js & NPM
-   Docker (optional, for PostgreSQL)

## Setup

1. Clone the repo:

    ```bash
    git clone https://github.com/moundhermesfar/art-gallery.git
    cd art-gallery
    ```

2. Install dependencies:

    ```bash
    composer install
    npm install && npm run build
    ```

3. Copy environment file:

    ```bash
    cp .env.example .env
    php artisan key:generate
    ```

4. Start database (choose one):

    **With Docker (Recommended for easy setup):**

    I've included a `docker-compose.yml` file that sets up a PostgreSQL database container for you. This makes it super easy to get started without having to install and configure PostgreSQL locally. Just run:

    ```bash
    docker compose up -d
    ```

    The Docker setup includes:

    - PostgreSQL 15 database
    - Pre-configured with database name, user, and password
    - Persistent data storage
    - Ready to use with the default `.env` configuration

    Or run your own Postgres/MySQL DB and update `.env` accordingly.

5. Run migrations and create storage link:

    ```bash
    php artisan migrate
    php artisan storage:link
    ```

6. Create a Filament user:

    ```bash
    php artisan make:filament-user
    ```

7. Start the app:

    ```bash
    composer run dev
    ```

8. Access the app at http://localhost:8000
