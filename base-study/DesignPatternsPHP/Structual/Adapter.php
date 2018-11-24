<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 18-11-24
 * Time: 下午6:33
 */

class Adaptee{

    public function usedMethod(){
        echo "this is Adaptee method\n";
    }
}

interface Target{
    public function request();
}

//class Adapter extends Adaptee implements Target{
//    public function request(){
//        $this->usedMethod();
//    }
//}

class Adapter implements Target{
    public $adaptee;

    public function __construct(Adaptee $adaptee){
        $this->adaptee = $adaptee;
    }

    public function request(){
        $this->adaptee->usedMethod();
    }
}

$adapter = new Adapter(new Adaptee());
$adapter->request();