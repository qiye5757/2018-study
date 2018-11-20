<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/3 0003
 * Time: 下午 4:49
 */
require_once "ShapeFactory.php";
require_once "ShapeStaticFactory.php";

//使用shapeFactory创建

$shapeFactory = new ShapeFactory();
$circle = $shapeFactory->getShape("circle");
$circle->draw();

//使用shapeStaticFactory创建
$rect = ShapeStaticFactory::getShape("rect");
$rect->draw();