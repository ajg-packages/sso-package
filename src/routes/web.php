<?php


Route::middleware(['web'])->group(function () {
    Route::redirect('/login', '/auth/redirect')->name('login');

    Route::get('/logout', function() {
        \Auth::logout();
        return view('sso::logout');
    });

    Route::get('/user', function(Illuminate\Http\Request $request) {
        $token = json_decode($request->session()->get("user"), true)['token'];
        $http = new \GuzzleHttp\Client;
        $response = $http->get(config('sso.auth.url') . '/api/user', [
            'headers' => [
                'Authorization' => "Bearer " . $token['access_token']
            ],
        ]);
        $user = collect(json_decode((string) $response->getBody(), true));
        return $user;
    });

    Route::get('/auth/redirect', function (Illuminate\Http\Request $request) {
        $request->session()->put("state", $state = Illuminate\Support\Str::random(40));
        $query = http_build_query([
            'client_id' => config('sso.auth.client_id'),
            'redirect_uri' => url('/auth/callback'),
            'response_type' => 'code',
            'scope' => config('sso.auth.scopes'),
            "state" => $state
        ]);
        return redirect(config('sso.auth.url') . '/oauth/authorize?'.$query);
    });


    Route::get('/auth/callback', function (Illuminate\Http\Request $request) {
        $state = $request->session()->pull("state");
        // dd((strlen($state) > 0 && $state == $request->state));
        throw_unless(strlen($state) > 0 && $state == $request->state, InvalidArgumentException::class);

        $http = new \GuzzleHttp\Client;
        $response = \Illuminate\Support\Facades\Http::asForm()->post(config('sso.auth.url') . '/oauth/token', [
            'grant_type' => 'authorization_code',
            'client_id' => config('sso.auth.client_id'),
            'client_secret' => config('sso.auth.client_secret'),
            'redirect_uri' => url('/auth/callback'),
            'code' => $request->code
        ]);
        $token = json_decode((string) $response->getBody(), true);
        $http = new \GuzzleHttp\Client;
        $response = $http->get(config('sso.auth.url') . '/api/user', [
            'headers' => [
                'Authorization' => "Bearer " . $token['access_token']
            ],
        ]);
        $user = collect(json_decode((string) $response->getBody(), true));
        $user['token'] = $token;
        session()->put('user', $user);
        return redirect('/');
    });
});
