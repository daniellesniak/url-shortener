<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Url;
use Carbon\Carbon;

class MyShortensController extends Controller
{
    public function index()
    {
        if(session('urlIDs') == null)
            return redirect()->route('home')->with('message', ['message_text' => 'You have no shortens yet!', 'message_class' => 'is-warning']);

        $urlIDs = session('urlIDs');

        $myShortens = [];
        foreach($urlIDs as $urlId)
        {
            $singleShorten = Url::where('string_id', $urlId)->first();

            if($singleShorten != null) {
                $shortenRedirects = $singleShorten->stats()->count();

                $singleShortenArr = $singleShorten->toArray();
                $singleShortenArr['redirects_count'] = $shortenRedirects;

                array_push($myShortens, $singleShortenArr);
            }
        }

        return response()->view('pages.my-shortens', ['myShortens' => $myShortens]);
    }
}
