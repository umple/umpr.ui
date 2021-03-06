# umpr.ui
Front-end for [umpr.core](https://github.com/umple-ucosp/umpr.core).

## Requirements:

* PHP 5.3+
* [Composer](https://getcomposer.org/)
* [git](http://git-scm.com/)

## Installing umpr.ui

1. Clone repository: `git clone https://github.com/umple-ucosp/umpr.ui.git`
1. Install composer components: `php composer.phar install`, or `composer install` depending on your environment.
1. Fetch repository data: `./update_data`

## Running

To run the application, run from `/public` folder. 

To run locally: `php -S localhost:8080 -t ./public`.

The application's "data repository" should be updated regularly using the `./update_data` script. 
