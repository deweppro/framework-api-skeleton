<?php declare(strict_types=1);

require_once './../vendor/autoload.php';

Dewep\Config::setConfigPath(__DIR__.'/../config.yml');

(new Dewep\Application())->bootstrap();
