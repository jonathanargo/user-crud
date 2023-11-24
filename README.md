# user-crud
A simple example CRUD using Laravel and Bootstrap. Also includes an exercise in creating a custom ORM class.

## Project Parameters
This project was completed as part of an assignment for an interview. Per the instructions, I was to create a responsive, mobile friendly form for managing a "Users" table, as well as a custom built ORM. Initially, I just created the User model via the Laravel's Doctrine ORM and focused on getting the user interface done. Afterwards, I replaced the User model with the new "ManualUser" class that does not use Doctrine. All of the saving, loading, updating, etc is handled manually via the database connector.

## Running the project locally via sail
Note: requires Composer and Docker

Clone the repository: 
`git clone https://github.com/jonathanargo/user-crud.git && cd user-crud`

Install composer dependencies: 
`composer install`

Build the image: 
`vendor/bin/sail build`

Run the container: 
`vendor/bin/sail up -d`

Run migrations: 
`vendor/bin/sail artisan migrate`

Run the vite server for the front end: 
`sail npm run dev`

You should be able to access the application running on http://localhost. If you're getting connection errors, you may need to use a private browser window (in my experience Chrome automatically redirected me to https).

## Tests
A PHPUnit test is included for confirming that the ManualUser class meets all of the project parameters. Run it using the following command:

`sail artisan test`
