<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:49
 */
require_once "citycleShape.php";
require_once "rectShape.php";
require_once "triangleShape.php";

class ShapeStaticFactory{
    public static function getShape($shapeType){
        if($shapeType == "circle"){
            return new circleShape();
        }elseif($shapeType == "rect"){
            return new rectShape();
        }elseif($shapeType == "triangle"){
            return new triangleShape();
        }
    }
}