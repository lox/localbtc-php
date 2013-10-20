<?php

/**
 * A helper script to generate an access_token for subsequent
 * API calls
 *
 * Run oauth.php --authorize
 */

require_once(__DIR__.'/vendor/autoload.php');

$client = \LocalBtc\Authenticator::factory(array(
    'client_id' => getenv('LOCALBITCOINS_CLIENT_ID'),
    'client_secret' => getenv('LOCALBITCOINS_CLIENT_SECRET'),
));

// generate a authorize link
if(isset($argv) && in_array('--authorize', $argv)) {
    printf("Visit and authorize app:\n%s\n\n", $client->authorizeUrl(array(
        'response_type' => 'code',
        'scope' => 'read write',
        'redirect_uri' => 'http://localhost:9000/',
    )));

    printf("Listening on localhost:9000\n");
    printf("Use ctrl-c to kill after you have gotten the key\n");
    passthru("php -S localhost:9000 ".$argv[0]);
    exit(0);
}

// parse the http query string
$query = array();
parse_str($_SERVER['QUERY_STRING'], $query);

// get an access token
$token = $client->accessToken(array(
    'code' => $query['code'],
    'grant_type' => 'authorization_code',
    'client_id' => $client->getConfig('client_id'),
    'client_secret' => $client->getConfig('client_secret'),
));

date_default_timezone_set('UTC');
$expires = date('r', time()+$token['expires_in']);

echo <<<EOT
<body>
<h1>Access Token for <a href="http://localbitcoins.com">localbitcoins.com<a/></h1>
<p style="font-size: 18px; font-weight: bold">
{$token['access_token']}
</p>
<p>Expires on $expires</p>
</body>
EOT;
