To deploy your Laravel API for free, you can use services like Heroku. Here are the steps to deploy your Laravel API on Heroku:

1. **Install the Heroku CLI**: Download and install the Heroku CLI from the [Heroku Dev Center](https://devcenter.heroku.com/articles/heroku-cli).

2. **Login to Heroku**: Open your terminal and log in to Heroku.

   ```sh
   heroku login
   ```

3. **Prepare your Laravel project**:

   - Ensure your `composer.json` file has the necessary dependencies.
   - Add a `Procfile` to your project root to specify the web server command.

4. **Create a new Heroku app**:

   ```sh
   heroku create your-app-name
   ```

5. **Add a database (optional)**: If your API uses a database, you can add a free Heroku Postgres database.

   ```sh
   heroku addons:create heroku-postgresql:hobby-dev
   ```

6. **Configure environment variables**: Set your environment variables on Heroku.

   ```sh
   heroku config:set APP_KEY=your-app-key
   heroku config:set APP_ENV=production
   heroku config:set APP_DEBUG=false
   heroku config:set DB_CONNECTION=pgsql
   heroku config:set DB_HOST=your-database-host
   heroku config:set DB_PORT=5432
   heroku config:set DB_DATABASE=your-database-name
   heroku config:set DB_USERNAME=your-database-username
   heroku config:set DB_PASSWORD=your-database-password
   ```

7. **Deploy your code**:

   - Initialize a git repository if you haven't already.
     ```sh
     git init
     git add .
     git commit -m "Initial commit"
     ```
   - Push your code to Heroku.
     ```sh
     git push heroku master
     ```

8. **Run migrations**:
   ```sh
   heroku run php artisan migrate
   ```

### Example of `Procfile`

Create a `Procfile` in the root of your project with the following content:

web: vendor/bin/heroku-php-apache2 public/
