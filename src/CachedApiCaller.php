<?php
namespace Pelcers\OpenWeatherMap;

use Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface;
use Pelcers\OpenWeatherMap\Interfaces\CacheServiceInterface;

/**
* CachedApiCaller
*/
class CachedApiCaller implements ApiCallerInterface
{
    /**
     * @var Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface
     */
    private $caller;

    /**
     * @var Pelcers\OpenWeatherMap\Interfaces\CacheServiceInterface
     */
    private $cacheService;

    /**
     * @var bool
     */
    private $skipCache;

    /**
     * @var int - cache expiration time
     */
    private $expiration;
    
    /**
     * @param Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface $caller - API caller that gets decorated
     * @param Pelcers\OpenWeatherMap\Interfaces\CacheServiceInterface $cacheSerice
     * @param int $expiration cache expiration time
     */
    public function __construct(ApiCallerInterface $caller, CacheServiceInterface $cacheService, $expiration = 600)
    {
        $this->caller = $caller;
        $this->cacheService = $cacheService;
        $this->skipCache = false;
        $this->expiration = $expiration; // Default cache expiration time
    }

    /**
     * Caches data retrieved from API
     * @param string $path - API endpoint
     * @return string
     */
    public function retrieve($endpoint)
    {
        // Cache skipping is on. We don't even check cache for data
        if (true === $this->skipCache) {
            $response = $this->caller->retrieve($endpoint);
        } else {
            $cacheKey = md5($endpoint);
            // Retrieve data from cache
            $response = $this->cacheService->get($cacheKey, $this->expiration);
            if (null === $response) {
                // No data in cache
                $response = $this->caller->retrieve($endpoint);
                if ($this->isCachable($response)) {
                    $this->cacheService->set($cacheKey, $response, $this->expiration);
                }
            }
        }
        // Always reset cache skipping
        $this->skipCache(false);
        return $response;
    }

    /**
     * Determines if data retrieved from API are cachable.
     * We never cache error responses
     * @param string $results - data retrieved from API
     * @return bool
     */
    private function isCachable($results)
    {
        // No response from API endpoint
        if (false === $results) {
            return false;
        }
        /* API endpoint responded with error if response does not contain code 200
         * API endpoint always responds with JSON if error occurs
         */
        $json = json_decode($results);
        if (false !== $json 
            && null !== $json 
            && 200 != $json->cod) {
            return false;
        }
        return true;
    }

    /**
     * Lets temporary skip caching of retrieved results
     * @param bool $status
     * @return this
     */
    public function skipCache($status = true)
    {
        $this->skipCache = $status;
        return $this;
    }

    /**
     * Custom cache expiration timeout
     * @param int $expiration - seconds before cache expires
     * @return this
     */
    public function setCacheExpiration($expiration = 600)
    {
        $this->expiration = $expiration;
        return $this;
    }
}
