<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/12/13 0013
 * Time: 下午 10:52
 */

Interface Component{
    public function renderData();
}

class ConcreteComponent implements Component{
    public $data;

    public function __construct($data){
        $this->data = $data;
    }
    //输出字符串功能
    public function renderData(){
        return $this->data;
    }
}

abstract class Decorator implements Component
{
    protected $component;

    public function __construct(Component $component)
    {
        $this->component = $component;
    }
}

// Json装饰类
class JsonComponent extends Decorator{
    public function renderData(){
        return json_encode($this->component->renderData());
    }
}

// XML装饰类
class XmlComponent extends Decorator{
    public function renderData(){
        return "this is Xml renderData method";
    }
}

$component = new ConcreteComponent(["1", "2"]);
var_dump($component->renderData());

$jsonComponent = new JsonComponent($component);
var_dump($jsonComponent->renderData());

$xmlComponent = new XmlComponent($component);
var_dump($xmlComponent->renderData());

