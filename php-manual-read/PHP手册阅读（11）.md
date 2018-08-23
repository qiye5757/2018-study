# PHP手册阅读（十一）之错误与异常

1. PHP错误
   + PHP错误最佳实践
   + PHP错误类型
     + PHP错误类型以及错误级别
     + PHP错误类型总结
     + PHP错误类型使用
   + PHP错误配置

## PHP 错误

 PHP报告错误是为了响应一些内部错误情况，而且通过类型来表示不同的内部错误情况，并且可以通过设置来显示或者记录下来。 PHP错误类型作为PHP核心之错误扩展中的预定义常量存在。

### PHP错误最佳实践

+ 一定要让 PHP 报告错误；
+ 在开发环境中要显示错误；
+ 在生产环境中不能显示错误；
+ 在开发和生产环境中都要记录错误。

### PHP错误类型

#### PHP错误类型以及错误级别

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

#### PHP错误类型总结

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

#### PHP错误类型使用

  可以在配置文件或者 `error_reporting()`函数中使用用于设置应该报告何种错误，**可以使用按位运算符来组合这些值或者屏蔽某些类型的错误。**在配置文件中只能使用 `|`、`&`、`^` 、`~`和`! `来进行组合。

### PHP错误配置

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

### 参考

1. [http://php.net/manual/zh/language.errors.basics.php](http://php.net/manual/zh/language.errors.basics.php)
2. [PHP的错误机制总结](https://www.cnblogs.com/yjf512/p/5314345.html)
3. [php 的错误和异常处理机制](https://juejin.im/entry/5987d2ff6fb9a03c314fe732#fn:2)
4. [PHP 最佳实践之异常和错误](https://laravel-china.org/articles/5435/exceptions-and-errors-in-php-best-practices)