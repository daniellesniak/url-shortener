<?php

namespace App\Helpers\Geolocation\Services;

use App\Helpers\Geolocation\GService;

class FreeGeoIpGService extends GService implements GServiceInterface
{
    public function __construct()
    {
        $this->setApi();
        $this->setUrl('http://freegeoip.net/json/');

        return $this;
    }
}