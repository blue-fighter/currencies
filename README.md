## Project set up

* Pull project from repository
* Port `8084` must be free, or change port for `currencies-webserver` in `docker-compose.yml` to any free
* Port `5433` must be free, or change port for `currencies-postgres` in `docker-compose.yml` to any free
* Execute `docker-compose up -d` from project root

## API method
`GET http://127.0.0.1:8084/exchange_rate`

### Query params
* `date` in format `dd-mm-yyyy`
* `targetCurrencyCode` ISO 4217 Currency Code
* `sourceCurrencyCode` ISO 4217 Currency Code

### Console command
To collect data for 180 days run `docker exec -it currencies-app ./bin/console app:collect-data`

or `docker exec  currencies-app ./bin/console app:collect-data --no-interaction`