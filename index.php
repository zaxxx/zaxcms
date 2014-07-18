<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/zax/Bootstraps/Bootstrap.php';

(new Zax\Bootstraps\Bootstrap(__DIR__ . '/app', __DIR__))
    ->setDebuggers(['10.0.0.1'], ['my@email.com'])
    ->enableInject()
    ->enableDebugger()
    ->boot();
