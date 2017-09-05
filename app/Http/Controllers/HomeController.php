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
        /* Get last 5 shortens. */
		$newestShortens = Url::orderBy('created_at', 'desc')->where('is_private', false)->take(5)->get();

        /* Count redirects */
		$newestShortens->map(function ($sh) {
		    $sh['redirects_count'] = $sh->stats()->count();
		    return $sh;
        });

		$currentPage = $request->input('page');

		/* Get my shortens */
    	if(session('urlIDs') != null)
		{
			$sessionUrlsIds = session('urlIDs');

			$myShortens = [];

			foreach($sessionUrlsIds as $urlId)
			{
                $singleUrl = Url::where('string_id', $urlId)->first();
                
                if($singleUrl != null)
                    array_push($myShortens, [
                        'string_id' => $urlId,
                        'url' => $singleUrl->url,
                        'created_at' => $singleUrl->created_at,
                        'ago_date' => Carbon::instance($singleUrl->created_at)->diffForHumans(),
                        'redirects_count' => $singleUrl->stats()->count()
                    ]);
                else
                    continue;
			}

			// Reverse $myShortens to show newest firstly
			$myShortens = collect($myShortens)->reverse();

            // Calculate number of pages (it's also last page)
            $shortenPages = ceil($myShortens->count() / 5);

            // If current page is greater than last page set it to the last page.
            if($currentPage > $shortenPages)
                $currentPage = $shortenPages;

            if($currentPage != null && is_numeric($currentPage))
                $myShortens = $myShortens->forPage($currentPage, 5);
            else
                $myShortens = $myShortens->forPage(1, 5);

            if($currentPage == null || !is_numeric($currentPage))
                $currentPage = 1;

			return view('pages.home',
                [
                    'myShortens' => $myShortens,
                    'newestShortens' => $newestShortens,
                    'carbon' => new Carbon(),
                    'pagination' => [
                        'pages' => $shortenPages, 'currentPage' => $currentPage,
                        'previousPage' => $currentPage - 1, 'nextPage' => $currentPage + 1, 'lastPage' => $shortenPages
                    ]
                ]);
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
