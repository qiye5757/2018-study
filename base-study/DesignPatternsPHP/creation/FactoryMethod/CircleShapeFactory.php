<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:43
 */
require_once "ShapeFactoryInterface.php";
require_once "CircleShape.php";
class CircleShapeFactory implements shapeFactoryInterface {
    //绘制图形
    public function createShape(){
        return new CircleShape();
    }
    public static function createStaticShape(){
        return new CircleShape();
    }
}