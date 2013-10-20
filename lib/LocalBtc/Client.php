<?php

namespace LocalBtc;

use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Common\Event;
use Guzzle\Common\Collection;
use Guzzle\Plugin\Log\LogPlugin;

class Client extends \Guzzle\Service\Client
{
    public static function factory($config = array())
    {
        // default config values
        $default = array(
            'base_url' => 'https://localbitcoins.com/api',
            'debug' => false,
        );

        $config = Collection::fromConfig($config, $default, array(
            'base_url'
        ));

        $client = new self($config->get('base_url'), $config);
        $client->setDescription(ServiceDescription::factory(__DIR__.'/Resources/localbtc.json'));

        // optiona debugging
        if($config->get('debug')) {
            $client->addSubscriber(LogPlugin::getDebugPlugin());
        }

        // oauth2 hook
        $client->getEventDispatcher()->addListener('request.before_send', function(Event $event) use($config) {
            $event['request']->getQuery()->set('access_token', $config['access_token']);
        });

        return $client;
    }
}
