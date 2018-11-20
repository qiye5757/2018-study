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
require_once "CircleShapeFactory.php";
require_once "RectShapeFactory.php";
require_once "TriangleShapeFactory.php";

//使用动态方法创建
$circleShapeFactory = new CircleShapeFactory();
$circleShape = $circleShapeFactory->createShape();
$circleShape->draw();

//使用静态方法创建
$rectShape = RectShapeFactory::createStaticShape();
$rectShape->draw();