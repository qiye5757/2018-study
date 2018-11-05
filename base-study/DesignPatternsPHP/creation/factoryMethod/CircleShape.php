<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:44
 */
require_once "shapeInterface.php";
class CircleShape implements shapeInterface{
    public function draw(){
        echo "this is circle shape draw method\n";
    }
}