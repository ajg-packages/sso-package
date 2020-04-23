<?php


Route::middleware(['web'])->group(function () {
    Route::redirect('/login', '/auth/redirect')->name('login');

    Route::get('/logout', function() {
        \Auth::logout();
        return view('sso::logout');
    });

    Route::get('/user', function() {
        $user = \App\Models\Auth\User::user();
        dd($user);
    });

    Route::get('/auth/redirect', function () {
        $query = http_build_query([
            'client_id' => env("AUTHORIZATION_SERVER_CLIENT_ID"),
            'redirect_uri' => url('/auth/callback'),
            'response_type' => 'code',
            'scope' => ''
        ]);
        return redirect(env('AUTHORIZATION_SERVER_URL') . '/oauth/authorize?'.$query);
    });


    Route::get('/auth/callback', function (Illuminate\Http\Request $request) {
        $http = new \GuzzleHttp\Client;
        $response = $http->post(env('AUTHORIZATION_SERVER_URL') . '/oauth/token', [
            'form_params' => [
                'client_id' => env("AUTHORIZATION_SERVER_CLIENT_ID"),
                'client_secret' => env("AUTHORIZATION_SERVER_CLIENT_SECRET"),
                'grant_type' => 'authorization_code',
                'redirect_uri' => url('/auth/callback'),
                'code' => $request->code,
            ],
        ]);
        $token = json_decode((string) $response->getBody(), true);

        $http = new \GuzzleHttp\Client;
        $response = $http->get(env('AUTHORIZATION_SERVER_URL') . '/api/user', [
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