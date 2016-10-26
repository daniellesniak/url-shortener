<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;

class HomeController extends Controller
{
    public function index()
    {
    	if(session('urlIDs') != null)
		{
			$url = new \App\Url;
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

			return view('pages.home', ['urlsData' => $urlsData]);
		}

    	return view('pages.home');
    }
}
