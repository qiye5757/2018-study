# PHP创造型设计模式

参考

+ [16 个 PHP 设计模式详解](https://juejin.im/entry/58f1c5ac61ff4b0058ed2d63)
+ [工厂模式——看这一篇就够了](https://juejin.im/entry/58f5e080b123db2fa2b3c4c6)
+ [PHP 设计模式全集 2018](https://laravel-china.org/docs/php-design-patterns/2018/)
+ [23种设计模式](http://wiki.jikexueyuan.com/project/java-design-pattern/)

## 1. 简单工厂模式

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
 
### 使用实例
 
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
 
### 使用实例

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

    class AndroidSystemFactory implements SystemFactory{
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
    
## 工厂模式总结

作为一种创建类模式，在任何需要生成复杂对象的地方，都可以使用工厂方法模式。有一点需要注意的地方就是复杂对象适合使用工厂模式，而简单对象，特别是只需要通过new就可以完成创建的对象，无需使用工厂模式。如果使用工厂模式，就需要引入一个工厂类，会增加系统的复杂度。

### 工厂方法模式和抽象工厂的区别？

在抽象工厂模式中，有一个产品族的概念：所谓的产品族，是指位于不同产品等级结构中功能相关联的产品组成的家族。抽象工厂模式所提供的一系列产品就组成一个产品族；而工厂方法提供的一系列产品称为一个等级结构。
    
## 4.建造者模式

 建造者模式是一步一步创建一个复杂的对象，它允许用户只通过指定复杂对象的类型和内容就可以构建它们，用户不需要知道内部的具体构建细节。例如，一辆汽车由轮子，发动机以及其他零件组成，对于普通人而言，我们使用的只是一辆完整的车，这时，我们需要加入一个构造者，让他帮我们把这些组件按序组装成为一辆完整的车。
 
### 定义建造者模式？

![UML 图](http://upload-images.jianshu.io/upload_images/944365-e4842ec60f89315e.png?imageMogr2/auto-orient/strip%7CimageView2/2/w/1240)

如上图所示，建造者模式共分为四个类

+ Product类：一般是一个较为复杂的对象，也就是说创建对象的过程比较复杂，一般会有比较多的代码量。在本类图中，产品类是一个具体的类，而非抽象类。实际编程中，产品类可以是由一个抽象类与它的不同实现组成，也可以是由多个抽象类与他们的实现组成。
+ Builder类：引入Builder类的目的，是为了将建造的具体过程交与它的子类来实现。这样更容易扩展。一般至少会有两个抽象方法，一个用来建造产品，一个是用来返回产品。
+ ConcreteBuilder类：实现Builder类的所有未实现的方法，具体来说一般是两项任务：组建产品；返回组建好的产品。
+ Director类：负责调用适当的ConcreteBuilder类来组件Product，而且不与Product类发生依赖。一般来说用于封装那些易变的部分。

### 使用实例

 以下这个示例摘抄于[建造者模式（Builder Pattern）- 最易懂的设计模式解析](https://www.jianshu.com/p/be290ccea05a)
+ 背景：小成希望去电脑城买一台组装的台式主机
+ 过程：
    + 电脑城老板（Diretor）和小成（Client）进行需求沟通（买来打游戏？学习？看片？）
    + 了解需求后，电脑城老板将小成需要的主机划分为各个部件（Builder）的建造请求（CPU、主板blabla）
    + 指挥装机人员（ConcreteBuilder）去构建组件；
    + 将组件组装起来成小成需要的电脑（Product）

#### 第一步：创建Builder接口

    Interface Builder{
        public function createA();
        public function createB();
        public function createC();
        public function getProduct();
    }

#### 第二步：定义需要构建的产品

    class product{
       protected $data = [];
       public function add($part){
           array_push($this->data, $part);
       }
       public function showProduct(){
           var_dump($this->data);
       }
    }
#### 第三步：创建ConcreteBuilder子类

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
    
#### 第四步：创建Diretor类

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

#### 第五步：测试

    $director = new Director(new ConcreteBuilder());
    $newProduct = $director->startBuild();
    $newProduct->showProduct();
  
 如果此时需要创建同样流程新的产品，则定义ConcreteBuilder类和Product类即可。
 
### 建造者模式与工厂模式的区别
   
   我们可以看到，建造者模式与工厂模式是极为相似的，总体上，建造者模式仅仅只比工厂模式多了一个"导演类"的角色。在建造者模式的类图中，假如把这个导演类看做是最终调用的客户端，那么图中剩余的部分就可以看作是一个简单的工厂模式了。
   
   与工厂模式相比，建造者模式一般用来创建更为复杂的对象，因为对象的创建过程更为复杂，因此将对象的创建过程独立出来组成一个新的类——导演类。也就是说，工厂模式是将对象的全部创建过程封装在工厂类中，由工厂类向客户端提供最终的产品；而建造者模式中，建造者类一般只提供产品类中各个组件的建造，而将具体建造过程交付给导演类。由导演类负责将各个组件按照特定的规则组建为产品，然后将组建好的产品交付给客户端。
   
## 5. 单例模式（Singleton）

单例模式，也叫单子模式，是一种常用的软件设计模式。在应用这个模式时，单例对象的类必须保证只有一个实例存在。许多时候整个系统只需要拥有一个的全局对象，这样有利于我们协调系统整体的行为。

常用场景：

+ 需要频繁实例化然后销毁的对象。
+ 创建对象时耗时过多或者耗资源过多，但又经常用到的对象。
+ 有状态的工具类对象。
+ 频繁访问数据库或文件的对象。
+ 以及其他我没用过的所有要求只有一个对象的场景。

单例模式的缺点比较多，单例模式不是用来解耦的，违反了单一职责原则，因为一个类控制了自己的创建。为了获得更好的可测试性和可维护性，请使用依赖注入模式。

### 实例

    //(懒汉式加载)
    class Signlenton{
        /**
         * @var Singleton
         */
        private static $instance;
        /**
         * 不允许从外部调用以防止创建多个实例
         * 要使用单例，必须通过 Singleton::getInstance() 方法获取实例
         */
        private function __construct()
        {
        }
    
        /**
         * 防止实例被克隆（这会创建实例的副本）
         */
        private function __clone()
        {
        }
    
        /**
         * 防止反序列化（这将创建它的副本）
         */
        private function __wakeup()
        {
        }
    
        public function test(){
            echo "test";
        }
        /**
         * 通过懒加载获得实例（在第一次使用的时候创建）
         */
        public static function getInstance()
        {
            if (null === static::$instance) {
                static::$instance = new static();
            }
    
            return static::$instance;
        }
    }
    
    $signlenton = Signlenton::getInstance();
    $signlenton->test();
    
### PHP中的单例模式

 在Java等静态语言中，单例模式可以全局访问。但是对于PHP解释型语言来说，只适合于单次请求中多次实例化一个对象。
 
## 6.多例模式

多例模式是指存在一个类有多个相同实例，而且该实例都是该类本身。这个类叫做多例类。 多例模式的特点是：

+ 多例类可以有多个实例。
+ 多例类必须自己创建、管理自己的实例，并向外界提供自己的实例。
+ 多例模式实际上就是单例模式的推广。

### 实例

     class Multiton
    {
        /**
         * @var 实例数组
         */
        private static $instances = [];
    
        /**
         * 这里私有方法阻止用户随意的创建该对象实例
         */
        private function __construct()
        {
        }
    
        public static function getInstance(string $instanceName)
        {
            if (!isset(self::$instances[$instanceName])) {
                self::$instances[$instanceName] = new self();
            }
    
            return self::$instances[$instanceName];
        }
    
        /**
         * 该私有对象阻止实例被克隆
         */
        private function __clone()
        {
        }
    
        /**
         * 该私有方法阻止实例被序列化
         */
        private function __wakeup()
        {
        }
    }
    
    
## 7.对象池模式（转载至[ 对象池模式（Pool）](https://laravel-china.org/docs/php-design-patterns/2018/Pool/1491#adcc23)）

###  目的
对象池模式是一种提前准备了一组已经初始化了的对象『池』而不是按需创建或者销毁的创建型设计模式。对象池的客户端会向对象池中请求一个对象，然后使用这个返回的对象执行相关操作。当客户端使用完毕，它将把这个特定类型的工厂对象返回给对象池，而不是销毁掉这个对象。

在初始化实例成本高，实例化率高，可用实例不足的情况下，对象池可以极大地提升性能。在创建对象（尤其是通过网络）时间花销不确定的情况下，通过对象池在可期时间内就可以获得所需的对象。

无论如何，对象池模式在需要耗时创建对象方面，例如创建数据库连接，套接字连接，线程和大型图形对象（比方字体或位图等），使用起来都是大有裨益的。在某些情况下，简单的对象池（无外部资源，只占内存）可能效率不高，甚至会有损性能。


### 实例

    WorkerPool.php
    <?php
    
    namespace DesignPatterns\Creational\Pool;
    
    class WorkerPool implements \Countable
    {
        /**
        * @var StringReverseWorker[]
        */
        private $occupiedWorkers = [];
    
        /**
        * @var StringReverseWorker[]
        */
        private $freeWorkers = [];
    
        public function get(): StringReverseWorker
        {
            if (count($this->freeWorkers) == 0) {
                $worker = new StringReverseWorker();
            } else {
                $worker = array_pop($this->freeWorkers);
            }
    
            $this->occupiedWorkers[spl_object_hash($worker)] = $worker;
    
            return $worker;
        }
    
        public function dispose(StringReverseWorker $worker)
        {
            $key = spl_object_hash($worker);
    
            if (isset($this->occupiedWorkers[$key])) {
                unset($this->occupiedWorkers[$key]);
                $this->freeWorkers[$key] = $worker;
            }
        }
    
        public function count(): int
        {
            return count($this->occupiedWorkers) + count($this->freeWorkers);
        }
    }
    
    ## 测试
    Tests/PoolTest.php
    <?php
    
    namespace DesignPatterns\Creational\Pool\Tests;
    
    use DesignPatterns\Creational\Pool\WorkerPool;
    use PHPUnit\Framework\TestCase;
    
    class PoolTest extends TestCase
    {
        public function testCanGetNewInstancesWithGet()
        {
            $pool = new WorkerPool();
            $worker1 = $pool->get();
            $worker2 = $pool->get();
    
            $this->assertCount(2, $pool);
            $this->assertNotSame($worker1, $worker2);
        }
    
        public function testCanGetSameInstanceTwiceWhenDisposingItFirst()
        {
            $pool = new WorkerPool();
            $worker1 = $pool->get();
            $pool->dispose($worker1);
            $worker2 = $pool->get();
    
            $this->assertCount(1, $pool);
            $this->assertSame($worker1, $worker2);
        }
    }

## 8.原型模式 

 [ 原型模式（Prototype）](https://laravel-china.org/docs/php-design-patterns/2018/Prototype/1492)
    
    

 
 
    
    
                    
              
        