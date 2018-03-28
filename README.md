# Team Registration System

## Prerequisites
* Docker
* Docker Compose

## Setup
1. Copy the file .env.dist to .env and update the values
1. Run `docker run -it --rm -v $PWD:/app composer install`
2. Run `docker run -it --rm -v $PWD:/usr/src/app -w /usr/src/app node yarn install`
3. Run `docker run -it --rm -v $PWD:/usr/src/app -w /usr/src/app node yarn run encore production`
4. Run `docker-compose up -d`
5. Visit in your browser `http://127.0.0.1:39876`