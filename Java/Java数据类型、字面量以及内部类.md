## Java数据类型

 看了别人讲解的这一方面的内容，感觉应该以我容易理解的方式记录下来

### 参考博客

+ [菜鸟教程-Java教程](http://www.runoob.com/java/java-basic-datatypes.html)
+ [【系列】重新认识Java——基本类型和包装类](https://blog.csdn.net/xialei199023/article/details/63251295)
+ [Java字面量](https://my.oschina.net/brucelee80/blog/161103)
+ [Java Data Types & Literals | 数据类型 和 字面量](http://wuaner.iteye.com/blog/1668172)

### 基本数据类型

 Java共有8大基本类型，这个是常用高级语言都一样的，如C，C++等等。共分为四种类型

+ 整数型：byte、short、int、long . 

  > 字面量：可表示二进制（`0b`开头）、八进制（`0` 开头）、十六进制（`0x`开头）、十进制整数

+ 浮点型：double、float

  > 字面量：可表示十进制、十六进制

+ 字符型：char

  > char 字面量：由单引号 ' 包围，用来表示单个字符(不能是单引号'，因为该literal由'包围；不能是反斜杠\，因为\用来表示转义字符)或转义字符 

  > string字面量：由双引号 " 包围，由>=0个字符(不能是双引号"，因为该literal由"包围；不能是反斜杠\，因为\用来表示转义字符)组成，这些字符可以是转义字符 

  > Null字面量：null，用来表示null类型的值，代表了空引用(null reference) 

+ 布尔型：boolean

  > 字面量：true & false，用来表示boolean类型的值 

| 类型    | 说明                         | 占用存储空间             | 表数范围                                | 包装类    |
| ------- | ---------------------------- | ------------------------ | --------------------------------------- | --------- |
| byte    | 用补码表示的有符号整数       | 1 bytes                  | -2^7 ～ 2^7-1 （-128 ～ 127）           | Byte      |
| short   | 用补码表示的有符号整数       | 2 bytes                  | -2^15 ～ 2^15-1 （-32,768 ～ 32,767）   | Short     |
| char    | Unicode字符，或无符号整数    | 2 bytes                  | \u0000 ～ \u00ff ； 0 ～ 2^16-1(65,535) | Character |
| int     | 用补码表示的有符号整数       | 4 bytes                  | -2^31 ～ 2^31-1                         | Integer   |
| long    | 用补码表示的有符号整数       | 8 bytes                  | -2^63 ～ 2^63-1                         | Long      |
| float   | 遵循IEEE 754单精度浮点数标准 | 4 bytes                  | -.403E38 ～ 3.403E38                    | Float     |
| double  | 遵循IEEE 754双精度浮点数标准 | 8 bytes                  | -1.798E308 ～ 1.798E308                 | Double    |
| boolean |                              | 1 bytes(only 1 bit used) |                                         | Boolean   |

### 常见问题说明



### 更新记录

+ 2018年7月16日16:38:52  

  > 新增基础类型、字面量和内部类定义。

