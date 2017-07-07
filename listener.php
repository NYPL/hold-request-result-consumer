<?php
require __DIR__ . '/vendor/autoload.php';

use NYPL\Starter\Config;
use NYPL\HoldRequestResultConsumer\HoldRequestResultConsumerListener;
use NYPL\Starter\Listener\ListenerEvents\KinesisEvents;

Config::initialize(__DIR__);

$listener = new HoldRequestResultConsumerListener();

$listener->process(
    new KinesisEvents(),
    'HoldRequestResult'
);
