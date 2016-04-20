<?php

use Pelcers\OpenWeatherMap\ApiCaller;

class ApiCallerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Set Up
     */
    public function setUp()
    {
        $this->apiCaller = new ApiCaller();
    }

    /**
     * Test that API caller returns false if incorrect API endpoint is requested
     */
    public function testReturnsFalseIfNotExistingEndpointRequested()
    {
        $result = $this->apiCaller->retrieve('some-incorrect-endpoint');
        $this->assertFalse($result);
    }

    /**
     * Test that API caller returns string response if correct API endpoint is requested
     */
    public function testReturnsStringIfExistingEndpointRequested()
    {
        $result = $this->apiCaller->retrieve('http://api.openweathermap.org/data/2.5');
        $this->assertTrue(is_string($result));
    }
}
