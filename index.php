<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/zax/Bootstraps/Bootstrap.php';

$rootDir = __DIR__;
$appDir = $rootDir . '/app';
//$tempDir = '/webcache/zaxcms';
$tempDir = $appDir . '/temp';

(new Zax\Bootstraps\Bootstrap($appDir, $rootDir, $tempDir))
	->enableConfigAutoload()
	->setDebuggers(['10.0.2.2', '10.0.0.1', '::1'], [''])
	->enableDebugger()
	//->enableDebugger(TRUE)
	->boot();
