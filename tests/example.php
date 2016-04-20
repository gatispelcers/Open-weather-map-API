<?php

use Pelcers\OpenWeatherMap\ApiCaller;
use Pelcers\OpenWeatherMap\CacheServices\FileCache;
use Pelcers\OpenWeatherMap\CacheServices\MemcachedCache;
use Pelcers\OpenWeatherMap\CachedApiCaller;
use Pelcers\OpenWeatherMap\OpenWeatherMap;

require_once '../vendor/autoload.php';
$apiKey = null;
if (is_null($apiKey)) {
    throw new \Exception("Please, provide your API key to see examples", 1);
    
}
$openWeatherMap = new OpenWeatherMap(new CachedApiCaller(new ApiCaller(), new FileCache()), $apiKey);
# $openWeatherMap = new OpenWeatherMap(new ApiCaller(), 'your-api-key');

var_dump($openWeatherMap->getCurrentWeatherByCityName('Riga'));
var_dump($openWeatherMap->getCurrentWeatherByCityId(2172797));
var_dump($openWeatherMap->getCurrentWeatherByCityCoordinates(53, 139));
var_dump($openWeatherMap->getCurrentWeatherByCityZip(12201));
var_dump($openWeatherMap->getHourlyForecastByCityName('Riga'));
var_dump($openWeatherMap->getHourlyForecastByCityId(2172797));
var_dump($openWeatherMap->getHourlyForecastByCityCoordinates(53, 139));
var_dump($openWeatherMap->getDailyForecastByCityName('Riga', 12));
var_dump($openWeatherMap->getDailyForecastByCityId(2172797));
var_dump($openWeatherMap->getDailyForecastByCityCoordinates(53, 139));
var_dump($openWeatherMap->getHourlyHistoricalDataByCityName('Riga'));
var_dump($openWeatherMap->getHourlyHistoricalDataByCityId(2172797));
var_dump($openWeatherMap->getHourlyHistoricalDataByCityCoordinates(53, 139));
var_dump($openWeatherMap->getCurrentWeatherDataFromStation(29584));
var_dump($openWeatherMap->getCurrentWeatherDataFromStations('8.87,49.07,65.21,61.26,6', 20));
