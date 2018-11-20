<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:44
 */
require_once "ShapeInterface.php";
class RectShape implements ShapeInterface{
    public function draw(){
        echo "this is rect shape draw method\n";
    }
}