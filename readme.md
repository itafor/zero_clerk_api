

## Simple steps to set up and run this application on your local machine 

- Clone the project
- Run composer install on your cmd or terminal
- Copy .env.example file to .env on the root folder.
- Open your .env file and change the database name (DB_DATABASE) to whatever you have, username (DB_USERNAME) and password (DB_PASSWORD) field correspond to your configuration.
- Run php artisan key:generate
- Run php artisan migrate
- Run php artisan jwt:secret
- Run php artisan serve
- Run php artisan queue:work //You need this when creating courses from Course factory
- Open the url below to view and run the endpoints in postman

https://documenter.getpostman.com/view/5369523/SztEZ6Tn

You can also see the screenshots of available endpoints in the screenshoot folder


