<?php
/**
Test for processor
 */
$basePath = realpath(dirname(__FILE__) . '/../../aplication');

require $basePath . '/lib/Messenger.php';
require $basePath . '/config/config.php';

$messenger = new Messenger();
$messenger->notify();