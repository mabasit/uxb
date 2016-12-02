# UXB Blog

### Installation

- Clone the repository and run `composer install` in the root.
- Create a new `.env` file in the root and enter the database configuration details as
  `DB_CONNECTION=mysql`
  `DB_HOST=127.0.0.1`
  `DB_PORT=3306`
  `DB_DATABASE=database_name`
  `DB_USERNAME=user_name`
  `DB_PASSWORD=password`
- Run `php artisan key:generate` to generate a new secret key.
- Migrate and seed the database by running
   `php artisan migrate`
   `php artisan db:seed`
- Start the server by running `php artisan serve`

##### Happy blogging