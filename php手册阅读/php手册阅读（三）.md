## PHP手册阅读（三）之数据类型

1. [简介](#introduction)
2. [bool类型](#boolean)

### <span id = "introduction">简介</span>

+ PHP有9种数据类型 
+ 标量：`integer`、 `boolean` 、`string`、 `float`
+ 复合：`array` 、`object` 、`callable`
+ 资源：`resource`、 `NULL`

+ 为了在手册中阅读，新增了 `mixed`、 `callback`、 `number`、 `array|object`、 `void`、 `$...`
+ var_dump()：打印变量的相关信息
+ gettype()：获取一个变量的数据类型（返回值除了callable的其他），判断某个变量的类型请用下面的这个is
+ settype()：设置一个变量为某种类型（除了callable和resource都可以）
+ is_int()：判断一个变量是否为某一种类型（九种都可以）

### <span id = "boolean">bool类型</span>

+ 这是最简单的一种数据类型。值可以为 `true` 或 `false`，这两个值为常量，不区分大小写。

        $test = true;

+ 通常 **运算符** 所返回的 **boolean** 值结果会返回给控制流程
+ **强制转换**：可以使用 `(bool)` 或者 `(boolean)` 或者 `boolval()函数`来强制转换获取boolean值
+ **隐形转换**：当运算符、函数或者流程控制需要一个boolean参数时，会自动转换

####### 自动转换规则（以下都会转为false，其他值都为true）

+ boolean类型本身
+ 整型值：0
+ 浮点型：0.0（零）
+ 空字符串以及字符串	`'0'`
+ 不包含任何元素的数组
+ 特殊类型NULL（包括尚未赋值的变量）
+ 从空标记生成的 `SimpleXML` 对象