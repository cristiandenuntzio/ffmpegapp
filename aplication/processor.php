<?php
/**
 *
 */

require 'lib/Processor.php';
require 'lib/Messenger.php';

$processor = new Processor();
$messenger = new Messenger();

$messenger->onNotification(array($processor, 'run'));
