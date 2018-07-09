<?php


function my_autoloader($class) {

	var_dump(dirname($class)); exit;
    include 'classes/' . $class . '.class.php';
}

spl_autoload_register('my_autoloader');


$a = new \test\aaa\b();
