# Import products

## Pre-install
To start working with the app you have to have `docker` and `docker-compose` installed.

## Installation
* Run `docker-compose up -d` inside `/docker` directory
* Create configuration files for `docker` and app from examples and fill in your credentials.

`cp docker/.env.exaple docker/.env`

`cp app/config.example.php app/config.php`

## Usage
1. Open [http://localhost][local]
2. Choose the file you want products to be imported from
3. Click on the `Import` btn

_The result should be displayed above the form._

[local]: http://localhost