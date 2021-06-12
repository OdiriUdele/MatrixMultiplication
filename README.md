<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>


## Project:2x2  Matrix Multiplication Parse Result to Alphabets.

This is a laravel application that takes some input from a POST endpoint and parses it into a matrix where the numeric values are its alphabetical representation (ie 1 => A, 28 => AC).  Tools Used:

- Laravel Framework => ^8.40
- Laravel tymon/jwt-auth=> "^1.0. (For Authentication)
- Php => 7.4.15).

I made use of Requests, a controller and a service.

## Testing and Validation

Validation will check that the matrices follow the principle of A matrix columns equaling B matrix rows. If this validation fails an error is returned

Testing will ensure the code is written to accomodate scenarios such as a matrix of unequal columns or rows. As well as input with alphanumeric characters as all input values should be numeric.

## Setup

- Clone repo
- Run composer intall/update
- copy .env.example to .env
- Run Php artisan key:generate
- Run Php Artisan jwt:secret
- Run Php Artisan migrate.
## Test Setup
- Run <b>php artisan config:cache</b>
- Run <b>php artisan config:clear</b>
- Run <b>touch database/Matrix_multiplication.sqlite </b> on git bash or create a file named <b>Matrix_multiplication.sqlite </b> in the database directory of the application.
- Run <b> php artisan migrate --database=sqlite</b>
- lastly run <b>php artisan config:cache --env=testing</b> and <b>php artisan config:clear</b> again.

## API Documentation
This application at the time of this writing has some  endpoints for authentication and a single endpoint for  '/api/2by2matrix'. To view the parameter requirements and expected return values, check out matrix.raml(within the /docs directory). for more detailed documentation please visit the postman documenter url below.<br>
**[Api Documentation Url ](https://documenter.getpostman.com/view/14675063/TzeTJVCc)**


