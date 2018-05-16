## PHP手册阅读（四）之变量阅读

### 基础

1. 一个有效的变量名由字母或者下划线开头，后面跟上任意数量的字母，数字，或者下划线。

	+ 在此所说的字母是 a-z，A-Z，以及 ASCII 字符从 127 到 255
	+ $this 是一个特殊的变量，它不能被赋值
	+ 以下代码结构会进入全局命名空间：

		+ functions（函数）
		+ classes（类）
		+ interfaces（接口）
		+ constants（常量，并非类常量）
		+ 在函数／方法之外定义的变量

2. 只有有名字的变量才可以引用赋值。
3. register_globals 最好不要使用，在PHP5.4已移除
### 预定义变量
	
+ 以前是预定义数组，自 PHP 5.0.0 起, 用 register_long_arrays 设置选项可禁用 长类型的 PHP 预定义变量数组。

+ 超级全局变量不能被用作函数或类方法中的可变变量。

+ 尽管超全局变量和 HTTP_*_VARS 同时存在，但是它们并不是同一个变量，所以改变其中一个的值并不会对另一个产生影响。

+ PHP自动会将 `.` 转换为 `_`

		example.com/page.php?chuck.norris=nevercries
		必须使用echo $_GET['chuck_norris'];

### 变量范围

1. 函数不能使用外部的变量
2. global
	+ 使用 `global` 在函数中使用全局变量
	+ 使用 `$GLOBALS` 全局变量在函数中使用全局变量
	+ 大多数的预定义变量并不 "super"，它们需要用 'global' 关键字来使它们在函数的本地区域中有效
	+ 全局变量 在任何范围内都有效，它们并不需要 'global' 声明。Superglobals 是在 PHP 4.1.0 引入的。
	+ 在函数之外使用 global 关键字不算错。可以用于在一个函数之内包含文件时。

3. 静态变量(在递归时使用最好)
	
 	例子如下

		<?php
		function test()
		{
	    	static $a = 0;
	   	 	echo $a;
	   	 	$a++;
		}
		?>
		现在，变量 $a 仅在第一次调用 test() 函数时被初始化，之后每次调用 test() 函数都会输出 $a 的值并加一

	如果在声明中用表达式的结果对其赋值会导致解析错误。  
	
	静态声明是在编译时解析的

4. 全局和静态变量的引用

	在 Zend 引擎 1 代，它驱动了 PHP4，对于变量的 static 和 global 定义是以引用的方式实现的。例如，在一个函数域内部用 global 语句导入的一个真正的全局变量实际上是建立了一个到全局变量的引用。这有可能导致预料之外的行为.即：

	**引用赋值给一个静态变量或者全局变量，没有生效**

### 可变变量

 在数组中，
	 
	${$a[1]} 将$a[1]作为一个变量   ####默认使用这一种
	${$a}[1] 将$$a 作为一个变量并取出该变量中索引为 [1] 的值 
 
 在对象中，
	
+ 对于 $foo->$bar 表达式，则会在本地范围来解析 $bar 并且其值将被用于 $foo 的属性名。对于 $bar 是数组单元时也是一样。
+ $foo->{$arr}[1]表达式为先获取对象中的属性，再获取属性中对应的值

### PHP之外的变量

##### 获取表单内容（GET和POST）

	<?php
	// 自 PHP 4.1.0 起可用
	   echo $_POST['username'];
	   echo $_REQUEST['username'];
	   
	   import_request_variables('p', 'p_');
	   echo $p_username;
	
	// 自 PHP 5.0.0 起，这些长格式的预定义变量
	// 可用 register_long_arrays 指令关闭。
	
	   echo $HTTP_POST_VARS['username'];
	
	// 如果 PHP 指令 register_globals = on 时可用。不过自
	// PHP 4.2.0 起默认值为 register_globals = off。
	// 不提倡使用/依赖此种方法。
	
	   echo $username;
	?>

+ 变量名中的点和空格被转换成下划线。例如 `<input name="a.b" />` 变成了 `$_REQUEST["a_b"]`。
+ magic_quotes_gpc 配置指令影响到 Get，Post 和 Cookie 的值。如果打开，值 (It's "PHP!") 会自动转换成 (It\'s \"PHP!\")。十多年前对数据库的插入需要如此转义，如今已经过时了，应该关闭。
	
		