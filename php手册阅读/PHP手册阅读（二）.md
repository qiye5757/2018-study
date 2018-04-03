## PHP手册阅读（二）之基本语法

1. [标记](#sign)
2. [从html中分离](#escape)
3. [指令分隔符](#Instruction)

### <span id = "sign">标记</span>

+ PHP解析器会解析`<?php` 和 `?>`之间的代码
+ PHP也支持 `<?` 和 `?>` 但是不鼓励使用
+ **如果为纯PHP文件，最好不要加上 `?>` ，这是为了以防coder在关闭标签外不小心键入了space，会导致PHP输出这些space。**
+ **PHP开始标签 `<?php` 后面必须跟一个space。这个space包括`\s` `\t` `\r` `\n`。结束标签相当于一个space**
+ PHP7只支持以上两种，以前还有类似于`<%, %>`或者`<script language="php"> </script>`

### <span id = "escape">从html中分离</span>

+ 由于PHP解析器只能解析PHP标签中的代码，所以PHP代码可以和HTML代码混搅在一起。
+ 所以当PHP解释器遇到结束标签就将其后的内容原样输出
+ 例外是处于条件语句中间的时候，PHP会根据条件判断哪些可以输出，哪些不可以输出

### <span id = "Instruction">指令分隔符</span>

+ PHP需要在每个语句后面用分号结束指令。
+ PHP结束标签可以当做一个结束符，所以PHP的最后一行代码可以不用加分号

### <span id = "clear">PHP注释</span>

+ PHP三种注释，分别为 `//` `/**/` `#`