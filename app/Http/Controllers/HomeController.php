<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;
use App\Url;

class HomeController extends Controller
{
    public function index()
    {
		$newest = Url::orderBy('created_at', 'desc')->paginate(5);

    	if(session('urlIDs') != null)
		{
			$url = new Url;
			$urlIDs = session('urlIDs');

			$urlsData = [];

			foreach($urlIDs as $urlID)
			{
				$singleUrl = $url->where('string_id', $urlID)->first();
				array_push($urlsData, [
					'string_id' => $urlID,
					'url' => $singleUrl->url,
					'created_at' => $singleUrl->created_at,
					'ago_date' => Carbon::instance($singleUrl->created_at)->diffForHumans(),
					'redirects_count' => $singleUrl->stats()->count()
				]);
			}

			return view('pages.home', ['urlsData' => $urlsData, 'newestUrls' => $newest, 'carbon' => new Carbon()]);
		}

    	return view('pages.home', ['newestUrls' => $newest, 'carbon' => new Carbon()]);
    }
}
