# PHP手册阅读（十）之命名空间
1. [前言](#intro)
2. [命名规则](#rule)
3. [命名空间使用](#use)
4. [解析规则](#how)

### <span id="intro">前言</span>

 从PHP5.3开始引入了命名空间的概念，这个概念类似于文件系统，在不同文件夹下可以存在相同的文件，在相同文件夹下不能存储相同的文件。
 
 命名空间主要是解决以下问题：

1. 用户代码命名重复的问题
2. 用户代码命名过长的问题

### <span id="rule">命名规则</span>

#### 说明

虽然，命名空间是在什么地方都能定义的，但是只针对以下部分有效

+ 类（包含抽象类和Trait）
+ 接口
+ 函数
+ 常量

#### 规则

1. 命名空间使用 `namespace` 关键字进行定义，**而且必须定义到所有代码的最前面（除了declear以外）**

		<?php
		namespace test;


2. 子命名空间是以 `\` 进行分割的

		<?php 
	
		namespace test\test\test;

3. 可以在同一个文件中定义多个命名空间(并不推荐，如果实在需要使用则使用第二种)，共有两种定义方式

		######第一种方式#########
		<?php 
		namespace test1;

		//code

		namespace test2;
		
		//code
		
		#######第二种方式###########

		<?php 

		namespace test1{
			//code
		}

		namespace test2{
			//code
		}

4. \_\_NAMESPACE\_\_魔术常量：会返回当前的命名变量，如果在全局代码中会返回空字符串

		<?php

		namespace test;
		
		echo __NAMESPSCE__; //会返回test

5. 关键字 namespace 可用来显式访问当前命名空间或子命名空间中的元素。它等价于类中的 self 操作符。

6. 可以使用use导入或者引入`类`、`接口`、`命名空间`、 `函数（PHP5.6引入）`、`常量（PHP5.6引入）`， 使用 as 设置别名。

	+ 导入的时候必须为完全限定名称，所以不需要加前面的那个 `\`

	+ 导入不影响动态访问元素

	+ 使用的时候，导入操作只影响非限定名称和限定名称。完全限定名称由于是确定的，故不受导入的影响

	+ use定义必须在全局变量中定义或者跟着namespace一起定义，这是因为导入是在编译阶段完成的。

	+ **导入操作只影响非限定名称和限定名称。完全限定名称由于是确定的，故不受导入的影响**

	+ 从PHP 7.0开始，从同一个名称空间导入的类、函数和常量可以在单个use语句中组合在一起。

			<?php
	
			// Pre PHP 7 code
			use some\namespace\ClassA;
			use some\namespace\ClassB;
			use some\namespace\ClassC as C;
			
			use function some\namespace\fn_a;
			use function some\namespace\fn_b;
			use function some\namespace\fn_c;
			
			use const some\namespace\ConstA;
			use const some\namespace\ConstB;
			use const some\namespace\ConstC;
			
			// PHP 7+ code
			use some\namespace\{ClassA, ClassB, ClassC as C};
			use function some\namespace\{fn_a, fn_b, fn_c};
			use const some\namespace\{ConstA, ConstB, ConstC}; 

### <span id="rule">命名空间使用</span>

#### 基础使用

命名空间共有三种使用方式

+ 非限定名称，即不包含前缀的类名称（相当于文件系统中不带目录的文件名）。

	> 如果在 `test` 命名空间中使用，则会被解析为 `test\非限定名称`

	> 如果在非命名空间中间使用，则会解析为全局的

	> 如果在命名空间中使用，而且函数或者常量未定义，则会定义为全局的

+ 限定名称，即包含前缀的名称（相当于文件系统中的相对路径）

	> $test = new test\test();  //如果在命名空间中则会被解析为 `命名空间\test\test`。如果在全局空间中则会被解析为 `test\test`;

+ 完全限定名称，即包含了全局前缀操作符的名称（相当于文件系统中的绝对路径）

	> $test = new \test\test();

	> $test = new \test(); //这个是在命名空间中使用全局类的方法

+ 全局空间：如果没有定义任何命名空间，所有的类与函数的定义都是在全局空间，与 PHP 引入命名空间概念前一样。在名称前加上前缀 \ 表示该名称是全局空间中的名称，即使该名称位于其它的命名空间中时也是如此。

#### 常见问题

1. 动态访问元素，动态的类名称、函数名称或常量名称，必须使用完全限定名称。**动态访问完全限定名称，加不加最前面的`\`都可以**

2. **对于函数和常量来说，如果当前命名空间中不存在该函数或常量，PHP 会退而使用全局空间中的函数或常量。**

3. **对于类名称来说，类名称总是解析到当前命名空间中的名称。因此在访问系统内部或不包含在命名空间中的类名称时，必须使用完全限定名称.**

### <span id="how">解析规则</span>

首先先理解四个概念：

1. 非限定名称 `Unqualified name`，如 `test()`
2. 限定名称 `Qualified name`, 如 `test\test()`
3. 完全限定名称 `Fully qualified name`, 如 `\test\test()`
4. 相对限定名称 `Relative name`，如 `namespace\test\test()`

规则如下：

1. 完全限定名称在还没导入命名空间之前解析，如 `new \test\test()` 会被解析为 `test\test()`
2. 相对限定名称总是将 `namespace` 关键字替换为当前的命名空间，如 `new namespace\test()`在 `A\B`命名空间中会被解析为 `new A\B\test()`，而在`全局`命名空间中会被解析为 `new test()`
3. 对所有的限定名称来说,根据当前的`namespace/class导入表`在编译时进行转换。
4. 在命名空间内部，所有的没有根据`namespace/class导入表`转换的限定名称均会在其前面加上当前的命名空间名称。
5. 对所有的非限定名称来说，各自名称是根据各自的导入表来解析的。即类名称是根据`namespace/class导入表`来解析的，常量是根据`常量导入表`来解析的，函数是根据 `函数导入表`来解析的
6. 在命名空间内部，所有的没有根据`namespace/class导入表`转换的限定名称均会在其前面加上当前的命名空间名称。
7. 在命名空间内部，对非限定名称的`类`和`常量`如果在当前命名空间中没有定义，却在全局命名空间中定义，则会在运行时解析。