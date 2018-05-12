## PHP手册阅读（三）之数据类型

1. [简介](#introduction)
2. [boolean类型](#boolean)
3. [integer整型](#int)
4. [float类型](#float)
5. [string字符串](#string)
6. [Array数组](#array)
7. [Object对象](#object)
8. [null空类型](#null)
9. [Callback / Callable 类型](#callable)
10. [类型转换的判别](#change-type)

### <span id = "introduction">简介</span>

1. PHP有9种数据类型 
2. 标量：`integer`、 `boolean` 、`string`、 `float`
3. 复合：`array` 、`object` 、`callable`
4. 资源：`resource`、 `NULL`

5. 为了在手册中阅读，新增了 `mixed`、 `callback`、 `number`、 `array|object`、 `void`、 `$...`
6. var_dump()：打印变量的相关信息
7. gettype()：获取一个变量的数据类型（返回值除了callable的其他），判断某个变量的类型请用下面的这个is
8. settype()：设置一个变量为某种类型（除了callable和resource都可以）
9. is_int()：判断一个变量是否为某一种类型（九种都可以）

### <span id = "boolean">boolean布尔型</span>

1. 这是最简单的一种数据类型。值可以为 `true` 或 `false`，这两个值为常量，不区分大小写。

        $test = true;

2. 通常 **运算符** 所返回的 **boolean** 值结果会返回给控制流程
3. **强制转换**：可以使用 `(bool)` 或者 `(boolean)` 或者 `boolval()函数`来强制转换获取boolean值
4. **隐形转换**：当运算符、函数或者流程控制需要一个boolean参数时，会自动转换

5. 自动转换规则（以下都会转为false，其他值都为true）

	+ boolean类型本身
	+ 整型值：0
	+ 浮点型：0.0（零）
	+ 空字符串以及字符串	`'0'`
	+ 不包含任何元素的数组
	+ 特殊类型NULL（包括尚未赋值的变量）
	+ 从空标记生成的 `SimpleXML` 对象

### <span id="int">integer整型</span>

1. 整型类型可以用十进制、十六进制、八进制或者二进制（***二进制在PHP5.4版本开始可以使用***），前面可以加上可选的符号（`+` 或者 `-`）

	+ 使用八进制开头必须加 `0`
	+ 使用十六进制开头必须加 `0x`
	+ 使用二进制开头必须加 `0b`

2. 整型语法的结构形式为

	+ 十进制： [1-9][0-9]*|0
	+ 十六进制： 0(xX)[0-9a-fA-F]+
	+ 八进制： 0[0-7]+
	+ 二进制： 0b[01]+
	+ 整型: [+-]?十进制|[+-]?十六机制|	[+-]?八进制|	[+-]?二进制
3. 整型的字长是和平台相关的

	+ 在32位机器上，最大值通常为 `20亿`
	+ 在64位机器上，最大值为 `9E18`,除了在window上的PHP7总是32位的

4. **PHP不支持无符号的整型**
5. int整型相关的常量

	+ PHP\_INT\_SIZE：获取常量的字长。4为32位，8为64位
	+ PHP\_INT\_MAX：从5.0.5版本起，可以获取整型的最大值
	+ PHP\_INT\_MIX：从7.0.0版本起，可以获取整型的最小值

6. **在PHP7之前，如果使用8进制的整型的时候使用了 `8`或者`9`是被忽略的。从PHP7版本起，会报一个语法错误**
7. **如果给定了一个值超过了整型的范围会转为 `float类型`，同样的，如果运算结果超过了整型的范围也会转为 `float类型`**
8. PHP没有整除的运算符，如果想实现整除效果，可以强制转换(结果为整型)或者使用 `round()`等函数进行运算（结果为float类型）
9. **强制转换：**可以使用 `(int)` 或者 `(integer)` 或者 `intval()函数`等方法进行转换
10. **隐形转换：**当运算符、函数或者流程控制需要一个int参数时，会自动转换		
11. 转换规则
	+ resource类型：结果会是PHP运行时为该资源分配的唯一资源号
	+ boolean类型：FALSE返回0，TRUE返回1
	+ float类型：向下取整，**如果超出了整型范围，结果为未定义，而且没有任何通知或者报错**
	+ ***自PHP7起，NaN和Infinity在转换位integer类型时，不再是未定义或者依赖于平台，都为0***	
	+ ***不要将未知的分数强制转为为integer类型，有时候会导致不可预知的错误*	**
	+ string类型：[字符串转换为数值](#string-to-number)
	+ NULL类型：0
	+ 其他类型：不可以转换

### <span id="float">float 浮点数</span>

 浮点数可以用以下任何一种方法表示
	
	$a = 3.1415926;
	$b = 2.2e13
	$c = 1.2E-10

+ 浮点数的字长和平台有关，尽管通常最大值是 1.8e308 并具有 14 位十进制数字的精度（64 位 IEEE 格式）。
+ PHP通常使用IEEE 754双精度格式，则由于取整而导致的最大相对误差为 1.11e-16。非基本数学运算可能会给出更大误差，并且要考虑到进行复合运算时的误差传递。注意事项:
	+ 永远不要相信浮点数结果精确到最后一位
	+ 永远不要比较两个浮点数是否一致

+ 转换浮点数
	+ string类型：[字符串装换为数值](#string-to-number)
	+ 其他类型：先转换为整型在转换为浮点
	+ PHP5以上：将对象转换为浮点数，会发出一条E_NOTICE错误

+ 两个浮点数进行比较
 
 	虽然浮点数由于精度的问题，比较起来有问题。但是总有迂回的方式，即**使用一个仅比该数值大一丁点的最小误差值，该值也被称为最小误差值，是计算中所能接受的最小误差值**

+ `NAN`

	 某些数学运算会产生一个有常量 `NAN` 所代表的结果。此结果代表着一个在浮点数中未定义或者不可描述的值，**任何拿此值和其他任何值（除了TRUE）去比较都是true**。  
	 由于 `NAN` 代表着任何不同值，不应该拿 `NAN` 和其他任何值去比较，包括其自身，应该使用 `is_nan()` 来判断

### <span id="string">string 字符串</span>

#### 什么是字符串？
 一个字符串 string 就是由一系列的字符组成，其中每个字符等同于一个字节。这意味着 PHP 只能支持 256 的字符集，因此不支持 Unicode 。 `string 最大可以达到 2GB`

#### 语法

1. 单字符

	+ 只能解析 `\\` 和 `\'` 这两个字符

2. 双引号

	+ 对变量进行解析
	+ **对一些特殊字符进行解析**

			\n	换行（ASCII 字符集中的 LF 或 0x0A (10)）
			\r	回车（ASCII 字符集中的 CR 或 0x0D (13)）
			\t	水平制表符（ASCII 字符集中的 HT 或 0x09 (9)）
			\v	垂直制表符（ASCII 字符集中的 VT 或 0x0B (11)）（自 PHP 5.2.5 起）
			\e	Escape（ASCII 字符集中的 ESC 或 0x1B (27)）（自 PHP 5.4.0 起）
			\f	换页（ASCII 字符集中的 FF 或 0x0C (12)）（自 PHP 5.2.5 起）
			\\	反斜线
			\$	美元标记
			\"	双引号
			\[0-7]{1,3}	符合该正则表达式序列的是一个以八进制方式来表达的字符
			\x[0-9A-Fa-f]{1,2}	符合该正则表达式序列的是一个以十六进制方式来表达的字符
	+ PHP 5.1.1 以前，`\{$var}` 中的反斜线还不会被显示出来。

3. Heredoc 结构 （相当于双引号）

    heredoc 句法结构：<<<。在该运算符之后要提供一个标识符，然后换行。接下来是字符串 string 本身，最后要用前面定义的标识符作为结束标志。结束时所引用的标识符必须在该行的第一列，而且，标识符的命名也要像其它标签一样遵守 PHP 的规则：**只能包含字母、数字和下划线，并且必须以字母和下划线作为开头**。

		<?php
			$str = <<<EOD
			Example of string
			spanning multiple lines
			using heredoc syntax.
			EOD;

	+ 要注意的是结束标识符这行除了可能有一个分号（;）外，绝对不能包含其它字符。
	+ Heredoc 结构不能用来初始化类的属性。（自 PHP 5.3 起，此限制仅对 heredoc 包含变量时有效）
	+ 自 PHP 5.3.0 起还可以在 Heredoc 结构中用双引号来声明标识符：
		
			<?php
				$str = <<<"EOD"
				Example of string
				spanning multiple lines
				using heredoc syntax.
				EOD; 

4. Nowdoc 结构(相当于单引号，PHP5.3引入)	
 
 	Nowdoc 结构很象 heredoc 结构，但是 nowdoc 中不进行解析操作。一个 nowdoc 结构也用和 heredocs 结构一样的标记 <<<， 但是跟在后面的标识符要用单引号括起来，即 <<<'EOT'。

		<?php
			$str = <<<'EOD'
			Example of string
			spanning multiple lines
			using heredoc syntax.
			EOD;

5. 变量解析

	+ 简单语法（解析变量，array 的值，object 的属性）
	
    	 当 PHP 解析器遇到一个美元符号（$）时，它会和其它很多解析器一样，去组合尽量多的标识以形成一个合法的变量名。可以用花括号来明确变量名的界线。  
		 数组索引要用方括号（]）来表示索引结束的边际
	+ 复杂语法

		任何具有 string 表达的标量变量，数组单元或对象属性都可使用此语法。只需简单地像在 string 以外的地方那样写出表达式，然后用花括号 { 和 } 把它括起来即可。由于 { 无法被转义，只有 $ 紧挨着 { 时才会被识别。可以用 {\$ 来表达 {$。

		**函数、方法、静态类变量和类常量只有在 PHP 5 以后才可在 {$} 中使用。然而，只有在该字符串被定义的命名空间中才可以将其值作为变量名来访问。只单一使用花括号 ({}) 无法处理从函数或方法的返回值或者类常量以及类静态变量的值。**
		
			// 有效，输出： I'd like an A & W
			echo "I'd like an {${beers::softdrink}}\n";
			
			// 也有效，输出： I'd like an Alexander Keith's
			echo "I'd like an {${beers::$ale}}\n";

6. 存取和修改字符串中的字符 

	可以把 string 当成字符组成的 array。可以使用 `{}` 和 `[]`

	+ 自 PHP 5.4 起字符串下标必须为整数或可转换为整数的字符串
	+ 用 [] 或 {} 访问任何其它类型（不包括数组或具有相应接口的对象实现）的变量只会无声地返回 NULL

7. 有用的函数和运算符

	字符串可以用 '.'（点）运算符连接起来，注意 '+'（加号）运算符没有这个功能。更多信息参考字符串运算符。

	对于 string 的操作有很多有用的函数。
	
	可以参考字符串函数了解大部分函数，高级的查找与替换功能可以参考正则表达式函数或 Perl 兼容正则表达式函数。
	
	另外还有 URL 字符串函数，也有加密／解密字符串的函数（mcrypt 和 mhash）。
	
	最后，可以参考字符类型函数。

8. 自动转换

	+ 在一个需要字符串的表达式中，会自动转换为 string。
	+ 一个布尔值 boolean 的 TRUE 被转换成 string 的 "1"。Boolean 的 FALSE 被转换成 ""（空字符串）
	+ 一个整数 integer 或浮点数 float 被转换为数字的字面样式的 string（包括 float 中的指数部分）。使用指数计数法的浮点数（4.1E+6）也可转换。
	+ 数组 array 总是转换成字符串 "Array"
	+ 资源 resource 总会被转变成 "Resource id #1" 这种结构的字符串
	+ NULL 总是被转变成空字符串
	+ 可以使用函数 print_r() 和 var_dump() 列出这些类型的内容。
	+ 大部分的 PHP 值可以转变成 string 来永久保存，这被称作串行化，可以用函数 serialize() 来实现。

9. <span id="string-to-number">字符串转换为数值</span>
	
	如果该字符串没有包含 '.'，'e' 或 'E' 并且其数字值在整型的范围之内（由 PHP_INT_MAX 所定义），该字符串将被当成 integer 来取值。其它所有情况下都被作为 float 来取值。  
	
	该字符串的开始部分决定了它的值。如果该字符串以合法的数值开始，则使用该数值。否则其值为 0（零）。

	使用函数 ord() 和 chr() 实现 ASCII 码和字符间的转换。

10. 字符串编码

	字符串的编码会以脚本的编码方式进行编码

### <span id="array">Array 数组</span>

##### 基础语法
1. 自 5.4 起可以使用短数组定义语法，用 [] 替代 array()。
2. key 可以是 integer 或者 string。value 可以是任意类型。

	键值强制转换

	+ **包含有合法整型值的字符串会被转换为整型。**例如键名 "8" 实际会被储存为 8。但是 "08" 则不会强制转换，因为其不是一个合法的十进制数值。
	+ **浮点数也会被转换为整型**，意味着其小数部分会被舍去。例如键名 8.7 实际会被储存为 8。
	+ 布尔值也会被转换成整型。
	+ Null 会被转换为空字符串，即键名 null 实际会被储存为 ""。
	+ 数组和对象不能被用为键名。坚持这么做会导致警告：Illegal offset type。

3. **每一个新单元都会覆盖前一个的值**
4. key 为可选项。如果未指定，PHP 将自动使用**之前**（删除也算）用过的最大 integer 键名加上 1 作为新的键名。（从0开始）

##### 其他

1. 用方括号语法访问数组单元。方括号和花括号可以互换使用来访问数组单元

		$arry[1] 或者 $array{111}

2. 自 PHP 5.4 起可以用直接对函数或方法调用的结果进行数组解引用，在此之前只能通过一个临时变量。(**即不需要临时变量**)

3. 自 PHP 5.5 起可以直接对一个数组原型进行数组解引用。(即使用 new stdclass()形式)
4. 用方括号的语法新建／修改。即如果存在变量对其修改，不存在对其新增。
5. 不给索引加上引号是合法的因此 "$foo[bar]" 是合法的，但是不建议使用
6. 装换为数组

	+ 对于任意 integer，float，string，boolean 和 resource 类型，如果将一个值转换为数组，将得到一个仅有一个元素的数组，其下标为 0，该元素即为此标量的值。
	+ 如果一个 object 类型转换为 array，则结果为一个数组，其单元为该对象的属性。键名将为成员变量名，不过有几点例外：整数属性不可访问；私有变量前会加上类名作前缀；保护变量前会加上一个 '*' 做前缀。这些前缀的前后都各有一个 NULL 字符
	+ 将 NULL 转换为 array 会得到一个空的数组。

##### 实例

1. 在循环中改变单元

		<?php
		// PHP 5
		foreach ($colors as &$color) {
		    $color = strtoupper($color);
		}
		unset($color); /* ensure that following writes to
		$color will not modify the last array element */
		
		// Workaround for older versions
		foreach ($colors as $key => $color) {
		    $colors[$key] = strtoupper($color);
		}
		
		print_r($colors);
		?>

2. 数组(Array) 的赋值总是会涉及到值的拷贝。使用引用运算符通过引用来拷贝数组。

		<?php
		$arr1 = array(2, 3);
		$arr2 = $arr1;
		$arr2[] = 4; // $arr2 is changed,
		             // $arr1 is still array(2, 3)
		             
		$arr3 = &$arr1;
		$arr3[] = 4; // now $arr1 and $arr3 are the same
		?>

### <span id="object">Object对象</span>

+ 对象初始化 

	 要创建一个新的对象 object，使用 new 语句实例化一个类：
	
		<?php
		class foo
		{
		    function do_foo()
		    {
		        echo "Doing foo."; 
		    }
		}
		
		$bar = new foo;
		$bar->do_foo();
		?>

	 如果将一个对象转换成对象，它将不会有任何变化。如果其它任何类型的值被转换成对象，将会创建一个内置类 stdClass 的实例。如果该值为 NULL，则新的实例为空。 ***array 转换成 object 将使键名成为属性名并具有相对应的值，除了数字键，***不迭代就无法被访问。[阅读stdclass详解](http://www.phppan.com/2011/05/php-stdclass/#comments),即**stdClass类是PHP的一个内部保留类，初始时没有成员变量也没成员方法，所有的魔术方法都被设置为NULL，可以使用其传递变量参数，但是没有可以调用的方法。stdClass类可以被继承，只是这样做没有什么意义。**
	
		<?php
		$obj = (object) array('1' => 'foo');
		var_dump(isset($obj->{'1'})); // outputs 'bool(false)'
		var_dump(key($obj)); // outputs 'int(1)'
		?>
	 ***对于其他值，会包含进成员变量名 scalar***。
	
		<?php
		$obj = (object) 'ciao';
		echo $obj->scalar;  // outputs 'ciao'
		?>

### <span id="resource">Resource 资源类型 </span>

 资源是一种特殊变量，保存了到外部资源的一个引用。资源是通过专门的函数来建立和使用的。可以使用get_resource_type() 函数来获取资源的类型

+ 转换为资源（没有意义）
+ 释放资源

	引用计数系统是 Zend 引擎的一部分，可以自动检测到一个资源不再被引用了（和 Java 一样）。这种情况下此资源使用的所有外部资源都会被垃圾回收系统释放。因此，很少需要手工释放内存。

	**Note: 持久数据库连接比较特殊，它们不会被垃圾回收系统销毁**

### <span id="null">NULL</span>
 特殊的 NULL 值表示一个变量没有值。NULL 类型唯一可能的值就是 NULL。

+ 在下列情况下一个变量被认为是 NULL：

	 + 被赋值为 NULL。
	
	 + 尚未被赋值。
	
	 + 被 unset()。

+ 语法 

	 NULL 类型只有一个值，就是不区分大小写的常量 NULL。
	
		<?php
		$var = NULL;       
		?>
		参见 is_null() 和 unset()。

+ 转换到 NULL 

 	使用 (unset) $var 将一个变量转换为 null 将不会删除该变量或 unset 其值。仅是返回 NULL 值而已。

### <span id="callable">Callback / Callable 类型 </span>

 自 PHP 5.4 起可用 callable 类型指定回调类型 callback。一些函数如 call_user_func() 或 usort() 可以接受用户自定义的回调函数作为参数。回调函数不止可以是简单函数，还可以是对象的方法，包括静态类方法。

1. 传递方法

	+ PHP是将函数以string形式传递的。 可以使用任何内置或用户自定义函数，但除了语言结构例如：array()，echo，empty()，eval()，exit()，isset()，list()，print 或 unset()。
	+ 一个已实例化的 object 的方法被作为 array 传递，下标 0 包含该 object，下标 1 包含方法名。 在同一个类里可以访问 protected 和 private 方法。
	+ 静态类方法也可不经实例化该类的对象而传递，只要在下标 0 中包含类名而不是对象。自 PHP 5.2.3 起，也可以传递 'ClassName::methodName'。
	+ 使用匿名函数
	+ `注意`：在函数中注册有多个回调内容时(如使用 call_user_func() 与 call_user_func_array())，如在前一个回调中有未捕获的异常，其后的将不再被调用。

### <span id = "change-type">类型转换的判别</span>

 PHP 在变量定义中不需要（或不支持）明确的类型定义；变量类型是根据使用该变量的上下文所决定的。

+ (binary) 转换和 b 前缀转换支持为 PHP 5.2.1 新增。
+ 可以将变量放置在双引号中的方式来代替将变量转换成字符串：