<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\ShortLink;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ShortLinkController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $shortLinks = ShortLink::latest()->get();
   
        return view('shortenLink', compact('shortLinks'));
    }
     
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
           'link' => 'required'
        ]);

        $link = $request->link;
        $parsedLink = parse_url($link);

		if (empty($parsedLink['scheme'])) {
		    $link = 'http://' . ltrim($link, '/');
		}

        $input = new Request([
		    'link' => $link,
		    'code' => Str::random(6),
            'expiry_at' => $request->date
		]);

        $this->validate($input, [
            'link' => 'required|url',
            'code' => 'required'
        ]);

        ShortLink::create($input->toArray());
  
        return redirect('/')->with('success', 'Короткая ссылка успешно создана!');
    }
   
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function shortenLink($code)
    {
        $find = ShortLink::where('code', $code)->where(function ($query) {
        	return $query->where('expiry_at', '>', Carbon::now()->toDateTimeString())->orWhere('expiry_at', null);
        })->firstOrFail();

        return redirect($find->link);
    }
}