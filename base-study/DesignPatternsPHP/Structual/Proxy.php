<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-11-25
 * Time: 下午11:02
 */

Interface Subject{
    public function request();
}

class RealSubject implements Subject{
    public function request(){
        echo "这个是真实主题\n";
    }
}

class Proxy implements Subject{

    public $realSubject;

    public function __construct(){
        $this->realSubject = new RealSubject();
    }

    public function request(){
        $this->preRequest();
        $this->realSubject->request();
        $this->postRequest();
    }

    public function preRequest(){
        echo "这是调用真实主题之前的\n";
    }

    public function postRequest(){
        echo "这是调用真实主题之后的\n";
    }
}

$testProxy = new Proxy();
$testProxy->request();