<?php

use app\App;
use Noodlehaus\Config;

require_once dirname(__DIR__) . '/vendor/autoload.php';

/**
 * init config
 */
$config = new Config(dirname(__DIR__) . '/config/main.php');
App::set('config', $config);