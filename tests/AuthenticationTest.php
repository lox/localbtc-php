<?php

class AuthenticationTest extends \Guzzle\Tests\GuzzleTestCase
{
    public function testAuthorizeUrl()
    {
        $client = $this->getServiceBuilder()->get('test.localbtc.auth');
        $url = $client->authorizeUrl(array(
            'client_id' => '1234'
        ));

        $this->assertEquals(
            "https://localbitcoins.com/oauth2/authorize/?client_id=1234&response_type=code&scope=read+write",
            $url
        );
    }

    public function testAccessTokenViaUsernameAndPassword()
    {
        $client = $this->getServiceBuilder()->get('test.localbtc.auth');
        $token = $client->accessToken(array(
            'grant_type' => 'password',
            'username' => getenv('LOCALBITCOINS_USERNAME'),
            'password' => getenv('LOCALBITCOINS_PASSWORD'),
            'client_id' => getenv('LOCALBITCOINS_CLIENT_ID'),
        ));

        var_dump($token);
    }
}
