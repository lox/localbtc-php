<?php

require_once(__DIR__.'/../vendor/autoload.php');

\Guzzle\Tests\GuzzleTestCase::setServiceBuilder(\Guzzle\Service\Builder\ServiceBuilder::factory(array(
    'test.localbtc.auth' => array(
        'class' => '\LocalBtc\Authenticator',
        'params' => array(
            'debug' => true
        )
    )
)));
