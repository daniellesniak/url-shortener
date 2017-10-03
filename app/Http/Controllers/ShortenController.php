<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Url;
use Carbon\Carbon;
use Illuminate\Http\Response;
use App\Helpers\Geolocation\Services\FreeGeoIpGService;

/**
 * Class UrlController
 * @package App\Http\Controllers
 */
class ShortenController extends Controller
{
    /**
     * Return view with mine and new shortens.
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('pages.home', [
            'newestShortens' => Url::latest()->paginate(5),
            'myShortens' => $this->getMyShortens()
        ]);
    }

    /**
     * Store a new shorten to the database.
     *
     * @param Requests\ShortenRequest $request
     * @return Response
     */
    public function store(Requests\ShortenRequest $request)
    {
        $shorten = Url::create($request->all());

        $this->addToMyShortens($shorten->slug); // add slug to the session (it's useful to create a local shortens history)

        return view('pages.shorten', ['shorten' => $shorten]);
    }

    /**
     * Store information about user to DB and redirect user.
     *
     * @param $slug
     * @return Response
     * @internal param $id
     */
    public function redirect($slug)
    {
        $shorten = Url::where('slug', $slug)->firstOrFail()->createStat(); // find shorten by id or fail and create stat

        return redirect($shorten->url);
    }

    /**
     * @param Request $request
     * @param $slug
     * @return Response
     */
    public function stats(Request $request, $slug)
    {
        $shorten = Url::where('slug', $slug)->firstOrFail();

        $from = request('from');
        $to = request('to');

        if($from && $to) {
            $stats = $shorten->stats()->whereBetween('created_at', [$from, $to])->get();
        } else
        {
            $stats = $shorten->stats()->get();
        }

        return view('pages.stats',
            [
                'shorten' => $shorten,
                'stats' => $stats
            ]
        );
    }

    /**
     * Add shorten slug to myShortens session.
     *
     * @param $slug
     * @return bool
     */
    private function addToMyShortens($slug)
    {
        session()->push('myShortens', $slug);
        return true;
    }

    /**
     * Get all my shortens.
     *
     * @return \App\Url
     */
    private function getMyShortens()
    {
        $myShortens = null;

        if (session('myShortens')) {
            $slugs = session('myShortens');

            foreach ($slugs as $index => $slug) // check if slugs are correct
            {
                $shorten = Url::where('slug', $slug)->first();

                if (!$shorten) // if not correct remove form $slugs[]
                {
                    unset($slugs[$index]);
                    continue;
                }
            }

            session(['myShortens' => $slugs]); // remove not correct slugs from session

            $myShortens = Url::whereIn('slug', $slugs)->latest()->paginate(5);
        }

        return $myShortens;
    }

    /**
     * Remove slug from myShortens session.
     *
     * @param $slug
     * @return Response
     */
    public function hideShorten($slug)
    {
        if (\Session::has( 'myShortens' ) && is_array(\Session::get('myShortens'))) {
            $myShortens = session('myShortens');
            $key = array_search($slug, $myShortens);
            unset($myShortens[$key]);

            session(['myShortens' => $myShortens]);
        } elseif(\Session::has('myShortens')) // if is not an array
            session()->forget('myShortens');

        return redirect()->route('home')->with('message', ['message_text' => 'URL has been hidden successfully!', 'message_class' => 'is-success']);
    }
}