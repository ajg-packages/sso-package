<?php
return [
    "auth" => [
        "url" => env('AUTHORIZATION_SERVER_URL', null),
        "client_id" => env('AUTHORIZATION_SERVER_CLIENT_ID', null),
        "client_secret" => env('AUTHORIZATION_SERVER_CLIENT_SECRET', null),
        "scopes" => [
            "view-user"
        ]
    ]
];