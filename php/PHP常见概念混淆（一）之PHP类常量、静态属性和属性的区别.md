## PHP常见概念混淆（一）之PHP类常量、静态属性和属性的区别

 最近在看手册的时候发现PHP有好些个坑，一不注意就会掉进去，边看边将这些容易混淆的内容记载下来。

 > tips:看手册的时候最好中英文对照着看，因为英文手册上有好些个中文手册没有的东西（最新的PHP）

1. PHP5.3 支持用一个变量调用类
2. PHP5.6 支持用一个表达式赋值 `PHP类常量` 和 `PHP静态属性`
3. PHP7.1 支持对 `PHP类常量` 增加访问控制

### 简介

+ PHP类常量：定义方式和常量一样
+ PHP属性：定义方式和变量一样
+ PHP静态属性：可PHP属性的定义一样，加了一个static

### PHP属性

+ 使用 `->` 访问的变量
+ 子类可以覆盖父类的变量
+ 变量可以随时修改
+ 在类中使用 `$this` 访问

### PHP类常量

+ 使用 `::` 访问类常量，可以通过 `类` 和 `对象` 获取类常量.

		<?php
	
		class test{
			const AAAA = "BBB";
		}
		
		$test = new test;
		
		echo test::AAAA;  //BBB
		echo $test::AAAA;   //BBB

+ 子类可以覆盖父类的类常量

		<?php
	
		class test{
			const AAAA = "BBB";
		}
		
		class test2 extends test{
			const AAAA = "ccc";
		
			public function gettest(){
				return parent::AAAA;
			}
		}	
		$test = new test2;
		
		echo test::AAAA;   //ccc
		echo test2::AAAA;  //ccc
		echo $test::AAAA;  //BBB
		echo $test->gettest();	//BBB

+ 类常量一旦定义了无法修改
+ 在类中使用 `self::类常量` 访问

### PHP静态属性

+  使用 `::` 访问静态属性，可以通过 `类` 和 `对象` 获取静态属性.

		<?php
	
		class test{
			public static $AAAA = "BBB";
		}
		
		
		$test = new test;
		
		echo test::$AAAA;    //BBB
		echo $test::$AAAA;	 //BBB

+ 子类可以覆盖父类的静态属性

		<?php
	
		class test{
			public static $AAAA = "BBB";
		}
		
		class test2 extends test{
			public static $AAAA = "CCC";
		}
		$test = new test2;
		
		echo test2::$AAAA;   //CCC
		echo $test::$AAAA;	 //CCC

+ 类常量定义了可以随时进行修改
+ 再类中使用 `self::$test` 进行访问

 