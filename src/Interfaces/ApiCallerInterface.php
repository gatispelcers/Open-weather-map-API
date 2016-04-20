<?php
namespace Pelcers\OpenWeatherMap\Interfaces;

interface ApiCallerInterface
{
    /**
     * Retrieve data from API
     * @param string $path - API endpoint
     * @return string
     */
    public function retrieve( $endpoint );
}
