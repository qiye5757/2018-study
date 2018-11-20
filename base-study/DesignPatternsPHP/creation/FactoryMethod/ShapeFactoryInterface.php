<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:43
 */
interface shapeFactoryInterface{
    //绘制图形
    public function createShape();
    public static function createStaticShape();
}