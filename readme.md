# UXB Blog

### Installation

- Clone the repository and run `composer install` in the root.
- Edit `.env` file in the root to enter the database details.
- Migrate and seed the database by running
   `php artisan migrate`
   `php artisan db:seed`
- Run `php artisan key:generate` to generate a new secret key.
- Start the server by running `php artisan serve`

##### Happy blogging