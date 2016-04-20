<?php
namespace Pelcers\OpenWeatherMap\CacheServices;

use Pelcers\OpenWeatherMap\Interfaces\CacheServiceInterface;

class FileCache implements CacheServiceInterface
{
    /**
     * @var string - path to directory where cache files are stored
     */
    private $cachePath;

    /**
     * @var strin - extension of cache file
     */
    private $cacheExtension;
        
        
    /**
     * @param string $cachePath - path to directory where cache files are stored
     * @param string $cacheExtension - extension of cache file
     */
    public function __construct($cachePath = '/tmp', $cacheExtension = '')
    {
        $this->cachePath = $cachePath;
        $this->cacheExtension = $cacheExtension;
    }

    /**
     * Retrieve data from cache by key
     * @param string $key - cache key
     * @param int $expiration
     * @return null|string
     */
    public function get($key, $expiration = 0)
    {
        $path = $this->createFullCachePath($key);
        if (is_readable($path) && filemtime($path) >= (time() - $expiration)) {
            return file_get_contents($path);
        }
        return null;
    }

    /**
     * Store data in cache for $expiration seconds
     * @param string $key - cache key
     * @param string $data - data to store in cache
     * @param int $expiration - time for data to be valid in cache
     * @return bool
     */
    public function set($key, $data, $expiration)
    {
        $path = $this->createFullCachePath($key);
        if (false === file_put_contents($path, $data)) {
            throw new \Exception("Failed writing to cache file path: \"$path\"", 1);
        }
        return true;
    }

    /**
     * Helper for creating full cache path
     * @param string $key - cache key
     * @return string
     */
    private function createFullCachePath($key)
    {
        if ('/' !== substr($this->cachePath, -1)) {
            $this->cachePath .= '/';
        }
        return $this->cachePath . $key . $this->cacheExtension;
    }
}
