<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:43
 */
require_once "shapeFactoryInterface.php";
require_once "TriangleShape.php";
class TriangleShapeFactory implements shapeFactoryInterface {
    //绘制图形
    public function createShape(){
        return new TriangleShape();
    }
    public static function createStaticShape(){
        return new TriangleShape();
    }
}