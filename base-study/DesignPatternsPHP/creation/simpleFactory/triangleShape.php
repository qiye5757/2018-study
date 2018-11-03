<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:44
 */
require_once "citycleShape.php";
require_once "rectShape.php";
require_once "triangleShape.php";

class ShapeFactory{
    public function getShape($shapeType){
        if($shapeType == "circle"){
            return new circleShape();
        }elseif($shapeType == "rect"){
            return new rectShape();
        }elseif($shapeType == "triangle"){
            return new triangleShape();
        }
        throw new InvalidArgumentException("无此类型");
    }
}