<?php
namespace Pelcers\OpenWeatherMap;

use Pelcers\OpenWeatherMap\Interfaces\ApiCallerInterface;

/**
* ApiCaller
*/
class ApiCaller implements ApiCallerInterface
{
    /**
     * @var array - options for curl
     */
    private $options;

    /**
     * ApiCaller constructor
     *
     * @param array $options
     */
    public function __construct($options = array())
    {
        $this->options = $options;
    }

    /**
     * Retrieve data from API
     * @param string $path - API endpoint
     * @return false|string
     */
    public function retrieve($endpoint)
    {
        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_URL, $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        curl_setopt_array($ch, $this->options);
        
        $results = curl_exec($ch);
        curl_close($ch);
        
        return $results;
    }
}
