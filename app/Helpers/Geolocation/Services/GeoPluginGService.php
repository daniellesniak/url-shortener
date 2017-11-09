<?php

namespace App\Helpers\Geolocation\Services;

use App\Helpers\Geolocation\GService;

class GeoPluginGService extends GService implements GServiceInterface {

    public function __construct()
    {
        $this->setApi('geoplugin_request', 'geoplugin_countryCode', 'geoplugin_countryName');
        $this->setUrl('http://www.geoplugin.net/json.gp');
    }
}