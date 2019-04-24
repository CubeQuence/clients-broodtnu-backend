<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Signature/Encryption Algorithm
    |--------------------------------------------------------------------------
    |
    | Indicates which algorithm is used to sign and encrypt the JWT.
    |
    */

    'algorithm' => 'RS256',

    /*
    |--------------------------------------------------------------------------
    | Expiration TTL
    |--------------------------------------------------------------------------
    |
    | This specifies how long a JWT is valid.
    |
    */

    'ttl' => [
        'access_token' => 60 * 15,
        'refresh_token' => 60 * 60 * 30
    ],

    /*
    |--------------------------------------------------------------------------
    | Private Key
    |--------------------------------------------------------------------------
    |
    | Transforms the .env private key from string to correct format.
    | https://stackoverflow.com/a/54287967/8750087
    |
    */

    'private_key' => str_replace('||||', PHP_EOL, env('JWT_PRIVATE_KEY')),

    /*
    |--------------------------------------------------------------------------
    | Public Key
    |--------------------------------------------------------------------------
    |
    | Transforms the .env public key from string to correct format.
    | https://stackoverflow.com/a/54287967/8750087
    |
    */

    'public_key' => str_replace('||||', PHP_EOL, env('JWT_PUBLIC_KEY'))
];