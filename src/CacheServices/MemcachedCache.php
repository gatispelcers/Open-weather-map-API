<?php
namespace Pelcers\OpenWeatherMap\CacheServices;

use Pelcers\OpenWeatherMap\Interfaces\CacheServiceInterface;

class MemcachedCache implements CacheServiceInterface
{
    /**
     * @param string $host - hostname of memcache server
     * @param int $port - port on which memcache is running
     */
    public function __construct($host = 'localhost', $port = 11211)
    {
        $this->memcached = new \Memcached();
        $this->memcached->addServer($host, $port);
    }

    /**
     * Retrieve data from cache by key
     * @param string $key - cache key
     * @param int $expiration
     * @return null|string
     */
    public function get($key, $expiration = 0)
    {
        $result = $this->memcached->get($key);
        return $result ? $result : null;
    }

    /**
     * Store data in cache for $expiration seconds
     * @param string $key - cache key
     * @param string $data - data to store in cache
     * @param int $expiration - time for data to be valid in cache
     * @return bool
     */
    public function set($key, $data, $expiration = 0)
    {
        return $this->memcached->set($key, $data, $expiration);
    }
}
