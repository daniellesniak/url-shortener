<?php

namespace App\Helpers;

class Geolocation
{
    private $_services = [
        'free_geo_ip' => 'http://freegeoip.net/json/',
        'ip-api' => 'http://ip-api.com/json'
    ];

    private $_services_json_joint = [
        'free_geo_ip' => [
            'ip' => 'ip',
            'country_code' => 'country_code',
            'country_name' => 'country_name'
        ],
        'ip-api' => [
            'ip' => 'query',
            'country_code' => 'countryCode',
            'country_name' => 'country'
        ]
    ];

    private $service;

    public $ip;
    public $country_name;
    public $country_code;

    public function  __construct($service_name = 'free_geo_ip')
    {
        try
        {
            $this->_setService($service_name);
        } catch (\Exception $e)
        {
            $this->service = 'free_geo_ip';
        }

        $decodedJson = json_decode($this->_getJsonResponse(), true);

        $this->ip = $decodedJson[$this->_services_json_joint[$this->service]['ip']];
        $this->country_name = $decodedJson[$this->_services_json_joint[$this->service]['country_name']];
        $this->country_code = $decodedJson[$this->_services_json_joint[$this->service]['country_code']];
    }

    /**
     * @return mixed
     */
    public function getService()
    {
        return $this->service;
    }

    /**
     * @param mixed $service_name
     * @throws \Exception
     */
    private function _setService($service_name)
    {
        if(array_key_exists($service_name, $this->_services))
            $this->service = $service_name;
        else
            throw new \Exception('Service not found!');
    }

    private function _getJsonResponse()
    {
        $json_url = $this->_services[$this->getService()];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $json_url);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}