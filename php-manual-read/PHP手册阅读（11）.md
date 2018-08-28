# PHP手册阅读（十一）之错误与异常

1. [PHP错误](#error)
   + [PHP错误最佳实践](#error_1)
   + [PHP错误类型](#error_2)
     + [PHP错误类型以及错误级别](#error_2_1)
     + [PHP错误类型总结](#error_2_2)
     + [PHP错误类型使用](#error_2_3)
   + [PHP错误配置](#error_3)
     + [错误配置内容](#error_3_1)
     + [错误配置的一些说明](#error_3_2)
   + [错误相关的一些函数](#error_4)
   + [PHP7错误处理机制](#error_5)

2. [PHP异常](#exception)

	+ [常用函数](#exception_1)

3. [总结](#totle)

	+ [PHP7之前](#totle_PHP7_early)
	+ [PHP7之后](#totle_PHP7)

4. [参考](#see)

## <span id="error">PHP 错误</span>

 PHP报告错误是为了响应一些内部错误情况，而且通过类型来表示不同的内部错误情况，并且可以通过设置来显示或者记录下来。 PHP错误类型作为PHP核心之错误扩展中的预定义常量存在。

### <span id="error_1">PHP错误最佳实践</span>

+ 一定要让 PHP 报告错误；
+ 在开发环境中要显示错误；
+ 在生产环境中不能显示错误；
+ 在开发和生产环境中都要记录错误。

### <span id="error_2">PHP错误类型</span>

#### <span id="error_2_1">PHP错误类型以及错误级别</span>

 PHP分为多个错误级别，而且在不同版本中的错误级别有可能不一致，建议使用预定义常量，而不是数字。

| 值    | 常量                | 说明                                                         |
| ----- | ------------------- | ------------------------------------------------------------ |
| 1     | E_ERROR             | 致命的运行时错误，不可恢复，如位置异常，内存不足等。**注意，如果有未被捕获的异常，也是会触发这个级别的。** |
| 2     | E_WARNING           | 运行时警告 (非致命错误)。仅给出提示信息，但是脚本不会终止运行。 |
| 4     | E_PARSE             | 解析错误，在解析PHP文件时产生，并强制PHP在执行前退出         |
| 8     | E_NOTICE            | 运行时通知。表示脚本遇到可能会表现为错误的情况，但是在可以正常运行的脚本里面也可能会有类似的通知。 |
| 16    | E_CODE_ERROR        | 在PHP初始化启动过程中发生的致命错误。该错误类似`E_ERROR`，但是是由PHP引擎核心产生的。 |
| 32    | E_CODE_WARNING      | PHP初始化启动过程中发生的警告 (非致命错误) 。类似 `E_WARNING`，但是是由PHP引擎核心产生的。 |
| 64    | E_COMPILE_ERROR     | 致命编译时错误。类似`E_ERROR`,  但是是由Zend脚本引擎产生的。 |
| 128   | E_COMPILE_WARNING   | 编译时警告 (非致命错误)。类似`E_WARNING`，但是是由Zend脚本引擎产生的。 |
| 256   | E_USER_ERROR        | 用户产生的错误信息。类似`E_ERROR`, 但是是由用户自己在代码中使用PHP函数 `trigger_error()`来产生的。 |
| 512   | E_USER_WARNING      | 用户产生的警告信息。类似`E_WARNING`, 但是是由用户自己在代码中使用PHP函数`trigger_error()`来产生的。 |
| 1024  | E_USER_NOTICE       | 用户产生的通知信息。类似`E_NOTICE`, 但是是由用户自己在代码中使用PHP函数` trigger_error()`来产生的。 |
| 2048  | E_STRICT            | 启用 PHP 对代码的修改建议，以确保代码具有最佳的互操作性和向前兼容性。 |
| 4096  | E_RECOVERABLE_ERROR | 可被捕捉的致命错误。 它表示发生了一个可能非常危险的错误，但是还没有导致PHP引擎处于不稳定的状态。 如果该错误没有被用户自定义句柄捕获 (参见 `set_error_handler()`)，将成为一个 E_ERROR　从而脚本会终止运行。 |
| 8192  | E_DEPRECATED        | 运行时通知。启用后将会对在未来版本中可能无法正常工作的代码给出警告。 |
| 16384 | E_USER_DEPRECATED   | 用户产生的警告信息。 类似`E_DEPRECATED`, 但是是由用户自己在代码中使用PHP函数 `trigger_error()`来产生的。 |
| 30719 | E_ALL               | `E_STRICT`除外的所有错误和警告信息。PHP5.4后包含`E_STRICT`错误 |

#### <span id="error_2_2">PHP错误类型总结</span>

1. `E_ERROR`：运行时致命错误
2. `E_WARNING`：运行时警告
3. `E_PARSE`：解析时错误
4. `E_NOTICE`：运行时通知，可能为错误。
5. `E_CODE_ERROR`和`E_CODE_WARNING`：PHP初始化时产生的致命错误和警告
6. `E_COMPIL_ERROR`和`E_COMPIL_WARNING`：编译时产生的致命错误和警告
7. `E_STRICT`：你的代码可以运行，但是不是PHP建议的写法。 
8. `E_DEPRECATED`：运行时通知，启用后将会对在未来版本中可能无法正常工作的代码给出警告。 
9. `E_RECOVERABLE_ERROR`：可被捕获的`E_ERROR`，如果用户未定义 `set_error_handler()`函数则成为一个`E_ERROR`错误
10. `E_USER_ERROR`、`E_USER_WARNING`、`E_USER_NOTICE`和`E_USER_DEPRECATED`：用户自定义的错误，使用`trigger_error()`抛出。
11. `E_ALL`：除了`E_STRICT`以外的所有错误信息。PHP5.4后包含`E_STRICT`错误

#### <span id="error_2_3">PHP错误类型使用</span>

  可以在配置文件或者 `error_reporting()`函数中使用用于设置应该报告何种错误，**可以使用按位运算符来组合这些值或者屏蔽某些类型的错误。**在配置文件中只能使用 `|`、`&`、`^` 、`~`和`! `来进行组合。

### <span id="error_3">PHP错误配置</span>

#### <span id="error_3_1">错误配置内容</span>

```
error_reporting = E_ALL  		 // 报告错误级别，什么级别的
display_errors = On 			// 是否把错误展示在输出上，这个输出可能是output(PHP执行的输出流)，也可能是stdout(为进程的标准输出)。PHP5.2以后可以设置值为 `stderr`，则可以将错误展示到 stderr输出（即标准错误输出）
display_startup_errors = On 	 // 是否把启动过程的错误信息显示在页面上
log_errors = On 				// 是否要记录错误日志
log_errors_max_len = 1024 		 // 错误日志的最大长度
ignore_repeated_errors = Off 	 // 是否忽略重复的错误
ignore_repeated_source = Off     // 忽略重复消息时，也忽略消息的来源
report_memleaks = On 			// 如果这个参数设置为Off，则内存泄露信息不会显示 (在 stdout 或者日志中)。
track_errors = Off 			    // 是否使用全局变量$php_errormsg来记录最后一个错误
html_errors = On  				// 是否把输出中的函数等信息变为HTML链接
xmlrpc_errors = 0 				// 是否使用XML-RPC的错误信息格式记录错误
xmlrpc_error_number = 0 		 // 用作 XML-RPC faultCode 元素的值。
docref_root = http://manual/en/  // 如果打开了html_errors参数，PHP将会在出错信息上显示超连接，直接链接到一个说明这个错误或者导致这个错误的函数的页面。你可以从http://www.php.net/docs.php下载php手册，并设置docref_root参数，将他指向你本地的手册所在目录。你还必须设置"docref_ext"来指定文件的扩展名	
error_prepend_string = "11"  	 //错误信息之前输出的内容。 
error_append_string = "22"		 //错误信息之后输出的内容。
fastcgi.logging = 0 			// 是否把php错误抛出到fastcgi中
error_log = /tmp/php_errors.log  // php中的错误显示的日志位置
```

#### <span id="error_3_2">错误配置的一些说明</span>

1. `error_reporting`：配置什么样子的错误能显示，这个是最重要的控制
2. `display_errors`：**是否展示错误说明**，设置为Off则不显示错误的说明信息

### <span id="error_4">错误相关的一些函数</span>

1. `error_reporting([ int $level ] )`：可以设置`$level`，不设置则输出当前的错误级别

   ```
   // 关闭所有PHP错误报告
   error_reporting(0);
   
   // Report simple running errors
   error_reporting(E_ERROR | E_WARNING | E_PARSE);
   
   // 报告 E_NOTICE也挺好 (报告未初始化的变量
   // 或者捕获变量名的错误拼写)
   error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
   
   // 除了 E_NOTICE，报告其他所有错误
   error_reporting(E_ALL ^ E_NOTICE);
   
   // 报告所有 PHP 错误 (参见 changelog)
   error_reporting(E_ALL);
   
   // 报告所有 PHP 错误
   error_reporting(-1);
   ```

2. `set_error_handler(callable $error_handler [, int $error_types = E_ALL | E_STRICT ])`：设置用户自定义的错误处理函数。

   + 其中$error_handle为一个回调函数
   + $_error_type:如果没有该掩码， 无论 error_reporting 是如何设置的， error_handler 都会在每个错误发生时被调用。 
   + **以下是不能被用户自定义函数调用的错误级别：E_ERROR、 E_PARSE、 E_CORE_ERROR、 E_CORE_WARNING、 E_COMPILE_ERROR、 E_COMPILE_WARNING**
   +  `error_types` 里指定的错误类型都会绕过 PHP 标准错误处理程序， 除非回调函数返回了 FALSE
   + 如果错误发生在脚本执行之前（比如文件上传时），将不会 调用自定义的错误处理程序因为它尚未在那时注册。 

3. `bool trigger_error ( string $error_msg [, int $error_type = E_USER_NOTICE ] )`：产生一个用户级别的错误

### <span id="error_5">PHP7错误处理机制</span>

**PHP 7 改变了大多数错误的报告方式**。不同于传统（PHP 5）的错误报告机制，现在大多数错误被作为 Error 异常抛出。 

这种 Error 异常可以像 Exception 异常一样被第一个匹配的 try / catch 块所捕获。如果没有匹配的 catch 块，则调用异常处理函数（事先通过 `set_exception_handler()`注册）进行处理。 如果尚未注册异常处理函数，则按照传统方式处理：被报告为一个致命错误（Fatal Error）。 

Error 类并非继承自 Exception 类，所以不能用 catch (Exception $e) { ... } 来捕获 Error。你可以用 catch (Error $e) { ... }，或者通过注册异常处理函数（`set_exception_handler()`）来捕获 Error。 

## <span id="exception">PHP异常</span>

  PHP的异常和其他语言中的异常一样，使用 `try`、`catch`、`finally`，只不过PHP的异常系统不会自动抛出，需要`手动抛出`，`导致PHP异常在程序中的作用减半`(异常就是意料之外的事情，根本我们意料不到的，如果用手动抛出，证明已经预先预料到了，那异常的意义就变味了) 。

### <span id="exception_1">常用函数</span>

`set_exception_handler(callable $exception_handler)`：设置默认的异常处理程序，用于没有用 try/catch 块来捕获的异常。 在 exception_handler 调用后异常会中止。

+ `$exception_handler`回调函数的参数是` Exception`类，PHP7以后为`Throwable`类，该类可以捕获大多数的错误

## <span id="totle">总结</span>

在PHP 中将代码自身异常(一般是环境或者语法非法所致)称作错误 `Error`，将运行中出现的逻辑错误称为异常 `Exception`。

### <span id="totle_PHP7_early">PHP7之前</span>

`error`可以通过`set_error_handler() `函数处理错误，但是一部分`error`类型是无法进行捕获的。`Exception`只能通过用户手动 `throw`,未通过 `try/catch`的会被`set_exception_handler()`捕获，如果未定义`set_exception_handler()`函数，也未`try/catch`的则会产生一个 `Fatal error`。那么问题来了，我们都用过框架，但是为什么框架能处理致命错误？可以参考这篇：[PHP 优雅的捕获处理错误 -- E_PARSE / E_ERROR](https://segmentfault.com/a/1190000014926703)

### <span id="totle_PHP7">PHP7之后</span>

PHP7 中出现了 `Throwable` 接口，该接口由 `Error` 和 `Exception` 实现，用户不能直接实现 `Throwable` 接口，而只能通过继承 `Exception` 来实现接口（这就和Java有点像了，虽然不能像Java那么强大，但是配合着`set_exception_handler()`函数可以捕获大部分的错误与异常，但是像一些如内存不足等错误还是像原来一样让程序停止运行）。可以参考 [PHP7中的异常与错误处理](https://novnan.github.io/PHP/throwable-exceptions-and-errors-in-php7/)

## <span id="see">参考</span>

1. [http://php.net/manual/zh/language.errors.basics.php](http://php.net/manual/zh/language.errors.basics.php)

2. [PHP的错误机制总结](https://www.cnblogs.com/yjf512/p/5314345.html)

3. [php 的错误和异常处理机制](https://juejin.im/entry/5987d2ff6fb9a03c314fe732#fn:2)

4. [PHP 最佳实践之异常和错误](https://laravel-china.org/articles/5435/exceptions-and-errors-in-php-best-practices)

   