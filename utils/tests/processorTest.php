<?php
/**
Test for processor
 */
$basePath = realpath(dirname(__FILE__) . '/../../aplication');

require $basePath . '/lib/Messenger.php';

$messenger = new Messenger();
$messenger->notify();