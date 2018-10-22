# PHP常见概念之变量详解

 最近看然之OA的源码，对`static`静态变量和`global`的用法理解的不是太深刻，（虽然说前一段时间跟着手册过了一遍，但是没有像然之OA中这样用过），所以结合着手册和别人的博文对变量进行总结一下。

 首先我们可以先简单了解一下，PHP的底层结构，对我们理解PHP变量的概念会帮助的。[详情请点](http://www.php-internals.com/book/?p=chapt03/03-01-00-variables-structure)

### 1. 变量底层存储结构（当然这只是PHP5的）

 	typedef struct _zval_struct zval;
	...
	struct _zval_struct {
	    /* Variable information */
	    zvalue_value value;         //变量的值
	    zend_uint refcount__gc;     //引用计数
	    zend_uchar type;    	    //变量的类型
	    zend_uchar is_ref__gc;      //是否为引用
	;

PHP使用这个结构来存储变量的所有数据，包括系统定义的以及用户定义的。

#### 1.1 变量类型

 zval结构体的type字段就是实现弱类型最关键的字段了，type的值可以为： `IS_NULL`、`IS_BOOL`、`IS_LONG`、`IS_DOUBLE`、`IS_STRING`、`IS_ARRAY`、`IS_OBJECT`和`IS_RESOURCE` 之一。 除此之外，和他们定义在一起的类型还有`IS_CONSTANT`和`IS_CONSTANT_ARRAY`。

#### 1.2 变量值

前面提到变量的值存储在zvalue_value联合体中，结构体定义如下：

	typedef union _zvalue_value {
	    long lval;                  /* long value */
	    double dval;                /* double value */
	    struct {
	        char *val;
	        int len;				
	    } str;						/* string value*/
	    HashTable *ht;              /* hash table value */
	    zend_object_value obj;      /* object value*/ 
	} zvalue_value;

### 2. 变量命名规则

 对变量的命名只需要记住以下三点就可以了（大多数语言都类似）

+ PHP 中的变量用一个`美元符号`后面跟`变量名`来表示。变量名是`区分大小写`的。 
+ 一个有效的变量名由`字母`或者`下划线`开头，后面跟上任意数量的`字母`，`数字`，或者`下划线`。和C语言一样
+ `$this` 是一个特殊的变量，它不能被赋值。 

 想要知道如何更好的命名，推荐一本书籍 `代码整洁之道`（仅供参考，可以知道编写代码需要注意的点），当然具体的标准还是要遵循团队的标准，或者框架的标准，如[Thinkphp5命名规范](https://www.kancloud.cn/manual/thinkphp5/118007)、[Laravel 项目开发规范 ](https://laravel-china.org/docs/laravel-specification/5.5)等等。

### 3.变量的作用域

PHP中变量大致分为4种

+ `全局变量`: 在函数外部声明的变量
+ `局部变量`: 定义在`函数`或者`类的方法`中
+ `类变量`：在类中声明的变量，普通变量或者静态变量
+ `预定义变量`：对于全部脚本而言，PHP 提供了大量的预定义变量。这些变量将所有的外部变量表示成内建环境变量，并且将错误信息表示成返回头。

变量相关的关键字有以下两种

+ `static`:声明为静态变量
+ `global`:声明为全局变量

#### 3.1 全局变量的作用域

 全局变量本身就是`静态存储方式`,所有的全局变量都相当于`其他语言中的静态变量`。**全局作用域中是不能使用局部变量**

##### 使用static关键字（用的不多）

 使用`static`定义`全局变量`，我们称之为`静态全局变量`。使用`静态全局变量`意味着该全局变量只初始化一次，且会在程序运行执行前先执行。如下

    <?php

	 static $test = 1;
	
	 var_dump($test);  //3
	
	 static $test = 3;
	
	 var_dump($test);  //3

 如果一个变量既声明为全局变量，又声明为静态全局变量呢？同一个变量，程序只会在第一次执行到`声明静态全局变量`会初始化该全局变量，再次声明，则被视为赋值操作，在程序运行执行前执行。如下

	<?php

	 static $test = 1;
	
	 var_dump($test);   // 3
	
	 $test = 2;
	
	 var_dump($test);   // 2
	
	 static $test = 3;
	
	 var_dump($test);   // 2
	
	 var_dump($test);   // 2
	

#### 3.2 局部变量的作用域

 如果定义了一个函数或者类定义了一个方法，在该方法中定义了一个变量，则该变量被称之为`局部变量`。在C语言中，在函数中可以使用全局变量，这是因为C语言为静态语言，编译好了再执行。在PHP这种解释型语言中如果像C语言一样，会有很大的风险的。所以在PHP中，局部变量的作用域就是在函数内部，`局部变量不能使用全局变量，全局变量不能使用局部变量`。

##### 使用`global`关键字
 
 按理说`局部变量`是`不能`使用`全局变量`，但是可以使用`global`关键字来声明`全局变量`，就可以在`局部变量`中使用全局变量（如果本身没有这个全局变量，就相当于声明了一个全局变量）
		
##### 使用`static`关键字

 **局部变量在函数或者方法中声明，用完就释放。**可以使用`static`关键字声明为静态变量，那么该变量在函数或者方法执行完成后，该变量会常驻内存，直到该次请求完毕之后才会释放，再次执行该函数，会直接从内存中获取值。（只会在第一次执行的时候初始化）

##### 作为函数的参数

 作为函数的参数，如果不是以引用形式传递的话，则每次执行函数的时候，该函数的参数作为局部变量，用完就释放。如果是以引用形式传递参数的话，[请点击](#references)

#### 3.3 类变量

 可以参考

#### 3.4 预定义变量

 预定义变量中的超全局变量可以在代码的任何地方访问。

### 4 预定义变量
 
 对于全部脚本而言，PHP 提供了大量的预定义变量。这些变量将所有的外部变量表示成内建环境变量，并且将错误信息表示成返回头。

#### 4.1 `register_globals`选项

 本特性已自 PHP 5.3.0 起废弃并将自 PHP 5.4.0 起移除。设置为`ON`，则可以将EGPCS (Environment, GET, POST, Cookie, Server)的参数作为全局变量，但是这样会很不安全，所以已经不用这个了。

#### 4.2 什么为外部变量？

 字面意思就是来自于PHP外部的变量，如HTML表单的`GET`和`POST`参数，Http cookie，以及文件，环境变量等等。

#### 4.3 超全局变量

 该超全局变量为PHP 4.1引入的。如4.1介绍的那样，在PHP4.1版本之前`register_globals`选项设置为`On`,则可以在PHP全局变量中使用外部变量。（即将外部变量作为全局变量使用，如get传值`a=1`，在PHP中`$a`作为全局变量且值为1）超全局变量主要分为9个，分别为

+ `$GLOBALS`
+ `$_SERVER`
+ `$_GET`
+ `$_POST`
+ `$_FILES`
+ `$_COOKIE`
+ `$_SESSION`
+ `$_REQUEST`
+ `$_ENV`

在默认情况下，所有的超全局变量都是可以使用的，但是有一部分指令会影响该可用性。

##### $GLOBAL（在PHP中总是可用的）

 该数组中包含全局作用域中的所有变量，变量的名称作为该数组的键。这意味着在函数或者方法中，可以不使用`global`关键字来访问全局变量。

##### $_SERVER（PHP5.4移除了`$HTTP_SERVER_VARS`,这个变量与$_SERVER一样，但是不是超全局变量）

 $_SERVER是一个包含了诸如头信息(header)、路径(path)、以及脚本位置(script locations)等等信息的数组。该数组是由服务器创建的，所以不同的服务器下的数组的值会有一些差异。常见的使用的参数如下

+ `PHP_SELF`：当前执行的脚本名称
+ `SERVER_NAME`:当前运行脚本所在服务器的主机名。如果该脚本运行于虚拟主机中，则该名称是由那个虚拟主机所设置的值决定的。
+ `DOCUMENT_ROOT`：当前运行脚本所在文档的根目录。

##### $_GET（PHP5.4移除了`$HTTP_GET_VARS`）

 通过 URL 参数传递给当前脚本的变量的数组。GET 是通过 `urldecode()` 传递的。 

##### $_POST（PHP5.4移除了`$HTTP_POST_VARS`）

 当 HTTP POST 请求的 `Content-Type` 是 `application/x-www-form-urlencoded` 或 `multipart/form-data` 时，会将变量以关联数组形式传入当前脚本。

##### $_FILES（PHP5.4移除了`$HTTP_POST_FILES`）

 通过 HTTP POST 方式上传到当前脚本的文件的数组。

##### $_REQUEST(HTTP Request 变量)

 默认情况下包括了`$_GET`、`$_POST`、`$_COOKIE` 的数组。

+ php.ini中`request_order`指令，这个指令指定`$_REQUEST`全局数组包含的值。(G,P,C,E & S)分别为(GET,POST,COOKIE,ENV,SERVER)。且会从左往右将数据放进`$_REQUEST`全局数组中。

	> request_order = "GP";  //意味着$_REQUEST数组中只包含着GET，和POST中的值，且POST值会覆盖GET的值。
	
+ php.ini中的`variables_order`指令执行在一开始的时候那些外部变量会注册到PHP全局变量中。**如果赋值为空，则值为EGPCS**

	>  variables_order = 'GPCS' //PHP会将GET、POST、COOKIE、SERVER注册为全局数组

+ import\_request\_variables — 将 GET/POST/Cookie 变量导入到全局作用域中

##### $_COOKIE(已弃用$HTTP_COOKIE_VARS)

 通过 HTTP Cookies 方式传递给当前脚本的变量的数组。关于cookie可以参考以下两篇文章

+ [PHP常见技术（六）之cookie详解](https://www.cnblogs.com/qiye5757/p/9762911.html)
+ [cookie详解](https://segmentfault.com/a/1190000006156098)

##### $_SESSION(已弃用$HTTP_SESSION_VARS)







 

 

 

 