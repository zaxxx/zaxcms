<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../zax/Bootstraps/Bootstrap.php';

Tester\Environment::setup();

$container = (new Zax\Bootstraps\Bootstrap(__DIR__ . '/../app', __DIR__ . '/../'))
	->enableConfigAutoload()
	->addConfig(__DIR__ . '/config/config.neon')
	->addLoaderPath(__DIR__ . '/zaxcms')
	->setDebuggers(['10.0.2.2', '10.0.0.1'], [''])
	->enableDebugger(FALSE, FALSE, FALSE)
	->setUp();

return $container;
