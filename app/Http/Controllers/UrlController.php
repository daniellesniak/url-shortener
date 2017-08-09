<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Url;
use App\UrlStat;
use Carbon\Carbon;
use App\Helpers\Geolocation;

class UrlController extends Controller
{
	/**
    * @desc Get url, validate and store it in database.
    * @param Request $request
    * @return Response
    */
    public function store(\App\Http\Requests\ShortenRequest $request)
    {
        $url = $request->input('url');
        $protocol = $request->input('protocol_select');
        $fullUrl = $protocol . $url;
        $is_private = $request->input('is_private');
        $custom_alias = $request->input('custom_alias');

        if($custom_alias == null || $custom_alias == '')
    	{
            $stringId = str_random(6); // generate random string_id
            // check if stringId is unique, if not it generates new id
            while(true)
            {
                if(Url::where('string_id', $stringId)->first() != null) {
                    $stringId = bin2hex(random_bytes(3));
                }
                else
                    break;
            }
        } else
            $stringId = $custom_alias;

        // create and store url to database
    	$urlModel = new Url;
    	$urlModel->url = $url;
        $urlModel->protocol = $protocol;
    	$urlModel->string_id = $stringId;
        $urlModel->is_private = $is_private;
    	$urlModel->save();

        // add url id to session (used to create local history of shortened urls)
    	session()->push('urlIDs', $urlModel->string_id); // push string_id to the session (need to generate local history)
    	
    	$shortenUrl = url('/').'/'.$stringId;

    	return view('pages.shorten', ['shortenUrl' => $shortenUrl]);
    }

    /**
    * @desc Store information about user to DB and redirect user.
    * @param $id
    * @return Response
    */
    public function redirect($id) 
    {
    	$ua = parse_user_agent(); // parsed user agent

        $geo = new Geolocation; // default geolocation by ip provider: http://freegeoip.net/

        $userInfo['platform'] = $ua['platform'];
        $userInfo['browser'] = $ua['browser'];
		$userInfo['ip'] = $geo->ip;
		$userInfo['country_code'] = $geo->country_code;
		$userInfo['country_name'] = $geo->country_name;
		$userInfo['http_referer'] = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : null;

        if(isset($_SERVER['HTTP_REFERER']))
            $userInfo['http_referer'] = $_SERVER['HTTP_REFERER'];

		$url = Url::where('string_id', '=', $id)->first();
		
		$urlStat = $url->stats()->create($userInfo);

		return redirect($url->protocol . $url->url, 301);
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
