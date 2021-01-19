<?php

namespace App;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;

class SsoClient {
	
	private $serverUrl;
	private $clientId;
	private $clientSecret;
	private $clientUrl;

    public function __construct()
    {
        $url = env('SSO_SERVER_URL');
        if(!$url) throw new \ErrorException('SSO server URL is not specified');

        $this->serverUrl = $url;


        $id = env('SSO_CLIENT_ID');
        if (!$id) throw new \ErrorException("SSO Client id is not specified");
        $this->clientId = $id;


        $secret = env("SSO_CLIENT_SECRET");
        if (!$secret) throw new \ErrorException("SSO Client secret is not specified");
        $this->clientSecret = $secret;


        $protocol = !empty($_SERVER['HTTPS']) ? 'https://' : 'http://';
        $this->clientUrl = $protocol . $_SERVER['HTTP_HOST'];

    }

    private function InitClient() {
    	return new Client([
		    'verify' => false,
		    'curl' => [
		        CURLOPT_SSL_VERIFYHOST => false,
		        CURLOPT_SSL_VERIFYPEER => false
		    ]
		]);
    }

    private function OpenRequest($method, $url, $params) {
	    $client = $this->InitClient();

	    try {
	    	$response = $client->request($method, $url, $params);
	    	return $response;
	    } catch (RequestException $e) {
	    	if($e->getCode() == 401) return $e->getResponse();
	    	else abort($e->getCode(), $e->getResponse()->getBody());
	    }
    }

	public function Login(Request $request) {
	    
	    $query = http_build_query([
	        'client_id' => $this->clientId, 
	        'redirect_uri' => $this->clientUrl. '/callback',
	        'response_type' => 'code',
	        'scope' => ''
	    ]);

	    $url = $this->serverUrl.'oauth/authorize?'.$query;

	    return redirect($url);
	}

	public function Callback(Request $request, $redirect_uri='') {
	    $response = $this->OpenRequest('POST',$this->serverUrl.'oauth/token', 
	    	['form_params' => [
	            'grant_type' => 'authorization_code',
	            'client_id' => $this->clientId, 
	            'client_secret' => $this->clientSecret,
	            'redirect_uri' => $this->clientUrl. '/callback',
	            'code' => $request->code,
	        	]
	    	]);

	    session()->put('token', json_decode((string) $response->getBody(), true));

	    $user_info = $this->Userinfo($request);
	    session()->put('userinfo', $user_info, true);
	    $this->GetRoles($user_info);

	    if($redirect_uri != '') return redirect($redirect_uri);
	}

	private function GetRoles($userinfo)
	{
		$roles = array();
		foreach($userinfo["roles"] as $role) $roles[] = $role["name"];

		session()->put('roles', $roles, true);
	}

	public function Logout() {
	    // $response = $this->OpenRequest('POST',$this->serverUrl.'api/logout', 
	    // 	['headers' => [
	    //         'Authorization' => 'Bearer '.session()->get('token.access_token')
	    //     	]
	    // 	]);

	    session()->forget('token');
	    session()->forget('userinfo');
	    session()->forget('roles');

	    $url = $this->serverUrl.'logoutsso?back='.$this->clientUrl;
	    return redirect($url);
	}

	public function Userinfo(Request $request) {
	    $response = $this->OpenRequest('GET',$this->serverUrl.'api/user', 
	    	['headers' => [
	            'Authorization' => 'Bearer '.session()->get('token.access_token'),
	            'Accept' => 'application/json',
	        	]
	    	]);

	    $statusCode = $response->getStatusCode();

	    if($statusCode == '200') 
	    	return json_decode((string) $response->getBody(), true);
	    else if($statusCode == '401') {
	    	return $this->Login($request);
	    }
	    else 
	    	abort($statusCode, $response->getBody());
	}
}