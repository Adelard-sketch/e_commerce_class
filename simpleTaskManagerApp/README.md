# Simple Task Manager

A small PHP and MySQL CRUD task manager.

## Local setup

1. Create a MySQL database.
2. Copy the database values into `db.php`, or set these environment variables:

   - `TASKS_DB_HOST`
   - `TASKS_DB_USER`
   - `TASKS_DB_PASS`
   - `TASKS_DB_NAME`

3. Open `setup.php` once to create the `tasks` table.
4. Open `index.php` to use the app.

## Live deployment

Upload the PHP and CSS files to the hosting account, then update the database values in the server copy of `db.php`. Do not commit real database credentials to GitHub.

After setup succeeds, remove `setup.php` from the live server.