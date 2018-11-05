<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:49
 */
require_once "CircleShape.php";
require_once "RectShape.php";
require_once "TriangleShape.php";

class ShapeStaticFactory{
    public static function getShape($shapeType){
        if($shapeType == "circle"){
            return new CircleShape();
        }elseif($shapeType == "rect"){
            return new RectShape();
        }elseif($shapeType == "triangle"){
            return new TriangleShape();
        }
    }
}