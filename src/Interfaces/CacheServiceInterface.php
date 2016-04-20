<?php
namespace Pelcers\OpenWeatherMap\Interfaces;

interface CacheServiceInterface
{
    /**
     * Retrieve data from cache by key
     * @param string $key - cache key
     * @param int $expiration
     * @return bool|string
     */
    public function get($key, $expiration = 0);

    /**
     * Store data in to cache for $expiration seconds
     * @param string $key - cache key
     * @param string $data - data to store in cache
     * @param int $expiration - time for data to be valid in cache
     * @return bool
     */
    public function set($key, $data, $expiration);
}
