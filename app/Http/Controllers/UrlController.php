<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Url;

use App\UrlStat;

use Carbon\Carbon;


class UrlController extends Controller
{
	/**
    * @param Request $request
    * @return Response
    */
    public function store(Request $request)
    {
        $url = $request->input('url');

    	$this->validate($request, [
    		'url' => 'required|url'
    	]);

    	$stringId = str_random(6);

    	// check if stringId is unique, if not it generates new id
    	while(true)
    	{
    		if(Url::where('string_id', $stringId)->first() != null) {
    			$stringId = bin2hex(random_bytes(3));
    		}
    		else
    			break;
    	}

    	$urlMdl = new Url;
    	
    	$urlMdl->url = $url;
    	$urlMdl->string_id = $stringId;
    	$urlMdl->save();

    	session()->push('urlIDs', $urlMdl->string_id);
    	
    	$completeUrl = url('/').'/'.$stringId;

    	return view('pages.shorten', ['shortenUrl' => $completeUrl]);
    }

    
    /*
    / Using phpuseragentparser to parse user agent
    / https://github.com/donatj/PhpUserAgent
    */

    /**
    * @param $id
    * @return Response
    */
    public function redirect($id) 
    {
    	$ua = parse_user_agent(); // $ua = Parsed User Agent
    	$platform = $ua['platform'];
    	$browser = $ua['browser'];

    	/* 
    	/ Get geolocation by ip with freegeoip.net api
    	/ 10,000 requests/hour
    	*/
        $geoloactionJson = getGeolocationJson();

		$decodedJson = json_decode($geoloactionJson);
		
		$ip = $decodedJson->ip;
		$country_code = $decodedJson->country_code;
		$country_name = $decodedJson->country_name;
		
		$http_referer = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null;
		if(isset($_SERVER['HTTP_REFERER']))
  	  		$http_referer = $_SERVER['HTTP_REFERER'];

		$url = Url::where('string_id', '=', $id)->first();
		
		$urlStat = $url->stats()->create([
			'platform' => $platform,
			'browser' => $browser,
			'ip' => $ip,
			'country_name' => $country_name,
			'country_code' => $country_code,
			'http_referer' => $http_referer
		]);

		return redirect($url->url, 301);
    }

    /**
    * @param $id
    * @return Response
    */
    public function statsv2(Request $request, $id)
    {
    	$shortenUrl = action("UrlController@redirect", $id);
    	
    	$url = Url::where('string_id', $id)->first();
    	
        // set variables to default
        $redirects_count = $url->stats()->count();
        $allUrls = $url->stats()->get();

        // handle range
        if($request->input('range') != null)
        {
            $range = $request->input('range');
            
            switch($range)
            {
                case '24h':
                    $allUrls = $url->stats()
                        ->whereBetween('created_at', [Carbon::now()->subDay(), Carbon::now()])
                        ->get();
                    break;
                case '48h':
                    $allUrls = $url->stats()
                        ->whereBetween('created_at', [Carbon::now()->subDays(2), Carbon::now()])
                        ->get();
                    break;
                case 'week':
                    $allUrls = $url->stats()
                        ->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()])
                        ->get();
                    break;
                case 'month':
                    $allUrls = $url->stats()
                        ->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()])
                        ->get();
                    break;
                default:
                    break;
            }
            
            $redirects_count = $allUrls->count();
        }

        // handle custom range
        if(($request->input('from') != null) && ($request->input('to') != null))
        {
            $allUrls = $url->stats()
                ->whereBetween('created_at', [$request->input('from'), $request->input('to')])
                ->get();
            $redirects_count = $allUrls->count();
        }

        $platformArr = [];
    	$browserArr = [];
    	$countryArr = [];
        $httpRefererArr = [];

    	foreach($allUrls as $singleUrl)
    	{	
    		array_push($platformArr, $singleUrl->platform);
    		array_push($browserArr, $singleUrl->browser);
    		array_push($countryArr, $singleUrl->country_name);
            if($singleUrl->http_referer == null)
                array_push($httpRefererArr, '(directly)');
            else
                array_push($httpRefererArr, $singleUrl->http_referer);
    	}

    	$platformArr = array_count_values($platformArr);
    	$browserArr = array_count_values($browserArr);
    	$countryArr = array_count_values($countryArr);
        $httpRefererArr = array_count_values($httpRefererArr);

		// Sort and reverse arrays
		asort($platformArr); arsort($platformArr);
		asort($browserArr); arsort($browserArr);
		asort($countryArr); arsort($countryArr);
		asort($httpRefererArr); arsort($httpRefererArr);

    	return view('pages.statsv2', 
    		[
             'id' => $id,
             'shortenUrl' => $shortenUrl,
    		 'redirects' => $redirects_count,
    		 'platformStats' => $platformArr,
    		 'browserStats' => $browserArr,
    		 'countryStats' => $countryArr,
             'httpRefererStats' => $httpRefererArr,
    		 'platformPercent' => makePercentFromArray($platformArr),
    		 'browserPercent' => makePercentFromArray($browserArr),
    		 'countryPercent' => makePercentFromArray($countryArr),
             'httpRefererPercent' => makePercentFromArray($httpRefererArr)
    		]);
    }

    /**
    * @param $id
    * @return Response
    */
    public function stats($id)
    {
    	$url = Url::where('string_id', '=', $id)->first();

    	$platform_arr = array();

    	foreach($url->stats as $stat)
    	{
    		array_push($platform_arr, $stat->platform);
    	}

    	$platform_arr = array_count_values($platform_arr);

    	$platformChartLabels = array();
    	$platformChartData = array();
    	foreach($platform_arr as $key => $value)
    	{
    		array_push($platformChartLabels, $key);
    		array_push($platformChartData, $value);
    	}

    	$platformChartArray = array($platformChartLabels, $platformChartData);

    	$january = $url->stats()->where([
    		['created_at', '>', Carbon::createFromDate(2016, 9, 30)],
    		['created_at', '<=', Carbon::createFromDate(2016, 10, 31)]
    	])->get();

    	$mothChartData = [];
    	$monthChartLabels = [];
    	for($i = 0; $i < 12; $i++)
    	{
    		array_push($mothChartData, $url->stats()->where([
    			['created_at', '>', Carbon::createFromDate(null, $i+1, 01)],
    			['created_at', '<=', Carbon::createFromDate(null, $i+1, 31)]
    		])->get()->count());
    		array_push($monthChartLabels, Carbon::createFromDate(null, $i+1)->format('F'));
    	}

    	$mothChartArray = [$monthChartLabels, $mothChartData];
    	return view('pages.stats', ['chartArray' => $platformChartArray, 'monthArray' => $mothChartArray]);
    }
}
