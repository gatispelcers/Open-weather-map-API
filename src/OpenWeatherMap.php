<?php
namespace Pelcers\OpenWeatherMap;

use Pelcers\OpenWeatherMap\City;
use Pelcers\OpenWeatherMap\Exceptions\ApiErrorException;
use Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface;

/**
* OpenWeatherMap
*/
class OpenWeatherMap
{
    /**
     * @var Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface
     */
    private $caller;

    /**
     * @var string - OpenWeatherMap API key
     */
    private $apiKey;

    /**
     * @var string - API base URL
     */
    private $apiBaseUrl = 'http://api.openweathermap.org/data/2.5/';

    /**
     * @var mixed - Raw Response retrieved from API
     */
    private $rawResponse = null;
        
        
    /**
     * Constructor
     *
     * @param Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface $caller - API call executer
     * @param string $apiKey - API key issued by OpenWeatherMap
     * @param string $units - imperial, metric, standard
     * @param string $language - ISO code
     * @param $string $accuracy - values (accurate;like)
     */
    public function __construct(
        ApiCallerInterface $caller,
        $apiKey,
        $units = 'metric',
        $language = 'en',
        $accuracy = 'accurate')
    {
        $this->caller = $caller;
        $this->apiKey = $apiKey;
        $this->units = $units;
        $this->language = $language;
        $this->accuracy = $accuracy;
    }

    /**
     * Set units
     * @param string $units - imperial, metric, standard
     * @return $this
     */
    public function setUnits($units)
    {
        $this->units = $units;
        return $this;
    }

    /**
     * Set language
     * @param string $language - ISO code
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;
        return $this;
    }

    /**
     * Set accuracy
     * @param string $accuracy - values (accurate;like)
     * @return $this
     */
    public function setAccuracy($accuracy)
    {
        $this->accuracy = $accuracy;
        return $this;
    }

    /**
     * Retrieve current weather by city name
     * @param string $cityName
     * @return array
     */
    public function getCurrentWeatherByCityName($cityName)
    {
        // Prepare query parameters
        $parameters = [
            'q'        => $cityName,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('weather', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve current weather by city id
     * @param int $cityId - see http://openweathermap.org/api
     * @return array
     */
    public function getCurrentWeatherByCityId($cityId)
    {
        // Prepare query parameters
        $parameters = [
            'id'       => $cityId,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('weather', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve current weather by city coordinates
     * @param float $latitude - see http://openweathermap.org/api
     * @param float $longitude - see http://openweathermap.org/api
     * @return array
     */
    public function getCurrentWeatherByCityCoordinates($latitude, $longitude)
    {
        // Prepare query parameters
        $parameters = [
            'lat'      => $latitude,
            'lon'      => $longitude,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('weather', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve current weather by city zip
     * @param int $zip - see http://openweathermap.org/api
     * @return array
     */
    public function getCurrentWeatherByCityZip($zip)
    {
        // Prepare query parameters
        $parameters = [
            'zip'      => $zip,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('weather', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly weather forecast by city name
     * For more information http://www.openweathermap.org/forecast5
     * @param string $cityName
     * @return array
     */
    public function getHourlyForecastByCityName($cityName)
    {
        // Prepare query parameters
        $parameters = [
            'q'        => $cityName,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly weather forecast by city id
     * For more information http://www.openweathermap.org/forecast5
     * @param int $cityId
     * @return array
     */
    public function getHourlyForecastByCityId($cityId)
    {
        // Prepare query parameters
        $parameters = [
            'id'       => $cityId,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly weather forecast by city coordinates
     * For more information http://www.openweathermap.org/forecast5
     * @param float $latitude
     * @param float $longitude
     * @return array
     */
    public function getHourlyForecastByCityCoordinates($latitude, $longitude)
    {
        // Prepare query parameters
        $parameters = [
            'lat'      => $latitude,
            'lon'      => $longitude,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve daily weather forecast by city name
     * For more information http://www.openweathermap.org/forecast16
     * @param string $cityName
     * @param int $cnt - day count. Max is 16
     * @return array
     */
    public function getDailyForecastByCityName($cityName, $cnt = 16)
    {
        // Prepare query parameters
        $parameters = [
            'q'        => $cityName,
            'cnt'      => $cnt,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast/daily', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve daily weather forecast by city id
     * For more information see http://www.openweathermap.org/forecast16
     * @param int $cityId
     * @param int $cnt - day count. Max is 16
     * @return array
     */
    public function getDailyForecastByCityId($cityId, $cnt = 16)
    {
        // Prepare query parameters
        $parameters = [
            'id'       => $cityId,
            'cnt'      => $cnt,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast/daily', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve daily weather forecast by city coordinates
     * For more information see http://www.openweathermap.org/forecast16
     * @param float $latitude
     * @param float $longitude
     * @param int $cnt - day count. Max is 16
     * @return array
     */
    public function getDailyForecastByCityCoordinates($latitude, $longitude, $cnt = 16)
    {
        // Prepare query parameters
        $parameters = [
            'lat'      => $latitude,
            'lon'      => $longitude,
            'cnt'      => $cnt,
            'language' => $this->language,
            'type'     => $this->accuracy,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('forecast/daily', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly historical weather data by city name
     * For more information see http://www.openweathermap.org/history
     * @param string $cityName
     * @param int $start start date (unix time, UTC time zone)
     * @param int $end end date (unix time, UTC time zone)
     * @return array
     */
    public function getHourlyHistoricalDataByCityName($cityName, $start, $end)
    {
        // Prepare query parameters
        $parameters = [
            'q'        => $cityName,
            'language' => $this->language,
            'start'    => $start,
            'end'      => $end,
            'units'    => $this->units,
            'type'     => 'hour', // according to documentation should always be hour
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('history/city', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly historical weather data by city ID
     * For more information see http://www.openweathermap.org/history
     * @param int $cityId
     * @param int $start start date (unix time, UTC time zone)
     * @param int $end end date (unix time, UTC time zone)
     * @return array
     */
    public function getHourlyHistoricalDataByCityId($cityId, $start, $end)
    {
        // Prepare query parameters
        $parameters = [
            'id'       => $cityId,
            'language' => $this->language,
            'start'    => $start,
            'end'      => $end,
            'units'    => $this->units,
            'type'     => 'hour', // according to documentation should always be hour
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('history/city', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve hourly historical weather data by city Coordinates
     * For more information see http://www.openweathermap.org/history
     * @param float $latitude
     * @param float $longitude
     * @param int $start start date (unix time, UTC time zone)
     * @param int $end end date (unix time, UTC time zone)
     * @return array
     */
    public function getHourlyHistoricalDataByCityCoordinates($latitude, $longitude, $cityId, $start, $end)
    {
        // Prepare query parameters
        $parameters = [
            'lat'      => $latitude,
            'lon'      => $longitude,
            'language' => $this->language,
            'start'    => $start,
            'end'      => $end,
            'units'    => $this->units,
            'type'     => 'hour', // according to documentation should always be hour
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('history/city', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve current weather data from single weather station
     * For more information see http://www.openweathermap.org/api_station
     * @param int $stationId
     * @return array
     */
    public function getCurrentWeatherDataFromStation($stationId)
    {
        // Prepare query parameters
        $parameters = [
            'id'       => $stationId,
            'language' => $this->language,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('station', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Retrieve current weather data from multiple weather stations
     * For more information see http://www.openweathermap.org/api_station
     * @param string $box - coma delimited coordinate rectangle (e.g. 8.87,49.07,65.21,61.26,6)
     * @param int $cnt - amount of lines in respond
     * @param string $cluster - use server clustering of points, possible values are [yes, no]
     * @return array
     */
    public function getCurrentWeatherDataFromStations($box, $cnt, $cluster = 'yes')
    {
        // Prepare query parameters
        $parameters = [
            'box'      => $box,
            'cnt'      => $cnt,
            'cluster'  => $cluster,
            'units'    => $this->units,
            'mode'     => 'json',
        ];
        $endpoint = $this->makeApiEndpoint('box/station', $parameters);
        $response = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($response, $endpoint);

        return $this->toArray($response);
    }

    /**
     * Allows to make raw API calls with user provided query parameters
     * For more information see http://openweathermap.org/api
     * @param string $pathName - URL path name. E.g. weather; forecast; forecast/daily
     * @param array $parameters - key => value used for making API endpoint query string
     * @return string - API call response
     */
    public function getRawResponse($pathName, $parameters)
    {
        $endpoint = $this->makeApiEndpoint($pathName, $parameters);
        $this->rawResponse = $this->makeApiCall($endpoint);
        // Validate API call response
        $this->validateApiResponse($this->rawResponse, $endpoint);
        return $this->rawResponse;
    }

    /**
     * Makes API call using provided API caller instance
     * @param string $endpoint
     * @return false|string
     */
    private function makeApiCall($endpoint)
    {
        $this->rawResponse = $this->caller->retrieve($endpoint);
        return $this->rawResponse;
    }

    /**
     * Generates full API endpoint by using given query parameters
     * @param string $pathName - URL path name
     * @param array $parameters - query parameters
     * @return string
     */
    private function makeApiEndpoint($pathName, array $parameters)
    {
        $query = $pathName . '?';
        // Add all query parameters
        foreach ($parameters as $parameter => $value) {
            $query .= $parameter . '=' . $value . '&';
        }
        // Add API key
        return $this->apiBaseUrl . $query . "appid={$this->apiKey}";
    }

    /**
     * Validates API response. Throws exception if no response or response with errors
     * @param string $response
     * @param string $endpoint - endpoint that returned response
     * @throws Pelcers\OpenWeatherMap\Exceptions\ApiErrorException
     * @return void
     */
    private function validateApiResponse($response, $endpoint)
    {
        if (false === $response) {
            throw new ApiErrorException("No response from \"$endpoint\"", 404);
        }
        // OpenWeather API always returns error messages in JSON format
        $json = json_decode($response);
        if (false !== $json && 200 != $json->cod) {
            throw new ApiErrorException("Error from \"$endpoint\" with message: \"{$json->message}\"", $json->cod);
        }
    }

    /**
     * Transform API call response to Array
     * @param string $response
     * @return array
     */
    private function toArray($response)
    {
        return json_decode($response, true);
    }
}
