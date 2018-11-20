<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:44
 */
require_once "ShapeInterface.php";
class TriangleShape implements shapeInterface{
    public function draw(){
        echo "this is triangle shape draw method\n";
    }
}