# Minube Software Developer TEST

## Install

### Dependencies

To run this stack on your machine, you need at least:

- Operating System: Windows, Linux, or OS X
- [Docker Engine](https://docs.docker.com/install/) >= 1.10.0
- [Docker Compose](https://docs.docker.com/compose/install/) >= 1.6.2

### Services

Services included are:

|Service name|Description|
|---|---|
|mongo|MongoDB server container.|
|postgres|PostgreSQL server container.|
|mysql|MySQL database container.|
|phpmyadmin|A web interface for MySQL and MariaDB.|
|memcached|Memcached server container.|
|queue|Beanstalk queue container.|
|aerospike|Aerospike – the reliable, high performance, distributed database optimized for flash and RAM.|
|redis|Redis database container.|
|app|PHP 7, Apache 2 and Composer container.|
|elasticsearch|Elasticsearch is a powerful open source search and analytics engine that makes data easy to search.|

## Configuration

Add ``minube.local`` in your ``/etc/hosts`` file as follows:

``$ 127.0.0.1 www.minube.local minube.local``

You can check the Environment variables in ``variables.env`` file.
## Usage

You can now build, create, start, and attach to containers to the environment for the test application. To build the containers use following command inside the project root:

``docker-compose build``

Run PHP Composer

```
docker-compose run --rm \
       -w /project/application \
       app \
       composer install
```

Import database dump

``docker exec -i <mysqlimage> mysql -uphalcon -psecret phalcondb < data/mysql/minube_test.sql``
       
And finally, launch the project:

``docker-compose up -d``

Now you can now launch your application in your browser visiting ``http://minube.local`` and ``http://minube.local:8080`` for the DB schema


### Additional information

Get image IP:

``docker inspect <image> | grep IPAddress``

E.g.:

``docker inspect softwaredevelopertest_redis_1 | grep IPAddress``


## Implementing the test

You can test that the project works checking the url [http://minube.local/status](http://minube.local/status)

You have to work with a dummy DB (```minube```) that contains tables representing a list of Points of Interest (POIs) from Andorra and create the logic to: 

- Retrieve a JSON with a list of pois from a given destination. 
Take into account that you can use Redis as cache layer and the method should be able to retrieve a huge list without returning a big JSON output.
It could resolve this pattern of URL: 
    - ```http://minube.local/pois/{countryId}```.
    - ```http://minube.local/pois/{countryId}/{zoneId}```.
    - ```http://minube.local/pois/{countryId}/{zoneId}/{cityId}```.
    
- Update a single poi data. You have to implement the bootstrapping to create a new route and manage the request in order to update any attribute from the given poi.

Preferably the implementation should be written following SOLID and KISS principles, be object oriented, in clean code and testable. 
 
You can also alter the Database schema. All the changes have to be committed into ```data/mysql/minube_test.sql```.

*Hint*: To start, please pay attention to the ```routes.php``` file content. 
You can find an skeleton on ```PoisController``` and some of the instructions are reflected in the code as ```TODOs```. The performance is quite important. 
This is just and example of a small dataset but the code must perform well with a million of pois.
The result have to be modelled to be reusable and the method have to be implemented using Restful specifications. 

Additionally you can use one or more of the provided services to make the better performance solution.

## Bonus: Testing the results

Feel free to cover as much code as you can using PHPUnit.
