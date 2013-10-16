<?php

namespace LocalBtc;

use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Common\Event;

class Client extends \Guzzle\Service\Client
{
    /**
     * Factory method to create a new instance of this client
     *
     * Available configuration options:
     *
     *     OAuth2 options:
     *         access_token
     *
     * @param array $config Configuration options
     * @return LocalBtc\Client
     */
    public static function factory($config = array())
    {
        $client = new Client('https://localbitcoins.com', array(
            'request.options' => array(
            ),
            'curl.options' => array(
            ),
        ));

        $description = ServiceDescription::factory(__DIR__.'/../../service.json');
        $client->setDescription($description);

        $client->getEventDispatcher()->addListener('request.before_send', function(Event $event) use($config) {
            $event['request']->getQuery()->set('access_token', $config['access_token']);
        });

        return $client;
    }
}
