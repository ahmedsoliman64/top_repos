# Get top github Repos
## Setup 
`docker build \
  --file .docker/Dockerfile \
  -t assessment-docker .`
`docker run -d --name my-assessment -p 8080:80 assessment-docker`


## Run Unit Test
`docker exec -it my-assessment /bin/bash`

inside the web directory /var/www/html run the following command
`vendor/bin/phpunit --testdox tests`

## Using
Open the web browser and try the following URL
`http://localhost:8080/?language=php&created_from=2021-01-01&limit=1`

## Parameters
- page : to get the selected page, default value 1
- limit : set the page limit 
- language : to filter by programming language 
- created_from : to filter the repos by creation date