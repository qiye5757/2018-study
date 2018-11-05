<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:43
 */
require_once "shapeFactoryInterface.php";
require_once "RectShape.php";
class RectShapeFactory implements shapeFactoryInterface {
    //绘制图形
    public function createShape(){
        return new RectShape();
    }
    public static function createStaticShape(){
        return new RectShape();
    }
}