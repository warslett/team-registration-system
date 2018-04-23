# Team Registration System [![Build Status](https://circleci.com/gh/warslett/team-registration-system.png?style=shield)](https://circleci.com/gh/warslett/team-registration-system)
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
`docker-compose exec php vendor/bin/phpunit`
## Dev Fixtures (Dummy Data)
For development, you can populate the database with fake data by running this command 
`docker-compose exec php bin/console hautelook:fixtures:load` All users created have the password "development"
For testing the API, use the email address "api@example.com"

## API
In order for the api to run from your environment you must first generate some SSL keys so that the user's access tokens
can be securely encrypted. If you do not carry out this step the API will not work at all. In a development environment
it is acceptable to use the distributed keys by just running `cp ssl/jwt.dist/* ssl/jwt`. In a production environment
you should create your own keys by running the following commands.
``` bash
docker-compose exec php openssl genrsa -out ssl/jwt/private.enc.pem -aes256 4096
docker-compose exec php openssl rsa -pubout -in ssl/jwt/private.enc.pem -out ssl/jwt/public.pem
docker-compose exec php openssl rsa -in ssl/jwt/private.enc.pem -out ssl/jwt/private.pem
```

Once you have your keys you should ensure the permissions are correct by running these two commands:
```bash
docker-compose exec php chgrp www-data ssl/jwt/private.pem
docker-compose exec php chmod 750 ssl/jwt/private.pem
```

### Basic usage
First authenticate with the API by sending a POST request to /api/login with the email and password of a user with the
role ROLE_API_USER (see Dev Fixtures section above for out the box access in dev) included in the form data.
You can do this with curl like this:

`curl -X POST http://127.0.0.1:39876/api/login -d _username="api@example.com" -d _password="development"`

This will return you a JSON Web Token (JWT) that you can pass to every subsequent request in the Authorization header to
prove who you are. So for example, to get a list of events from the system you can do this:

`curl http://127.0.0.1:39876/api/events -H "Authorization: Bearer YOUR_TOKEN_GOES_HERE"`

which will return something that looks like this:

```json
{  
   "@context":"/api/contexts/Event",
   "@id":"/api/events",
   "@type":"hydra:Collection",
   "hydra:member":[  
      {  
         "@id":"/api/events/12",
         "@type":"Event",
         "id":12,
         "name":"Previous Three Towers",
         "hikes":[  
            "/api/hikes/18",
            "/api/hikes/20",
            "/api/hikes/22",
            "/api/hikes/24"
         ],
         "date":"2017-07-22T00:00:00+00:00"
      },
      {  
         "@id":"/api/events/13",
         "@type":"Event",
         "id":13,
         "name":"Upcoming Three Towers",
         "hikes":[  
            "/api/hikes/19",
            "/api/hikes/21",
            "/api/hikes/23",
            "/api/hikes/25"
         ],
         "date":"2018-05-23T00:00:00+00:00"
      }
   ],
   "hydra:totalItems":2
}
```

Collections like these paginate up to 30 items in a single response. Pagination information is specified in the property
`hydra:view`. For example:

`curl http://127.0.0.1:39876/api/teams -H "Authorization: Bearer YOUR_TOKEN_GOES_HERE"`

```json
{  
   "@context":"/api/contexts/Team",
   "@id":"/api/teams",
   "@type":"hydra:Collection",
   "hydra:member":[  
      {  
         "@id":"/api/teams/1",
         "@type":"Team",
         "name":"Nihil repellat ut.",
         "hike":"/api/hikes/18",
         "id":1
      },
      {  
         "@id":"/api/teams/2",
         "@type":"Team",
         "name":"Est est alias.",
         "hike":"/api/hikes/18",
         "id":2
      },
      ...
   ],
   "hydra:totalItems":147,
   "hydra:view":{  
      "@id":"/api/teams?page=1",
      "@type":"hydra:PartialCollectionView",
      "hydra:first":"/api/teams?page=1",
      "hydra:last":"/api/teams?page=5",
      "hydra:next":"/api/teams?page=2"
   },
   "hydra:search":{  
      "@type":"hydra:IriTemplate",
      "hydra:template":"/api/teams{?hike,hike[]}",
      "hydra:variableRepresentation":"BasicRepresentation",
      "hydra:mapping":[  
         {  
            "@type":"IriTemplateMapping",
            "variable":"hike",
            "property":"hike",
            "required":false
         },
         {  
            "@type":"IriTemplateMapping",
            "variable":"hike[]",
            "property":"hike",
            "required":false
         }
      ]
   }
}
```

Accessing the subsequent page can be achieved using the the page parameter in the query string eg.
`curl http://127.0.0.1:39876/api/teams?page=2 -H "Authorization: Bearer YOUR_TOKEN_GOES_HERE"`

Results can also be filtered using the query string. For example, to find only the teams for hike with id the 18, you
would do the following:
`curl http://127.0.0.1:39876/api/teams?hike=18 -H "Authorization: Bearer YOUR_TOKEN_GOES_HERE"`

You can provide multiple values for the same argument like this:
`curl http://127.0.0.1:39876/api/teams?hike[]=18&hike[]=19 -H "Authorization: Bearer YOUR_TOKEN_GOES_HERE"`
which would return all teams for either hike 18 or hike 19.