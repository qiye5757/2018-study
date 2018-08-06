## PHP手册阅读（九）之类与对象

1. [简介](#intro)
2. [属性](#property)


### <span id="intro">简介</span>

+ PHP 对待对象的方式与引用和句柄相同，即每个变量都持有对象的引用，而不是整个对象的拷贝。
+ $this 是一个到主叫对象的引用（通常是该方法所从属的对象，**但如果是从第二个对象静态调用时也可能是另一个对象**）。

		
		<?php
		class A
		{
		    function foo()
		    {
		        if (isset($this)) {
		            echo '$this is defined (';
		            echo get_class($this);
		            echo ")\n";
		        } else {
		            echo "\$this is not defined.\n";
		        }
		    }
		}
		
		class B
		{
		    function bar()
		    {
		        // Note: the next line will issue a warning if E_STRICT is enabled.
		        A::foo();
		    }
		}
		
		$a = new A();
		$a->foo();
		
		// Note: the next line will issue a warning if E_STRICT is enabled.
		A::foo();
		$b = new B();
		$b->bar();
		
		// Note: the next line will issue a warning if E_STRICT is enabled.
		B::bar();
		?>  
		
		
		以上例程会输出：
		
		
		$this is defined (A)
		$this is not defined.
		$this is defined (B)
		$this is not defined.
 
+ new 使用

	+ 如果在 new 之后跟着的是一个包含有类名的字符串，则该类的一个实例被创建。

			$a = new a;
			$a = new a();
	+ **在类定义内部，可以用 new self 和 new parent 创建新对象**。 
	+ **把一个对象已经创建的实例赋给一个新变量时，新变量会访问同一个实例，就和用该对象赋值一样。此行为和给函数传递入实例时一样。**。
	+ **可以用克隆给一个已创建的对象建立一个新实例**。 
	+ PHP5.3新增 new static 
	+ PHP5.4引用了使用单个表达式来访问对象的属性和方法


+ 实例化对象的几种办法的区别

	+ new self  就是这个类，是代码段里面的这个类。
	+ new static 访问的是当前实例化的那个类
	+ new parent 访问的是继承的上级的对象

			<?php
	
			class test{
				public $name1 = 1;
				public function aa(){
					return new self;
				}
			
				public function bb(){
					return new static;
				}
			
				public function cc(){
					return new parent;
				}
			}
			
			
			class test2 extends test{
				public $name1 = 2;
			
				public function cc(){
					return new parent;
				}
			}
			
			
			$test1 = new test;
			echo $test1->name1;    //1
			
			$test2 = $test1->aa();
			echo $test2->name1;    //1 
			
			$test3 = $test1->bb();
			echo $test3->name1;    //1
			
			$test3 = $test1->cc();
			echo $test3->name1;   //error
			
			
			$test1 = new test2;
			echo $test1->name1;   //2
			
			$test2 = $test1->aa();
			echo $test2->name1;   //1
			
			$test3 = $test1->bb();
			echo $test3->name1;   //2 
			
			$test3 = $test1->cc();
			echo $test3->name1;   //1

+ 一个类中的属性和方法命名可以一样，使用哪个看使用的时候是否加括号
	
		<?php

		class test{
			public $name1 = 1;
		
			public function name1(){
				return 222;
			}
		}
		
		$test1 = new test;
		
		echo $test1->name1;  //1
		echo $test1->name1();   //222
+ 这一意味着不能使用使用匿名函数给类的属性赋值。但是可以先将属性赋值给一个变量，来访问该匿名函数

		<?php
		class Foo
		{
		    public $bar;
		    
		    public function __construct() {
		        $this->bar = function() {
		            return 42;
		        };
		    }
		
			public function bar(){
				return 222;
			}
		}
		
		$obj = new Foo();
		
		// as of PHP 5.3.0:
		$func = $obj->bar;
		echo $func(), PHP_EOL;
		
		// alternatively, as of PHP 7.0.0:
		echo ($obj->bar)(), PHP_EOL;
+ extends使用

	+ 一个类可以在声明中用 extends 关键字继承另一个类的方法和属性。PHP不支持多重继承，一个类只能继承一个基类。 
	+ 被继承的方法和属性可以通过用同样的名字重新声明被覆盖。
	+ 但是如果父类定义方法时使用了 final，则该方法不可被覆盖。
	+ 可以通过 parent:: 来访问被覆盖的方法或属性。 
	+ **当覆盖方法时，参数必须保持一致否则 PHP 将发出 E_STRICT 级别的错误信息。但构造函数例外，构造函数可在被覆盖时使用不同的参数**。

+ ::class使用

	+ 自 PHP 5.5 起，关键词 class 也可用于类名的解析。使用 ClassName::class 你可以获取一个字符串，包含了类 ClassName 的完全限定名称。这对使用了 命名空间 的类尤其有用。
	+ 类名的解析是在加载的时候就开始了，这意味着即使类没加载进来使用也不报错。

### <span id="property">属性</span>

+ 属性中的变量可以初始化，但是初始化的值必须是常数，这里的常数是指 PHP 脚本在编译阶段时就可以得到其值，而不依赖于运行时的信息才能求值。 
+ 跟 heredocs 不同，nowdocs 可在任何静态数据上下文中使用，包括属性声明。

### 类常量

+ 常量的值必须是一个定值，不能是变量，类属性，数学运算的结果或函数调用。
+ PHP5.3支持 nowdocs 和 heredocs
+ PHP5.6以上没有第一条的限制
+ PHP7支持定义类常量的关键字，public/private/protected

### 类自动加载  

	spl_autoload_register()

### 构造函数和析构函数

 PHP5新增析构函数

### 访问控制

 同一个类的对象即使不是同一个实例也可以互相访问对方的私有与受保护成员。这是由于在这些对象的内部具体实现的细节都是已知的。 

### 对象继承

 单继承

### ::操作符

 访问本类中的类常量和静态数据，以及父类的属性和方法

### static静态关键字

+ 声明类属性或方法为静态，就可以不实例化类而直接访问。静态属性不能通过一个类已实例化的对象来访问（但静态方法可以）。 
+ PHP5.6以后可以使用其他的来赋值静态类属性

### 抽象类

+ 抽象类不能被实例化
+ 抽象类如果有一个方法是抽象的，就必须为抽象类
+ 继承一个抽象类的时候必须定义抽象类中的所有抽象方法
+ 访问控制必须要和父类的一样或者更加宽松
+ **此外方法的调用方式必须匹配，即类型和所需参数数量必须一致。例如，子类定义了一个可选参数，而父类抽象方法的声明里没有，则两者的声明并无冲突。** 这也适用于 PHP 5.4 起的构造函数。在 PHP 5.4 之前的构造函数声明可以不一样的。 

### 对象接口

+ 接口是通过 interface 关键字来定义的，就像定义一个标准的类一样，但其中定义所有的方法都是空的。 
+ 接口中定义的所有方法都必须是公有，这是接口的特性。 
+ 类可以实现多个接口，用逗号来分隔多个接口的名称。 
+ 实现多个接口时，接口中的方法不能有重名
+ 接口也可以继承，通过使用 extends 操作符
+ **接口中也可以定义常量，但是不能被覆盖。即同一个类中的常量**

### Trait（PHP5.4以后支持）

+ Trait 是为类似 PHP 的单继承语言而准备的一种代码复用机制。
+ Trait 相当于横向扩展代码
+ 使用方法

		//定义trait
		Trait aa{
			
		}

		class test{
			use aa;
		}

+ 优先级：优先顺序是来自当前类的成员覆盖了 trait 的方法，而 trait 则覆盖了被继承的方法
+ 使用多个Trait 

	> use aa,bb,cc;

##### 特性

+ 支持定义抽象方法
+ 可以定义静态类静态方法
+ 可以定义属性：Trait 定义了一个属性后，类就不能定义同样名称的属性，否则会产生 fatal error。 有种情况例外：属性是兼容的（同样的访问可见度、初始默认值）。

##### 冲突性的解决

+ 如果两个 trait 都插入了一个同名的方法，如果没有明确解决冲突将会产生一个致命错误。 
+ 使用 `insteadof` 和 `as` 解决冲突


		<?php 

			trait A {
			    public function smallTalk() {
			        echo 'a';
			    }
			    public function bigTalk() {
			        echo 'A';
			    }
			}
			
			trait B {
			    public function smallTalk() {
			        echo 'b';
			    }
			    public function bigTalk() {
			        echo 'B';
			    }
			}
			
			class Talker {
			    use A, B {
			        B::smallTalk insteadof A;
			        A::bigTalk insteadof B;
			    }
			}
			
			class Aliased_Talker {
			    use A, B {
			        B::smallTalk insteadof A;
			        A::bigTalk insteadof B;
			        B::bigTalk as talk;
			    }
			}


+ 使用 as 语法还可以用来调整方法的访问控制。 

	>  use HelloWorld { sayHello as private myPrivateHello; }
	>  use HelloWorld { sayHello as protected; }

+ trait可以使用trait，就像class使用trait一样
	
		<?php

			trait test1{
			   public $aa = "111";
			
			   public function test11(){
			       echo 222;
			   }
			}
			
			trait test2{
			  use test1{
			     test1::test11 as public test1;
			  }
			}
			
			class test3{
			  use test2;
			}
			
			$test = new test3;
			$test->test1();
			echo $test->aa;

### 匿名类（PHP7开始支持）

	<?php
		$util->setLogger(new class {
		    public function log($msg)
		    {
		        echo $msg;
		    }
		}); 

+ 可以传递参数到匿名类的构造器，也可以扩展（extend）其他类、实现接口（implement interface），以及像其他普通的类一样使用 trait。
+ 匿名类被嵌套进普通 Class 后，不能访问这个外部类（Outer class）的 private（私有）、protected（受保护）方法或者属性。 为了访问外部类（Outer class）protected 属性或方法，匿名类可以 extend（扩展）此外部类。 为了使用外部类（Outer class）的 private 属性，必须通过构造器传进来
+ 声明的同一个匿名类，所创建的对象都是这个类的实例。
+ **注意，匿名类的名称是通过引擎赋予的，如下例所示。 由于实现的细节，不应该去依赖这个类名。**

### 重载

 在Java中的重载就是函数或者方法有相同的名称，但是参数列表不相同的情形，这样的同名不同参数的函数或者方法之间，互相称之为重载函数或者方法，这也牵涉到了多态。  

 PHP所提供的“重载”（overloading）是指动态地“创建”类属性和方法。我们是通过魔术方法（magic methods）来实现的。

+ 对属性的重载 `__set()`、 `__get()`、 `__isset()`、 `__unset()`
+ 对方法的重载 `__call()`、 `__callStatic()`

### PHP魔术方法

1. __construct():类的构造方法

	如果 PHP 5 在类中找不到 __construct() 函数并且也没有从父类继承一个的话，它就会尝试寻找旧式的构造函数，也就是和类同名的函数。因此唯一会产生兼容性问题的情况是：类中已有一个名为 __construct() 的方法却被用于其它用途时。 

	+ 自 PHP 5.3.3 起，在命名空间中，与类名同名的方法不再作为构造函数
	

2. __destory():类的析构方法

	+ 注意：析构函数不能带有任何参数。
	+ 析构函数即使在使用 exit() 终止脚本运行时也会被调用。
	+ 在析构函数中调用 exit() 将会中止其余关闭操作的运行。 

3. __call():在对象中调用一个不可访问方法时调用。

	> function __call(string $function_name, array $arguments)

4. __callStatic():用静态方式中调用一个不可访问方法时调用。

	> public function __call($name, $arguments) 

5. __set()：在给不可访问属性赋值时，会被调用。**对象中有用** 
	
	> public void __set ( string $name , mixed $value )

6. __get()：读取不可访问属性的值时，会被调用。 **对象中有用** 

	> public mixed __get ( string $name )
	
7. __isset()：当对不可访问属性调用 isset() 或 empty() 时， 会被调用。**对象中有用** 

	> public bool __isset ( string $name ) 

8. __unset()：当对不可访问属性调用 unset() 时， 会被调用。 **对象中有用** 

	> public void __unset ( string $name )

9. __sleep():当类序列化的时候会首先调用。此功能可以用于清理对象，并返回一个包含对象中所有应被序列化的变量名称的数组。

### Final 关键字 

+ PHP 5 新增了一个 final 关键字。如果父类中的方法被声明为 final，则子类无法覆盖该方法。如果一个类被声明为 final，则不能被继承。

+ PHP 5 新增了一个 final 关键字。如果父类中的方法被声明为 final，则子类无法覆盖该方法。如果一个类被声明为 final，则不能被继承。