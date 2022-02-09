Copy .env.example to a new file .env and set the DB connection params. Example I used:

```
DATABASE_URL="mysql://<db_user>:<db_pass>@127.0.0.1:3306/<db_name>"
```
Create a database and run:
```
composer install
```

Execute the migrations to update the database.
