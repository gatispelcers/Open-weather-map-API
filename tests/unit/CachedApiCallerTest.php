<?php

use Pelcers\OpenWeatherMap\ApiCaller;
use Pelcers\OpenWeatherMap\CachedApiCaller;

/**
 * Tests only public interface. Private methods are tested indirectly
 */
class CachedApiCallerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set Up
     */
    public function setUp()
    {
        $this->cacheService = $this
            ->getMockBuilder('Pelcers\OpenWeatherMap\CacheServices\FileCache')
            ->getMock();
        $this->apiCaller = $this
            ->getMockBuilder('Pelcers\OpenWeatherMap\ApiCaller')
            ->getMock();
    }

    /**
     * Test conditional cache skipping works
     */
    public function testCacheIsSkipped()
    {
        // Cache service get method should not be called
        $this->cacheService
            ->expects($this->exactly(0))
            ->method('get');
        $cachedApiCaller = new CachedApiCaller($this->apiCaller, $this->cacheService);
        $result = $cachedApiCaller->skipCache()->retrieve('some-endpoint');
    }

    /**
     * Test cache is searched with correct key and expiration time
     */
    public function testCacheIsSearchedWIthCorrectParameters()
    {
        $expiration = 10; // Some cache expiration timeout in seconds
        $this->cacheService
            ->expects($this->once())
            ->method('get')
            ->with(
                md5('some-endpoint'),
                $expiration
            );
        $this->apiCaller
            ->expects($this->once())
            ->method('retrieve')
            ->will($this->returnValue('response'));

        $cachedApiCaller = new CachedApiCaller($this->apiCaller, $this->cacheService, $expiration);
        $result = $cachedApiCaller->retrieve('some-endpoint');
    }

    /**
     * Test API response results are cached if response is valid and cachable
     */
    public function testValidResponseIsCached()
    {
        $expiration = 10; // Some cache expiration timeout in seconds
        $this->cacheService
            ->expects($this->once())
            ->method('get')
            ->with(
                md5('some-endpoint'),
                $expiration
            )
            ->will($this->returnValue(null));

        $this->apiCaller
            ->expects($this->once())
            ->method('retrieve')
            ->will($this->returnValue('{"json":"valid", "cod":200}'));

        $this->cacheService
            ->expects($this->once())
            ->method('set')
            ->with(
                md5('some-endpoint'),
                '{"json":"valid", "cod":200}', // Some valid API response
                $expiration
            );

        $cachedApiCaller = new CachedApiCaller($this->apiCaller, $this->cacheService, $expiration);
        $result = $cachedApiCaller->retrieve('some-endpoint');
    }
}
