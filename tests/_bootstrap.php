<?php
// This is global bootstrap for autoloading

use Codeception\Util\Autoload;

require_once __DIR__ . '/../vendor/autoload.php';

Autoload::addNamespace('', __DIR__ . '/unit/');
Autoload::addNamespace('', __DIR__ . '/../src');
