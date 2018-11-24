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
    

 