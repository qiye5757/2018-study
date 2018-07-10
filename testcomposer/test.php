<?php
require 'vendor/autoload.php';
 
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
 
$log = new Logger('demo');
$log->pushHandler(new StreamHandler('app.log', Logger::WARNING));
$log->addWarning("this is a warning log!");
