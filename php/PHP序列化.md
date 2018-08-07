### PHP序列化详解

 PHP序列化使用的是 `serialize()`函数来进行序列化，`serialize()`函数定义如下：

    string serialize ( mixed $value )

 除了资源类型以外的都可以被序列化。

##### Integer类型
 
 Integer类型序列化: `i:值;`

	<?php 
		
		$test = 1;
		
		echo serialize($test); // i:1;

##### Float类型

 float类型序列化： `d:值;`
		
	<?php 
	
		$test = 1.01;
		
		echo serialize($test); // d:1.01;

##### String类型

 String类型序列化： `s:长度:值;`

	<?php 
		
		$test = '1.01';
		
		echo serialize($test); // s:4:'1.01';

##### Boolean类型

 Boolean类型序列化： `b:(0或者1);`
	
	<?php 
		
		$test = true;
		
		echo serialize($test); // b:1;

##### Null类型
    
 Null类型序列化： `N;`

	<?php 
		
		$test = Null;
		
		echo serialize($test); // N;

##### array类型

 array类型序列化： `a:个数:{key的值;value的值;key的值;value的值; ......}`

    <?php

		$test = array(
		   1,
		   2
		);
	
	   echo serialize($test); // a:2:{i:0;i:1;i:1;i:2;}

##### Object类型

 object类型序列化： `O:类名长度：类名：变量个数{key;value等等}`。**这里的key;value只包括对象所有变量值。**
	
    <?php
	
		class test{
		  private $test = "111";
		  protected $test2 = "222";
		  public $test3 = "333";
		  const TEST = "111";
		  public static $test4 = "444";
		
		  public function test5(){
		    echo 111;
		  }
		
		  protected function test6(){
		    echo 222;
		  }
		
		  private function test7(){
		    echo 333;
		  }
		
		  public static function test8(){
		    echo 444;
		  }
		}
	
		$test = new test;
	
	    echo serialize($test); 

    //O:4:"test":3{s:10:" test test";s:3:"111";s:8:" * test2";s:3:"222";s:5:"test3";s:3:"333"}

+ `private类型`的变量名以 `空格 类名 空格 变量名` 存储
+ `protected类型`的变量名以 `空格 * 空格 变量名` 存储p
+ 将变量存储到session中，session会自动进行序列化处理保存到文件中,读取的时候又会自动反序列化

	> session.serialize_handler = php  //表明session的默认序列话引擎使用的是php序列话引擎

		<?php

		class test{
		  private $test = "111";
		  protected $test2 = "222";
		  public $test3 = "333";
		  const TEST = "111";
		  public static $test4 = "444";
		
		  public function test5(){
		    echo 111;
		  }
		
		  protected function test6(){
		    echo 222;
		  }
		
		  private function test7(){
		    echo 333;
		  }
		
		  public static function test8(){
		    echo 444;
		  }
		}
	
		$_SESSION['test'] = new test();

		echo $_SESSION['test']; // 111;

1. __sleep(): 在序列化对象的时候调用

2. __wake(): 在反序列化对象的时候调用