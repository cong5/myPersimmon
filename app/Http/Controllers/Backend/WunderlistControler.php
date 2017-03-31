<?php

namespace App\Http\Controllers\Backend;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Models\Options;
use Persimmon\ThridService\Wunderlist;

class WunderlistControler extends Controller
{

    protected $wunderlist;
    protected $accessToken;

    public function __construct()
    {
        $accessToken = Options::firstOrCreate(['option_title' => 'Wunderlist授权Token', 'option_name' => 'wunderlist_access_token']);
        $this->accessToken = $accessToken->option_value;
        $this->wunderlist = new Wunderlist($this->accessToken);
    }

    public function index()
    {
        if (empty($this->accessToken)) {
            return response()->json([
                'auth' => 'Unauthenticated',
                'authUrl' => $this->getAuthUrl()
            ]);
        }
        $list = $this->wunderlist->getTasks();
        $response = [
            'status' => 1,
            'todo' => $list
        ];
        return response()->json($response);
    }

    public function getAuthUrl()
    {
        return $this->wunderlist->authUrl();
    }

    public function callback(Request $request)
    {
        $accessToken = Options::firstOrCreate(['option_name' => 'wunderlist_access_token']);
        $wunderlistAccessToken = $this->wunderlist->getAuthToken($request->input('code'));
        $accessToken->option_value = $wunderlistAccessToken['access_token'];
        $accessToken->save();
        return view('errors.close');
    }

}