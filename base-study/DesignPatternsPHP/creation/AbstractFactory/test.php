<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/6 0006
 * Time: 下午 7:15
 */
Interface Operation{
    public function controlOperation();
}

 Interface Ui{
     public function controlUi();
 }

 Interface SystemFactory{
     public function createOperationController();
     public function createUiController();
 }

 class AndroidOperation implements Operation{
     public function controlOperation(){
         echo "this is Android operation controller";
     }
 }

class AndroidUi implements Ui{
    public function controlUi(){
        echo "this is Android ui controller";
    }
}

 class AndroidSystemFactory implements SystemFactory{
     public function createOperationController(){
         return new AndroidOperation();
     }
     public function createUiController(){
         return new AndroidUi();
     }
 }

$androidSystemFactory = new AndroidSystemFactory();
$androidOperationController = $androidSystemFactory->createOperationController();
$androidOperationController->controlOperation();
$androidUiController = $androidSystemFactory->createUiController();
$androidUiController->controlUi();
