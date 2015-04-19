<?php
/**
 * Bootstrap and run app
 */

// loading libs and config
require 'lib/Application.php';
require 'config/config.php';

// run app
$app = new Application();
$app->run();
