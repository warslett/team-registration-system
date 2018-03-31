# Team Registration System

## Prerequisites
* Docker
* Docker Compose

## Setup
1. Create your environment file `cp .env.dist .env`
2. Update the values in .env as required (or use default values as supplied)
3. Run `bin/build`
4. Visit in your browser (with default port the address would be `http://127.0.0.1:39876`)

## Tests
All features are tested by behat scenarios. To run behat use this command
`docker-compose exec php vendor/bin/behat`