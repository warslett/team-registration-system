# Team Registration System

## Prerequisites
* Docker
* Docker Compose

## Setup
1. Clone this repo `git clone https://github.com/warslett/team-registration-system.git && cd team-registration-system`
2. Create your environment file `cp .env.dist .env`
3. If you are setting up a production environment, update the values in .env
4. Run `bin/build`
5. Visit in your browser (with default port the address would be `http://127.0.0.1:39876`)

## Tests
All features are tested by behat scenarios. To run behat use this command
`docker-compose exec php vendor/bin/behat`

Low level functionality is unit tested with PHPUnit. To run phpunit use this command
`docker-compose exec php php vendor/bin/phpunit`
