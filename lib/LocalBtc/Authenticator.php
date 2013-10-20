<?php

namespace LocalBtc;

use Guzzle\Service\Description\ServiceDescription;
use Guzzle\Common\Event;
use Guzzle\Common\Collection;
use Guzzle\Plugin\Log\LogPlugin;

class Authenticator extends \Guzzle\Service\Client
{
    public function authorizeUrl($config = array())
    {
        $params = Collection::fromConfig($config,array(
            'client_id' => $this->getConfig('client_id'),
            'response_type' => 'code',
            'scope' => 'read write',
        ), array(
            'client_id'
        ));

        return sprintf("https://localbitcoins.com/oauth2/authorize/?%s",
            http_build_query($params->toArray()));
    }

    public static function factory($config = array())
    {
        // default config values
        $default = array(
            'base_url' => 'https://localbitcoins.com/oauth2/',
            'debug' => false
        );

        $config = Collection::fromConfig($config, $default, array(
            'base_url'
        ));

        // client with required keys
        $client = new self($config->get('base_url'), $config);

        // optiona debugging
        if($config->get('debug')) {
            $client->addSubscriber(LogPlugin::getDebugPlugin());
        }

        // load the service description
        $client->setDescription(ServiceDescription::factory(__DIR__.'/Resources/oauth.json'));

        return $client;
    }
}
