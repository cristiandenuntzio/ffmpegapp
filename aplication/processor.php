<?php
/**
 *
 */

require 'lib/Processor.php';
require 'lib/Messenger.php';
require 'config/config.php';

$processor = new Processor();
$messenger = new Messenger();

$messenger->onNotification(array($processor, 'run'));
