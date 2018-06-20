## PHP手册阅读（八）之函数

1. [用户自定义函数](#myfunction)
2. [函数的参数](#argument)
3. [返回值](#return)
4. [可变函数](#changefunction)
5. [匿名函数](#anonymous)
### <span id="myfunction">用户自定义函数</span>

+ 任何有效的 PHP 代码都有可能出现在函数内部，**甚至包括其它函数和类定义**。
 
+ 有效的函数名以字母或下划线打头，后面跟字母，数字或下划线。

+ 函数定义不需要再使用之前，除了以下两种情况

	+ 函数定义在判断语句中
	+ 函数定义在函数中

+ PHP 中的所有函数和类都具有全局作用域，可以定义在一个函数之内而在之外调用，反之亦然。 
+ PHP 不支持函数重载，也不可能取消定义或者重定义已声明的函数。 
+ PHP 的函数支持可变数量的参数和默认参数。

	+ func\_num\_args()获取函数参数的数量
	+ func\_get\_arg()获取函数对应参数的值
	+ func\_get\_args()获取函数对应的参数列表

### <span id="argument">函数的参数</span>

 通过参数列表可以传递数据到函数，即以逗号作为分割符的表达式列表。参数是从左往右求值的。PHP支持：

+ 按值传递参数
+ 通过引用传递参数
+ 默认参数
+ 也支持可变长度参数列表

##### 通过引用传递参数

 如果想要函数的一个参数总是通过引用传递，可以在函数定义中该参数的前面加上符号 &

##### 使用默认参数

+ 除了标量以外，PHP还可以用数组和NULL作为默认参数

+ 默认值必须是常量表达式，不能是诸如变量，类成员，或者函数调用等。

+ **自 PHP 5 起，传引用的参数也可以有默认值。**

##### 类型声明

类型声明允许函数在调用时要求参数为特定类型。 如果给出的值类型不对，那么将会产生一个错误： 在PHP 5中，这将是一个可恢复的致命错误，而在PHP 7中将会抛出一个TypeError异常。 

 为了指定一个类型声明，类型应该加到参数名前。**这个声明可以通过将参数的默认值设为NULL来实现允许传递NULL**

+ Class/interface：参数必须为instanceof成功（PHP5）

		<?php
			class C {}
			class D extends C {}
			
			// This doesn't extend C.
			class E {}
			
			function f(C $c) {
			    echo get_class($c)."\n";
			}
			
			f(new C); //true
			f(new D); //true
			f(new E); //false
			?> 
+ self:类方法或者实例函数 PHP5
+ array:PHP 5.1.0 
+ callable：PHP 5.4.0 
+ bool：PHP 7.0.0 
+ float：PHP 7.0.0 
+ int：PHP 7.0.0 
+ string:PHP 7.0.0

##### 严格模式

 declear(strict_types = 1); 开启

+ 在严格模式中，只有一个与类型声明完全相符的变量才会被接受，否则将会抛出一个TypeError。 唯一的一个例外是可以将integer传给一个期望float的函数。 
+ **严格类型适用于在启用严格模式的文件内的函数调用，而不是在那个文件内声明的函数**

##### 可变数量的参数列表

+ 在PHP5.6以上，支持使用 `...` 符号来定义可变参数，可变参数会作为数组列表

 	<?php
		function sum(...$numbers) {
		    $acc = 0;
		    foreach ($numbers as $n) {
		        $acc += $n;
		    }
		    return $acc;
		}
		
		echo sum(1, 2, 3, 4);
		?>

+ 也可以使用 `...` 来压缩数组

 	<?php
		function add($a, $b) {
		    return $a + $b;
		}
		
		echo add(...[1, 2])."\n";
		
		$a = [1, 2];
		echo add(...$a);

+ 也可以在 `...` 之前定义参数，那么之前的参数则不会被匹配到

	<?php
		function add($a, ...$b) {
		    return $a + $b;
		}
		
		echo add(1, 2, 3);

+ 也可以给 `...` 参数加参数声明，则所有参数都应该是这个类型的。
+ 也可以在 `...` 前面加 `$`


### <span id="return">返回值</span>

+ 如果省略了 return，则返回值为 NULL。
+ 函数不能返回多个值，但可以通过返回一个数组来得到类似的效果。
+ **从函数返回一个引用，必须在函数声明和指派返回值给一个变量时都使用引用运算符 &**： 

		<?php
			function &returns_reference()
			{
			    return $someref;
			}
			
			$newref =& returns_reference();
+ php7开始给函数增加了函数声明

### <span id="changefunction">可变函数 </span>

+ 这意味着如果一个变量名后有圆括号，PHP 将寻找与变量的值同名的函数，并且尝试执行它。

+ 用可变函数的语法来调用一个对象的方法。

+ **当调用静态方法时，函数调用要比静态属性优先**。

		<?php
		class Foo
		{
		    static $variable = 'static property';
		    static function Variable()
		    {
		        echo 'Method Variable called';
		    }
		}
		
		echo Foo::$variable; // This prints 'static property'. It does need a $variable in this scope.
		$variable = "Variable";
		Foo::$variable();  // This calls $foo->Variable() reading $variable in this scope.

+ **PHP5.4以上，你可以存储 `callable` 存储到一个变量**
  
		<?php
			class Foo
			{
			    static function bar()
			    {
			        echo "bar\n";
			    }
			    function baz()
			    {
			        echo "baz\n";
			    }
			}
			
			$func = array("Foo", "bar");
			$func(); // prints "bar"
			$func = array(new Foo, "baz");
			$func(); // prints "baz"
			$func = "Foo::bar";
			$func(); // prints "bar" as of PHP 7.0.0; prior, it raised a fatal error

+ is_callable():检测参数是否为合法的可调用结构 
+ call\_user\_func(): 把第一个参数作为回调函数调用

### <span id="anonymous">匿名函数</span>

匿名函数（Anonymous functions），也叫闭包函数（closures），允许 临时创建一个没有指定名称的函数。

+ 匿名函数目前是通过 Closure 类来实现的。
+ **闭包函数也可以作为变量的值来使用。PHP 会自动把此种表达式转换成内置类 Closure 的对象实例。**
+ 闭包可以从父作用域中继承变量。 任何此类变量都应该用 use 语言结构传递进去。 PHP 7.1 起，不能传入此类变量： superglobals、 $this 或者和参数重名。 
+ 这些变量都必须在函数或类的头部声明。 从父作用域中继承变量与使用全局变量是不同的。全局变量存在于一个全局的范围，无论当前在执行的是哪个函数。**而 闭包的父作用域是定义该闭包的函数（不一定是调用它的函数）**。

##### 静态匿名函数

 从PHP5.4开始，在类中定义匿名函数，会自动将$this绑定到函数作用域。如果不起作用，则是定义了静态匿名函数。

+ 可以在闭包中使用 func\_num\_args()，func\_get\_arg() 和 func\_get\_args()。 
