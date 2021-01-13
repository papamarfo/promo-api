# Promo API

A promo api solution written using the Laravel web framework.


## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prerequisites

* [Composer](https://getcomposer.org/doc/00-intro.md)
* \>= PHP 7.3

### Installing

Please run the following commands to setup your development env up.

```bash
# clone this repository
git clone https://github.com/yawmanford/promo-api.git

# change directory
cd promo-api/

# install the project's dependencies using Composer
composer install

# make a copy of the .env.example to configure the application 
# for your local environment
cp .env.example .env

# generate application key
php artisan key:generate
```

**Update your `.env` file with appropriate values for your database, cache, etc,**

```bash
# run the migrations to setup the database
php artisan migrate
```

### Running the application

You can use the development server the ships with Laravel by running, from the project root:

```bash
php artisan serve
```
You can visit [http://localhot:8000](http://localhot:8000) to see the application in action.

## Running the tests

There are tests for some Controllers available in the `tests/Feature/Controllers` directory.

```bash
# to run all tests
php artisan test --testsuite=Feature --stop-on-failure
```

## Endpoints
| Domain | Method    | URI                                  |
|--------|-----------|--------------------------------------|
|        | GET       | api/v1/events                        |
|        | POST      | api/v1/events                        |
|        | GET       | api/v1/events/{event}/promos         |
|        | POST      | api/v1/events/{event}/promos         |
|        | GET       | api/v1/events/{event}/promos/active  |
|        | POST      | api/v1/promos/deactivate             |
|        | POST      | api/v1/promos/verify                 |


### Get events
```curl
curl -X GET http://localhost:8000/api/v1/events
```
Response
```curl
{
    "data": [
        {
            "id": 4,
            "name": "Party 2",
            "description": null,
            "date": "2021-01-20",
            "coordinate": {
                "lat": 37.384924,
                "lng": -99.974362
            },
            "created_at": "2021-01-13T15:17:05.000000Z",
            "updated_at": "2021-01-13T15:17:05.000000Z"
        },
        {
            "id": 3,
            "name": "Test Event",
            "description": null,
            "date": "2021-01-20",
            "coordinate": {
                "lat": 37.384924,
                "lng": -99.974362
            },
            "created_at": "2021-01-13T10:22:54.000000Z",
            "updated_at": "2021-01-13T10:22:54.000000Z"
        },
        {
            "id": 2,
            "name": "Party 2",
            "description": null,
            "date": "2021-01-20",
            "coordinate": {
                "lat": 37.384924,
                "lng": -99.974362
            },
            "created_at": "2021-01-13T09:09:54.000000Z",
            "updated_at": "2021-01-13T09:09:54.000000Z"
        },
        {
            "id": 1,
            "name": "Party",
            "description": null,
            "date": "2021-01-20",
            "coordinate": {
                "lat": 37.384924,
                "lng": -99.974362
            },
            "created_at": "2021-01-13T09:09:42.000000Z",
            "updated_at": "2021-01-13T09:09:42.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/v1/events?page=1",
        "last": "http://127.0.0.1:8000/api/v1/events?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/v1/events?page=1",
                "label": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/v1/events",
        "per_page": 15,
        "to": 4,
        "total": 4
    }
}
```


### Create an event
```curl
curl -X POST \
	http://localhost:8000/api/v1/events \
	-H 'Content-Type: application/json' \
	-d '{
	    "name": "Test Event",
	    "date": "2021-01-20",
	    "coordinate": {
	        "lat": 37.384924,
	        "lng": -99.974362
	    }
	}'
```

Response
```curl
{
    "data": {
        "id": 1,
        "name": "Party",
        "description": null,
        "date": "2021-01-20",
        "coordinate": {
            "lat": 37.384924,
            "lng": -99.974362
        },
        "created_at": "2021-01-13T15:17:05.000000Z",
        "updated_at": "2021-01-13T15:17:05.000000Z"
    }
}
```


### Get promos for an event
```curl
curl -X GET http://localhost:8000/api/v1/events/{event}/promos
```

Response
```curl
{
    "data": [
        {
            "id": 1,
            "code": "NO-6xT-hJ59-xuR40-uC",
            "amount": "500",
            "active": true,
            "radius": "20.0",
            "event": {
                "id": 1,
                "name": "Party",
                "description": null,
                "date": "2021-01-20",
                "coordinate": {},
                "created_at": "2021-01-13T09:09:42.000000Z",
                "updated_at": "2021-01-13T09:09:42.000000Z"
            },
            "created_at": "2021-01-13T14:40:01.000000Z",
            "updated_at": "2021-01-13T14:40:01.000000Z"
        },
        {
            "id": 2,
            "code": "5g-9ls-geCI-0sjuV-8C",
            "amount": "500",
            "active": true,
            "radius": "20.0",
            "event": {
                "id": 1,
                "name": "Party",
                "description": null,
                "date": "2021-01-20",
                "coordinate": {},
                "created_at": "2021-01-13T09:09:42.000000Z",
                "updated_at": "2021-01-13T09:09:42.000000Z"
            },
            "created_at": "2021-01-13T15:09:06.000000Z",
            "updated_at": "2021-01-13T15:09:06.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/v1/events/1/promos?page=1",
        "last": "http://127.0.0.1:8000/api/v1/events/1/promos?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/v1/events/1/promos?page=1",
                "label": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/v1/events/1/promos",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```


### Create promo for an event
```curl
curl -X POST \
	http://localhost:8000/api/v1/events/{event}/promos \
	-H 'Content-Type: application/json' \
	-d '{
	    "amount": 500,
    	"radius": 20
	}'
```

Response
```curl
{
    "data": {
        "id": 1,
        "code": "5g-9ls-geCI-0sjuV-8C",
        "amount": 500,
        "active": null,
        "radius": 20,
        "event": {
            "id": 1,
            "name": "Party",
            "description": null,
            "date": "2021-01-20",
            "coordinate": {},
            "created_at": "2021-01-13T09:09:42.000000Z",
            "updated_at": "2021-01-13T09:09:42.000000Z"
        },
        "created_at": "2021-01-13T15:09:06.000000Z",
        "updated_at": "2021-01-13T15:09:06.000000Z"
    }
}
```


### Deactivate promo
```curl
curl -X POST \
	http://localhost:8000/api/v1/promos/deactivate \
	-H 'Content-Type: application/json' \
	-d '{
	    "code": "NO-6xT-hJ59-xuR40-uC"
	}'
```

Response
```curl

```

### Get active promos
```curl
curl -X GET http://localhost:8000/api/v1/events/{event}/promos/active
```

Response
```curl
{
    "data": [
        {
            "id": 1,
            "code": "NO-6xT-hJ59-xuR40-uC",
            "amount": "500",
            "active": true,
            "radius": "20.0",
            "event": {
                "id": 1,
                "name": "Party",
                "description": null,
                "date": "2021-01-20",
                "coordinate": {},
                "created_at": "2021-01-13T09:09:42.000000Z",
                "updated_at": "2021-01-13T09:09:42.000000Z"
            },
            "created_at": "2021-01-13T14:40:01.000000Z",
            "updated_at": "2021-01-13T14:40:01.000000Z"
        },
        {
            "id": 2,
            "code": "5g-9ls-geCI-0sjuV-8C",
            "amount": "500",
            "active": true,
            "radius": "20.0",
            "event": {
                "id": 1,
                "name": "Party",
                "description": null,
                "date": "2021-01-20",
                "coordinate": {},
                "created_at": "2021-01-13T09:09:42.000000Z",
                "updated_at": "2021-01-13T09:09:42.000000Z"
            },
            "created_at": "2021-01-13T15:09:06.000000Z",
            "updated_at": "2021-01-13T15:09:06.000000Z"
        }
    ],
    "links": {
        "first": "http://127.0.0.1:8000/api/v1/events/1/promos/active?page=1",
        "last": "http://127.0.0.1:8000/api/v1/events/1/promos/active?page=1",
        "prev": null,
        "next": null
    },
    "meta": {
        "current_page": 1,
        "from": 1,
        "last_page": 1,
        "links": [
            {
                "url": null,
                "label": "&laquo; Previous",
                "active": false
            },
            {
                "url": "http://127.0.0.1:8000/api/v1/events/1/promos/active?page=1",
                "label": 1,
                "active": true
            },
            {
                "url": null,
                "label": "Next &raquo;",
                "active": false
            }
        ],
        "path": "http://127.0.0.1:8000/api/v1/events/1/promos/active",
        "per_page": 15,
        "to": 2,
        "total": 2
    }
}
```


### Verify promo
```curl
curl -X POST \
	http://localhost:8000/api/v1/promos/verify \
	-H 'Content-Type: application/json' \
	-d '{
	    "code":"NO-6xT-hJ59-xuR40-uC",
	    "origin":{
	        "lat": 37.384924,
	        "lng": -99.974362
	    },
	    "destination":{
	        "lat": 37.384924,
	        "lng": -99.974362
	    }
	}'
```

Response
```curl
{
    "data": {
        "promo": {
            "id": 1,
            "code": "NO-6xT-hJ59-xuR40-uC",
            "amount": "500",
            "active": true,
            "radius": "20.0",
            "event": {
                "id": 1,
                "name": "Party",
                "description": null,
                "date": "2021-01-20",
                "coordinate": {},
                "created_at": "2021-01-13T09:09:42.000000Z",
                "updated_at": "2021-01-13T09:09:42.000000Z"
            },
            "created_at": "2021-01-13T14:40:01.000000Z",
            "updated_at": "2021-01-13T14:40:01.000000Z"
        },
        "polyline": {
            "type": "LineString",
            "coordinates": [
                [
                    -99.974362,
                    37.384924
                ],
                [
                    -99.974362,
                    37.384924
                ]
            ]
        }
    }
}
```
