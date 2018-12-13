# PHP结构型设计模式

## 参考

+ [设计模式](http://c.biancheng.net/view/1357.html)
+ [PHP 设计模式全集 2018 ](https://laravel-china.org/docs/php-design-patterns/2018/)

## 什么是结构型是设计模式

 结构型模式讲的是如何将类和对象按照某种布局组成更大的结构。它分为类结构型模式和对象结构型模式，其中类结构型模式采用继承机制来组织接口和类，其中对象结构型模式采用组合和聚合来组合对象。由于组合和聚合比继承的耦合性低，满足“合成复用原则”，所以对象结构型模式比类结构型模式具有更大的灵活性。
 
## 1.适配器设计模式（Adapter模式）

 在现实生活中有很多类似的例子，如用直流电的笔记本电脑接交流电源时需要一个电源适配器，用计算机访问照相机的 SD 内存卡时需要一个读卡器等。
 
### 为什么需要适配器设计模式？
 
 在软件设计中也可能出现：需要开发的具有某种业务功能的组件在现有的组件库中已经存在，但它们与当前系统的接口规范不兼容，如果重新开发这些组件成本又很高，这时用适配器模式能很好地解决这些问题。
 
### 什么是适配器设计模式？

适配器模式（Adapter）包含以下主要角色。

+ 目标（Target）接口：当前系统业务所期待的接口，它可以是抽象类或接口。
+ 适配者（Adaptee）类：它是被访问和适配的现存组件库中的组件接口。
+ 适配器（Adapter）类：它是一个转换器，通过继承或引用适配者的对象，把适配者接口转换成目标接口，让客户按目标接口的格式访问适配者。

即现在想要在 `目标接口` 中调用 `适配者类` 中的某些方法，所以定义一个 `适配器类` 继承 `适配者类`,实现`目标接口`。

### 分类

适配器模式分为类结构型模式和对象结构型模式两种，前者类之间的耦合度比后者高，且要求程序员了解现有组件库中的相关组件的内部结构，所以应用相对较少些。

### 实例

#### 实例一：使用类结构型模式

##### 第一步：首先需要一个适配者类（已经存在的）


    class Adaptee{
        
        public function usedMethod(){
            echo "this is Adaptee method";
        }
    }

##### 第二步：现在想要开发一个组件（源系统中存在，但是不符合规范）

    interface Target{
        public function request();
    }
    
##### 第三步：定义一个适配器类

    class Adapter extends Adaptee implements Target{
        public function request(){
            $this->usedMethod();
        }
    }

##### 第四步：使用

    $adapter = new Adapter();
    $adapter->request();
    
#### 实例二：使用对象结构型模式

前两个条件与上面相同

##### 第三步：定义一个适配器类

    class Adapter implements Target{
        public $adaptee;
        
        public function __construct(Adaptee $adaptee){
            $this->adaptee = $adaptee;
        } 
        
        public function request(){
            $this->adaptee->usedMethod();
        }
    }
    
##### 第四步：使用

    $adapter = new Adapter();
    $adapter->request(new Adaptee());
    
### 模式的应用场景

适配器模式（Adapter）通常适用于以下场景。
+ 以前开发的系统存在满足新系统功能需求的类，但其接口同新系统的接口不一致。
+ 使用第三方提供的组件，但组件接口定义和自己要求的接口定义不同。

## 2. 代理模式(Proxy)

 在有些情况下，一个客户不能或者不想直接访问另一个对象，这时需要找一个中介帮忙完成某项任务，这个中介就是代理对象。例如，购买火车票不一定要去火车站买，可以通过 12306 网站或者去火车票代售点买。又如找女朋友、找保姆、找工作等都可以通过找中介完成。
 
### 为什么需要代理模式？

 在软件设计中，如果由于某些原因（如安全）不想访问真实对象的话，可以使用代理模式。
 
### 什么是代理模式？

代理模式主要分为以下几个角色：

+ 抽象主题（Subject）类：通过接口或抽象类声明真实主题和代理对象实现的业务方法。
+ 真实主题（Real Subject）类：实现了抽象主题中的具体业务，是代理对象所代表的真实对象，是最终要引用的对象。
+ 代理（Proxy）类：提供了与真实主题相同的接口，其内部含有对真实主题的引用，它可以访问、控制或扩展真实主题的功能。
    
### 实例

#### 第一步：首先存在一个抽象主题接口

    Interface Subject{
        public function request();
    }

#### 第二步：存在一个真实主题

    class RealSubject implements Subject{
        public function request(){
            echo "这个是真实主题\n";
        }
    }

#### 第三步：使用一个代理模式

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
    
    
#### 第四步：测试

    $testProxy = new Proxy();
    $testProxy->request();d
    
### 模式的应用场景

+ 远程代理，这种方式通常是为了隐藏目标对象存在于不同地址空间的事实，方便客户端访问。例如，用户申请某些网盘空间时，会在用户的文件系统中建立一个虚拟的硬盘，用户访问虚拟硬盘时实际访问的是网盘空间。
+ 虚拟代理，这种方式通常用于要创建的目标对象开销很大时。例如，下载一幅很大的图像需要很长时间，因某种计算比较复杂而短时间无法完成，这时可以先用小比例的虚拟代理替换真实的对象，消除用户对服务器慢的感觉。
+ 安全代理，这种方式通常用于控制不同种类客户对真实对象的访问权限。
+ 智能指引，主要用于调用目标对象时，代理附加一些额外的处理功能。例如，增加计算真实对象的引用次数的功能，这样当该对象没有被引用时，就可以自动释放它。
+ 延迟加载，指为了提高系统的性能，延迟对目标的加载。例如，Hibernate 中就存在属性的延迟加载和关联表的延时加载。


## 3. 桥梁模式（Bridge）

 引用[设计模式（八）桥梁模式（Bridge）](https://blog.csdn.net/xingjiarong/article/details/50132727)，这篇文章中的一个例子：
 
 现需要提供大中小3种型号的画笔，能够绘制5种不同颜色，如果使用蜡笔，我们需要准备3*5=15支蜡笔，也就是说必须准备15个具体的蜡笔类。而如果使用毛笔的话，只需要3种型号的毛笔，外加5个颜料盒，用3+5=8个类就可以实现15支蜡笔的功能。实际上，**蜡笔和毛笔的关键一个区别就在于笔和颜色是否能够分离**。
 
### 为什么需要Bridge模式？

 一个类中有多个纬度的变化，那可以使用Bridge模式对该类进行解耦，即将多个纬度进行抽象。所以桥梁模式的用意是“将抽象化与实现化脱耦，使得二者可以独立地变化。” 
 
### 什么是Bridge模式？

桥接（Bridge）模式包含以下主要角色。
+ 抽象化（Abstraction）角色：定义抽象类，并包含一个对实现化对象的引用。
+ 扩展抽象化（Refined Abstraction）角色：是抽象化角色的子类，实现父类中的业务方法，并通过组合关系调用实现化角色中的业务方法。
+ 实现化（Implementor）角色：定义实现化角色的接口，供扩展抽象化角色调用。
+ 具体实现化（Concrete Implementor）角色：给出实现化角色接口的具体实现。

### 实例

 可以实现一个包含颜色的形状接口。

#### 第一步：定义一个颜色接口（Implementor角色）。定义一个形状抽象类（Abstraction角色），其中包含一个颜色接口的引用。

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
    
#### 第二步：实现具体化实现角色（Concrete Implementor）


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
    
#### 第三步：实现扩展抽象化（Refined Abstraction）角色

    class Square extends Shape{
        public function draw(){
            echo "i am " . $this->color->showColor() . " square\n";
        }
    }
    
    
#### 第四步：测试

    $redSquare = new Square(new Red());
    $redSquare->draw();
    $greenSquare = new Square(new Green());
    $greenSquare->draw();
    
## 4.装饰模式（Decorator）

 在现实生活中，常常需要对现有产品增加新的功能或美化其外观，如房子装修、相片加相框等。
 
### 为什么需要装饰模式？
    
 在软件开发过程中，有时想用一些现存的组件。这些组件可能只是完成了一些核心功能。但在不改变其结构的情况下，可以动态地扩展其功能。所有这些都可以釆用装饰模式来实现。
 
### 什么是装饰模式？

 装饰（Decorator）模式的定义：指在不改变现有对象结构的情况下，动态地给该对象增加一些职责（即增加其额外功能）的模式，它属于对象结构型模式。
 
装饰模式主要包含以下角色。

+ 抽象构件（Component）角色：定义一个抽象接口以规范准备接收附加责任的对象。
+ 具体构件（Concrete Component）角色：实现抽象构件，通过装饰角色为其添加一些职责。
+ 抽象装饰（Decorator）角色：继承抽象构件，并包含具体构件的实例，可以通过其子类扩展具体构件的功能。
+ 具体装饰（ConcreteDecorator）角色：实现抽象装饰的相关方法，并给具体构件对象添加附加的责任。

### 实例

比如现在有一个输出字符串接口，现在只能输出原有字符串，现在想要增加输出JSON，XML等字符串的功能。

#### 第一步：先定义系统中已经存在的构件

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

#### 第二步：定义一个抽象装饰角色

    abstract class Decorator implements Component
    {
        protected $component;
    
        public function __construct(Component $component)
        {
            $this->component = $component;
        }
    }

#### 第三步：定义具体的装饰类

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

#### 第四步：测试

    $component = new ConcreteComponent(["1", "2"]);
    var_dump($component->renderData());
    
    $jsonComponent = new JsonComponent($component);
    var_dump($jsonComponent->renderData());
    
    $xmlComponent = new XmlComponent($component);
    var_dump($xmlComponent->renderData());


 
