<?php

namespace App;

use App\Helpers\Geolocation\Services\FreeGeoIpGService;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    protected $fillable = ['slug', 'url', 'is_private'];
    protected $redirects_count;

    /**
     * Get statistics of given Url.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function stats()
    {
    	return $this->hasMany('\App\UrlStat', 'url_id');
    }

    public function shortenUrl()
    {
        return url('/') . '/' . $this->slug;
    }

    public function getRedirectsCount()
    {
        $this->redirects_count = $this->stats()->count();

        return $this->redirects_count;
    }

    public function createStat()
    {
        if(isset($_SERVER['HTTP_USER_AGENT']))
            $parsed_user_agent = parse_user_agent();
        else
            $parsed_user_agent = ['platform' => null, 'browser' => null];

        $freeGeoIpService = new FreeGeoIpGService();
        $geolocationData = $freeGeoIpService->execute()->getData();

        $statsData = [
            'url_id' => $this->id,
            'http_referer' => (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null
        ];

        $statsData = array_merge($statsData, $parsed_user_agent, $geolocationData);

        $this->stats()->create($statsData);

        return $this;
    }

    public function getCountryCode($name)
    {
        $stat = $this->stats()->where('country_name', $name)->first();

        if($stat)
            return $stat->country_code;

        return '';
    }
}
