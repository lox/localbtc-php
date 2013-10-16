<?php

/**
 * A helper script to generate an access_token for subsequent
 * API calls
 *
 * First run oauth.php --authorize, then php -S localhost:9000 and then
 * client the link from authorize
 */

require_once(__DIR__.'/vendor/autoload.php');

$clientId = getenv('LOCALBITCOINS_CLIENT_ID');
$clientSecret = getenv('LOCALBITCOINS_CLIENT_SECRET');

use Guzzle\Http\Client;
use Guzzle\Http\Exception\ClientErrorResponseException;

// generate a authorize link
if(isset($argv) && in_array('--authorize', $argv)) {
    $params = array(
        'client_id' => $clientId,
        'response_type' => 'code',
        'scope' => 'read write',
        'redirect_uri' => 'http://localhost:9000/',
    );

    printf("https://localbitcoins.com/oauth2/authorize/?%s\n",
        http_build_query($params));

    exit(0);
}

// parse the http query string
$query = array();
parse_str($_SERVER['QUERY_STRING'], $query);

// get an access token
$client = new Client('https://localbitcoins.com');
$request = $client->post('/oauth2/access_token/', array(), array(
    'code' => $query['code'],
    'grant_type' => 'authorization_code',
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    ));

try {
    $response = $request->send();
    echo '<pre>';
    var_dump($response->json());
    echo '</pre>';
} catch(ClientErrorResponseException $e) {
    printf('<h1>Error</h1><pre>Server replied:<br /><pre><code>%s</code></pre>',
        $e->getResponse()->getBody());
}



