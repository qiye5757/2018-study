<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-12-1
 * Time: 下午11:17
 */


//定义颜色接口
Interface Color{
    public function showColor();
}


//形状抽象类
abstract class Shape
{

    protected $color;


    public function __construct(Color $color)
    {
        $this->color = $color;
    }

    public function setColor(Color $color){
        $this->color = $color;
    }
    abstract public function draw();
}

class Red implements Color{
    public function showColor(){
        return "red";
    }
}

class Green implements Color{
    public function showColor(){
        return "green";
    }
}

class Square extends Shape{
    public function draw(){
        echo "i am " . $this->color->showColor() . " square\n";
    }
}

$redSquare = new Square(new Red());
$redSquare->draw();
$greenSquare = new Square(new Green());
$greenSquare->draw();