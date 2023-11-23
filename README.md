# user-crud
A simple example CRUD using Laravel and Bootstrap

## Running the project locally via sail
Note: requires Composer and Docker

Clone the repository

`git clone https://github.com/jonathanargo/user-crud.git && cd user-crud`

Install composer dependencies

`composer install`

Build the image

`vendor/bin/sail build`

Run the container

`vendor/bin/sail up -d`

Run the vite server for the front end

`sail npm run dev`

You should be able to access the application running on http://localhost. Use the register link at the top, then navigate to Users using the navigation at the top!
