<?php

namespace App\Helpers\Geolocation\Services;

use App\Helpers\Geolocation\GService;

class IpApiGService extends GService implements GServiceInterface
{
    public function __construct()
    {
        $this->setApi('query', 'countryCode', 'country');
        $this->setUrl('http://ip-api.com/json');
    }
}