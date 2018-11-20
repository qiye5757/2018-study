<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6 0006
 * Time: 下午 8:42
 */
Interface Builder{
    public function createA();
    public function createB();
    public function createC();
    public function getProduct();
}

class product{
    protected $data = [];
    public function add($part){
        array_push($this->data, $part);
    }
    public function showProduct(){
        var_dump($this->data);
    }
}

class ConcreteBuilder implements Builder{
    public function __construct(){
        $this->product = new product();
    }
    public function createA(){
        $this->product->add("创建A");
    }
    public function createB(){
        $this->product->add("创建B");
    }
    public function createC(){
        $this->product->add("创建C");
    }
    public function getProduct(){
        return $this->product;
    }
}

class Director
{
    public $myBuilder;
    public function __construct(Builder $builder){
        $this->myBuilder = $builder;
    }
    public function startBuild()
    {
        $this->myBuilder->createA();
        $this->myBuilder->createB();
        $this->myBuilder->createC();
        return $this->myBuilder->getProduct();
    }
}

$director = new Director(new ConcreteBuilder());
$newProduct = $director->startBuild();
$newProduct->showProduct();