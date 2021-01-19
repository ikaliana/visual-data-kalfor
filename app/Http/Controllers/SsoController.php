<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SsoClient;

class SsoController extends Controller
{
	private $client;

    public function __construct()
    {
        $this->client = new SsoClient();
    }

    public function Login(Request $request) {
	    return $this->client->Login($request);
    }

    public function Logout() {
        return $this->client->Logout();
    }

    public function Callback(Request $request) {
	    $this->client->Callback($request);

	    // return redirect('/');
	    return redirect()->route('home');
    }

    public function GetUserInfo(Request $request) {
	    return $this->client->Userinfo($request);
    }
}