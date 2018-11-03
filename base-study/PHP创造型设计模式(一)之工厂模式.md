# PHP创造型设计模式

参考

+ [16 个 PHP 设计模式详解](https://juejin.im/entry/58f1c5ac61ff4b0058ed2d63)
+ [工厂模式——看这一篇就够了](https://juejin.im/entry/58f5e080b123db2fa2b3c4c6)
+ [PHP 设计模式全集 2018](https://laravel-china.org/docs/php-design-patterns/2018/)

## 工厂模式

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
    
#### 第二步定义不同的图形类
    
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

 在PHP中有两种方式定义工厂类，第一方式即使用静态方法（这种方式也可以叫做静态工厂模式），第二种即使用非静态方法。
 
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
    
    
                    
              
        