<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Carbon\Carbon;
use App\Url;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index(Request $request)
    {
		$newestShortens = Url::orderBy('created_at', 'desc')->paginate(5);

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

			// Create collection form $urlsData and reverse to show newest first
			$urlsCollection = collect($urlsData)->reverse();

            // Calculate pages for urls
            $urlPages = ceil($urlsCollection->count() / 5);

            if($request->has('page') && is_numeric($request->input('page')))
                $urlsCollection = $urlsCollection->forPage($request->input('page'), 5);
            else
				$urlsCollection = $urlsCollection->forPage(1, 5);

            $currentPage = $request->input('page');
            if($currentPage == null)
                $currentPage = 1;

			return view('pages.home',
                ['urlsData' => $urlsCollection,
                    'urlPage' => ['pages' => $urlPages, 'currentPage' => $currentPage,
                        'previousPage' => $currentPage - 1, 'nextPage' => $currentPage + 1, 'lastPage' => $urlPages]
                    , 'newestShortens' => $newestShortens, 'carbon' => new Carbon()]);
		}

    	return view('pages.home', ['newestShortens' => $newestShortens, 'carbon' => new Carbon()]);
    }

    /**
     * @param $string_id
     * @return Response
     */
    public function hideUrl($string_id)
    {
        if (\Session::has( 'urlIDs' ) && is_array(\Session::get('urlIDs'))) {
            $urlIDs = \Session::get('urlIDs');
            $key = array_search($string_id, $urlIDs);
            unset($urlIDs[$key]);

            session(['urlIDs' => $urlIDs]);
        }
        return redirect()->route('home')->with('message', ['message_text' => 'URL has been hidden successfully!', 'message_class' => 'is-success']);
    }
}
