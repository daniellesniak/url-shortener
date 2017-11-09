<?php

namespace App\Helpers\Geolocation;

abstract class GService {

    /**
     * Geolocation service url.
     *
     * @string $url
     */
    protected $url;

    /**
     * Array with JSON's names for ip, country code and country name.
     *
     * @array $api
     */
    protected $api;

    /**
     * Array with data [ip, country code and country name].
     *
     * @array $api
     */
    protected $data;

    /**
     * @param string $ip
     * @param string $country_code
     * @param string $country_name
     * @return $this
     */
    protected function setApi($ip = 'ip', $country_code = 'country_code', $country_name = 'country_name')
    {
        $this->api = ['ip' => $ip, 'country_code' => $country_code, 'country_name' => $country_name];

        return $this;
    }

    public function execute()
    {
        $this->setData();

        return $this;
    }

    public function getIp()
    {
        return $this->data['ip'];
    }

    public function getCountryName()
    {
        return $this->data['country_name'];
    }

    public function getCountryCode()
    {
        return $this->data['country_code'];
    }

    public function getData()
    {
        return $this->data;
    }

    protected function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    private function setData()
    {
        $json = $this->getResponse();
        $data = json_decode($json, true);
        $api = $this->api;

        $this->data = ['ip' => $data[$api['ip']], 'country_code' => $data[$api['country_code']], 'country_name' => $data[$api['country_name']]];

        return $this;
    }

    private function getResponse()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $this->url);

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }
}