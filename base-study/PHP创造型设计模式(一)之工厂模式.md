# PHP创造型设计模式

参考

+ [16 个 PHP 设计模式详解](https://juejin.im/entry/58f1c5ac61ff4b0058ed2d63)
+ [工厂模式——看这一篇就够了](https://juejin.im/entry/58f5e080b123db2fa2b3c4c6)
+ [PHP 设计模式全集 2018](https://laravel-china.org/docs/php-design-patterns/2018/)

## 1. 工厂模式

 工厂模式具体可以分为三种：简单工厂模式，工厂方法模式以及工厂抽象模式。

### 简单工厂模式

 简单工厂模式其实并不算一种设计模式，更多的时候算是一种设计思想。
 
### 简单工厂模式如何定义？

 定义一个工厂类，根据传入的参数不同返回不同的实例，被创建的实例拥有共同的父类或者接口。
 
### 适用场景
 
  先由于只有一个工厂类，所以工厂类中创建的对象不能太多，否则工厂类的业务逻辑就太复杂了，其次由于工厂类封装了对象的创建过程，所以客户端应该不关心对象的创建。总结一下适用场景：
 + 需要创建的对象较少。
 + 客户端不关心对象的创建过程。
 
### 使用实例
  
   创建一个可以绘制不同形状的绘图工具，可以绘制圆形、正方形等等
   
#### 第一步：创建接口

    interface shape{
        //绘制图形
        public function draw();
    }
    
#### 第二步：定义不同的图形类
    
    //圆形
    class circleShape implements shape{
        public function draw(){
            echo "this is circle shape draw method";
        }
    } 
    //正方形
    class rectShape implements shape{
        public function draw(){
            echo "this is rect shape draw method";
        }
    } 
    //三角形
    class triangleShape implements shape{
        public function draw(){
            echo "this is triangle shape draw method";
        }
    }
     
#### 第三步：定义工厂类

 在PHP中有两种方式定义工厂类，第一方式即使用静态方法（这种方式也可以叫做静态工厂模式），第二种即使用非静态方法。**（当然都是一样的，只是非静态方法便于测试罢了）**
 
##### 使用静态方法定义

    class ShapeStaticFactory{
        public static function getShape($shapeType){
            if($shapeType == "circle"){
                return new circleShape();
            }elseif($shapeType == "rect"){
                return new rectShape();
            }elseif($shapeType == "triangle"){
                return new triangleShape();
            }
        }
    }
    
##### 使用非静态方法定义

    class ShapeFactory{
        public function getShape($shapeType){
            if($shapeType == "circle"){
                return new CircleShape();
            }elseif($shapeType == "rect"){
                return new RectShape();
            }elseif($shapeType == "triangle"){
                return new TriangleShape();
            }
        }
    }
    
#### 第四步：测试

    //使用shapeFactory创建
    
    $shapeFactory = new ShapeFactory();
    $circle = $shapeFactory->getShape("circle");
    $circle->draw();
    
    //使用shapeStaticFactory创建
    $rect = ShapeStaticFactory::getShape("rect");
    $rect->draw();
    
## 2. 工厂方法模式

 相对于简单工厂模式来说，简单工厂模式只有一个工厂类，如果需要增加新的产品，则需要修改工厂类，不符合开闭原则。对于工厂方法模式来说，可以创建多个工厂类，只需要提供一个工厂类的接口，通过这个接口可以定义多个工厂类。

### 工厂方法模式如何定义？

 定义一个产品接口，定义一个工厂接口。新增一个产品类则新增一个工厂类。
 
### 实例
 
  创建一个可以绘制不同形状的绘图工具，可以绘制圆形、正方形等等

#### 第一步：创建接口

 产品接口：
 
     interface shape{
         //绘制图形
         public function draw();
     }
 工厂接口:
 
    interface shapeFactory{
        //创建图形绘制类
        public function createShape();
        public static function createStaticShape();
    }
#### 第二步：定义不同的图形类

    //圆形
    class circleShape implements shape{
        public function draw(){
            echo "this is circle shape draw method";
        }
    } 
    //正方形
    class rectShape implements shape{
        public function draw(){
            echo "this is rect shape draw method";
        }
    } 
    //三角形
    class triangleShape implements shape{
        public function draw(){
            echo "this is triangle shape draw method";
        }
    } 
    
#### 第三步：定义不同的工厂类

    //圆形
    class circleShapeFactory implements shapeFactory{
         public function createShape(){
             return new circleShape();
         }
         
         public function createStaticShape(){
              return new circleShape();
          }
    } 
    //正方形
    class rectShape implements shape{
         public function draw(){
             echo "this is rect shape draw method";
         }
         public static function createStaticShape(){
             return new circleShape();
         }
    } 
    //三角形
    class triangleShape implements shape{
         public function draw(){
             echo "this is triangle shape draw method";
         }
         public static function createStaticShape(){
             return new circleShape();
         }
    } 
    
#### 第四步：测试

    $circleShapeFactory = new CircleShapeFactory();
    $circleShape = $circleShapeFactory->createShape();
    $circleShape->draw();
    
    $rectShape = RectShapeFactory::createStaticShape();
    $rectShape->draw();
    
## 3.抽象工厂模式

 相对于工厂方法模式来说，抽象工厂模式中的工厂类接口中可以生产一组产品。
 
### 抽象工厂模式如何定义？

 定义多个产品接口，定义一个工厂接口。定义多个产品类同时定义一个工厂类。
 
### 缺点

 抽象工厂模式是不符合开闭原则的，如果新增一个产品，则需要修改工厂接口和工厂类。
 
### 实例

 现在需要做一款跨平台的游戏，需要兼容Android，Ios，Wp三个移动操作系统，该游戏针对每个系统都设计了一套操作控制器（OperationController）和界面控制器（UIController）。
 
#### 第一步：定义产品接口

定义操作控制器接口：

    Interface Operation{
        public function controlOperation();
    }
    
定义界面控制器接口：

    Interface Ui{
        public function controlUi();
    }
    
定义工厂接口(可以是静态，也可以是动态方法)：

    Interface SystemFactory{
        public function createOperationController();
        public function createUiController();
    }
    
#### 第二步：定义产品类(以Android举例)

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
    
#### 第三步：定义工厂类

    class AndroidSystemFactory implements SysytemFactory{
        public function createOperationController(){
            return new AndroidOperation();
        }
        public function createUiController(){
            return new AndroidUi();
        }
    }
    
#### 第四步：测试

    $androidSystemFactory = new AndroidSystemFactory();
    $androidOperationController = $androidSystemFactory->createOperationController();
    $androidOperationController->controlOperation();
    $androidUiController = $androidSystemFactory->createUiController();
    $androidUiController->controlUi();
 
 
    
    
                    
              
        