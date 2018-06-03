## PHP手册阅读（七）之流程控制

### 控制语句

`if` `else` `elseif` `while` `do-while` `for` `foreach` `break` `continue` `switch` `declear` `return` `require` `include` `require_once` `include_once` `goto`

+ do-while的其中一种用法

 	把语句放在 do-while(0) 之中，在循环内部用 break 语句来结束执行循环

+ break/continue 可以接受一个可选的数字参数来决定跳出几重循环。

	+ 5.4.0 break 0; 不再合法。这在之前的版本被解析为 break 1;。  
	+ 5.4.0 取消变量作为参数传递（例如 $num = 2; break $num;）。 

+ break and break() 是一样的
+ 注意在 PHP 中 switch 语句被认为是可以使用 continue 的一种循环结构。 
+ 如果当前脚本是被 include 的，则 return 的值会被当作 include 调用的返回值。
+ return 如果没有提供参数，则一定不能用括号
+ goto 操作符可以用来跳转到程序中的另一位置。该目标位置可以用目标名称加上冒号来标记，而跳转指令是 goto 之后接上目标位置的标记。PHP 中的 goto 有一定限制，目标位置只能位于同一个文件和作用域，也就是说无法跳出一个函数或类方法，也无法跳入到另一个函数。也无法跳入到任何循环或者 switch 结构中。可以跳出循环或者 switch，通常的用法是用 goto 代替多层的 break。（只在5.3以上版本使用） 

### 控制语句的替代语法

 if，while，for，foreach 和 switch。替代语法的基本形式是把左花括号（{）换成冒号（:），把右花括号（}）分别换成 endif;，endwhile;，endfor;，endforeach; 以及 endswitch;。

+ switch 和第一个 case 之间的任何输出（含空格）将导致语法错误。

### foreach的用法

+ foreach 只能用于对象和数组，还可以自定义遍历对象
+ 可以很容易地通过在 $value 之前加上 & 来修改数组的元素。此方法将以引用赋值而不是拷贝一个值。
+ $value 的引用仅在被遍历的数组可以被引用时才可用
+ 数组最后一个元素的 $value 引用在 foreach 循环之后仍会保留。建议使用 unset() 来将其销毁。
+ 以下与foreach作用相同

		while(list($key,$value) = each($arr)){
	    	// 这里可以处理key和value了
		}

		//或者
		$arr = array(1=>2,"df"=>5,6=>5);
		for($il;i<count($arr)l$i++){
		    $key = key($arr);
		    $value = next($arr);
		    echo "$key => $value";
		}

##### 用 list() 给嵌套的数组解包 （PHP5.5以上）

	<?php
		$array = [
		    [1, 2],
		    [3, 4],
		];
		
		foreach ($array as list($a, $b)) {
		    // Note that there is no $b here.
		    echo "$a\n $b";
		}

### switch

+ 注意和其它语言不同，continue 语句作用到 switch 上的作用类似于 break。如果在循环中有一个 switch 并希望 continue 到外层循环中的下一轮循环，用 continue 2。 
+ **仅当一个 case 语句中的值和 switch 表达式的值匹配时 PHP 才开始执行语句，直到 switch 的程序段结束或者遇到第一个 break 语句为止。如果不在 case 的语句段最后写上 break 的话，PHP 将继续执行下一个 case 中的语句段。**

		在一个 case 中的语句也可以为空，这样只不过将控制转移到了下一个 case 中的语句。 

		<?php
		switch ($i) {
		    case 0:
		    case 1:
		    case 2:
		        echo "i is less than 3 but not negative";
		        break;
		    case 3:
		        echo "i is 3";
		}
		?> 

+ case 表达式可以是任何求值为简单类型的表达式，即整型或浮点数以及字符串。不能用数组或对象，除非它们被解除引用成为简单类型。

### declear

 declare 结构用来设定一段代码的执行指令.

	declare (directive)
    statement
 directive 目前只能为 ticks 和 encoding(PHP5.3新增的)，可以用用于全局或者部分

	<?php
		// you can use this:
		declare(ticks=1) {
		    // entire script here
		}
		
		// or you can use this:
		declare(ticks=1);
		// entire script here
		?> 

##### ticks指令

 Tick（时钟周期）是一个在 declare 代码段中解释器每执行 N 条可计时的低级语句就会发生的事件。N 的值是在 declare 中的 directive 部分用 ticks=N 来指定的。  
 在每个 tick 中出现的事件是由 register\_tick\_function() 来指定的。注意每个 tick 中可以出现多个事件。 

 低级语句包括以下：（[参考](https://blog.csdn.net/beyond__devil/article/details/52584101)）

+ 简单语句：空语句（就一个；号），return,break,continue,throw, goto,global,static,unset,echo, 内置的HTML文本，分号结束的表达式等均算一个语句。

+ 复合语句：完整的if/elseif,while,do...while,for,foreach,switch,try...catch等算一个语句。

+ 语句块：{} 括出来的语句块。

+ 最后特别的：declare块本身也算一个语句(按道理declare块也算是复合语句，但此处特意将其独立出来)。

+ 所有的statement, function_declare_statement, class_declare_statement就构成了所谓的低级语句(low-level statement)。

##### encoding指令

 可以用 encoding 指令来对每段脚本指定其编码方式。 

 在 PHP 5.3 中除非在编译时指定了 --enable-zend-multibyte，否则 declare 中的 encoding 值会被忽略。 

 注意除非用 phpinfo()，否则 PHP 不会显示出是否在编译时指定了 --enable-zend-multibyte。

### include和require

+ require 和 include 的区别：

	+ require 在出错时产生 E\_COMPILE\_ERROR 级别的错误。换句话说将导致脚本中止而 include 只产生警告（E_WARNING），脚本会继续运行。 

+ 加载顺序

 	+ 按参数给出的路径寻找，如果没有给出目录（只有文件名）时则按照 include_path 指定的目录寻找
 	+ 如果在 include_path 下没找到该文件则 include 最后才在调用脚本文件所在的目录和当前工作目录下寻找。
 	+ 如果最后仍未找到文件则 include 结构会发出一条警告；这一点和 require 不同，后者会发出一个致命错误。

+ 引用规则
	
	+ **魔术常量在引用前就定义好的**
	+ 如果"URL fopen wrappers"在 PHP 中被激活（默认配置），可以用 URL来指定要包含的文件
	+ **远程文件可能会经远程服务器处理（根据文件后缀以及远程服务器是否在运行 PHP 而定），但必须产生出一个合法的 PHP 脚本，因为其将被本地服务器处理。如果来自远程服务器的文件应该在远端运行而只输出结果，那用 readfile() 函数更好。另外还要格外小心以确保远程的脚本产生出合法并且是所需的代码。**
	+ 处理返回值：在失败时 include 返回 FALSE 并且发出警告。成功的包含则返回 1，除非在包含文件中另外给出了返回值。
	+ **如果在包含文件中定义有函数，这些函数不管是在 return 之前还是之后定义的，都可以独立在主文件中使用。**
	+ 要在脚本中自动包含文件，参见 php.ini 中的 auto_prepend_file 和 auto_append_file 配置选项。 
+ require 和 require_once的区别：require_once只能包含一次