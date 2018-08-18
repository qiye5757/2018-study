1. [安装composer](#install)
2. [composer使用](#use)
3. [composer自动加载说明](#autoload)

## <span id="install">如何安装 Composer<span>
### 下载 Composer

 > 安装前请务必确保已经正确安装了 PHP。打开命令行窗口并执行 php -v 查看是否正确输出版本号。

 打开命令行并依次执行下列命令安装最新版本的 Composer：

	php -r "copy('https://install.phpcomposer.com/installer', 'composer-setup.php');"

	php composer-setup.php

	php -r "unlink('composer-setup.php');"
 执行第一条命令下载下来的 composer-setup.php 脚本将简单地检测 php.ini 中的参数设置，如果某些参数未正确设置则会给出警告；然后下载最新版本的 composer.phar 文件到当前目录。

 上述 3 条命令的作用依次是：

1. 下载安装脚本 － composer-setup.php － 到当前目录。
2. 执行安装过程。
3. 删除安装脚本。

### 局部安装
 上述下载 Composer 的过程正确执行完毕后，可以将 composer.phar 文件复制到任意目录（比如项目根目录下），然后通过 php composer.phar 指令即可使用 Composer 了！

### 全局安装
 全局安装是将 Composer 安装到系统环境变量 PATH 所包含的路径下面，然后就能够在命令行窗口中直接执行 composer 命令了。

##### Mac 或 Linux 系统：
 打开命令行窗口并执行如下命令将前面下载的 composer.phar 文件移动到 /usr/local/bin/ 目录下面：

	sudo mv composer.phar /usr/local/bin/composer
##### Windows 系统：
1. 找到并进入 PHP 的安装目录（和你在命令行中执行的 php 指令应该是同一套 PHP）。
2. 将 composer.phar 复制到 PHP 的安装目录下面，也就是和 php.exe 在同一级目录。
3. 在 PHP 安装目录下新建一个 composer.bat 文件，并将下列代码保存到此文件中。
	
		@php "%~dp0composer.phar" %*
最后重新打开一个命令行窗口试一试执行 composer --version 看看是否正确输出版本号。

### 最后
 提示：不要忘了经常执行 composer selfupdate 以保持 Composer 一直是最新版本哦！

## <span id="use">composer使用<span>

1. [安装composer](#install)

2. 如果在项目中有composer.json文件，执行

		php composer.phar install

3. 如果项目中有composer.json文件且已安装了全局模式
		
		composer install

4. 修改镜像源为国内

		composer config -g repo.packagist composer https://packagist.phpcomposer.com

### 概念详解

+ `composer.json`: 该文件包含了项目的依赖和其它的一些元数据。依赖是以JSON字符串定义的,类似于下面

		{
		    "require": {
		        "monolog/monolog": "1.0.*"
		    }
		}

	由此可知，依赖以键值对组成

	+ `键`：由供应商和包名称组成
	+ `值`：由版本号组成。

		> 确切的：1.0.2  
		> 范围： >=1.0 >=1.0,<2.0 >=1.0,<1.1|>=1.2  
		> 通配符：1.0.*
		> 本版本的最新的：~1.2。相当于>=1.2,<2.0. 

	+ 范围详解：`>`、`>=`、`<`、`<=`、`!=`、`,`为逻辑AND、`|`为逻辑或
	+ ~ 例子

		>  ~1.2 相当于 >=1.2,<2.0  
		>  ~1.2.3 相当于 >=1.2.3,<1.3

+ `composer.lock`：在安装依赖后，Composer 将把安装时确切的版本号列表写入 composer.lock 文件。
+ 两个文件的作用

	+ composer.json:定义项目所需要的依赖
	+ composer.lock:锁定当前依赖的版本号
	+ 可以使用 `composer update`命令强制更新composer.lock文件为composer.json定义的最新的依赖的版本，不过一般不推荐这么做，可以在该命令后指定需要更新的依赖

### 资源包

+ 资源包:每个项目都是一个包，每次定义 `require` 实际上是引入其他的资源包
+ 平台软件包:Composer 将那些已经安装在系统上，但并不是由 Composer 安装的包视为一个虚拟的平台软件包

### composer命令
 
 [composer命令详解](https://docs.phpcomposer.com/03-cli.html)

## <span id="autoload">composer 自动加载以及源码分析</span>

[参考](https://my.oschina.net/sallency/blog/893518)

### 自动加载使用

+ 文件加载：Files 是最简单的加载方式，这种方式不管加载的文件是否用到始终都会加载，而不是按需加载

		{
		    "require": {
		        "monolog/monolog": "1.0.*"
		    },
		    "autoload": {
		        "files": ["controllers/Controller.php"]
		    }
		}

+ classmap加载：classmap 引用的所有组合，都会在 install/update 过程中生成，并存储到 vendor/composer/autoload_classmap.php 文件中。这个 map 是经过扫描指定目录（同样支持直接精确到文件）中所有的 `.php` 和 `.inc` 文件里内置的类而得到的。

		{
		    "autoload": {
		        "classmap": ["src/", "lib/", "Something.php"]
		    }
		}

+ psr-0加载（已过时）：定义一个命名空间到实际路径的映射，映射到目标地址作为基目录再解析命名空间。NameSpace和Class Name中的下划线会被解析成路径。

		{
		    "autoload": {
		        "psr-0": { "Monolog\\": ["src/", "lib/"] }
		    }
		}

		psr-0 规范同时兼容了 PHP5.2 版本前的为命名空间风格

		比如有一个类名为 Foo_Bar_HelloWorld 的类文件存放在
		
		app/Foo/Bar/HelloWorld.php 文件中
		
		"psr-0": {
		    "Foo\\": "app/"
		}

+ psr-4加载：定义一个命名空间到实际路径的映射

		{
		    "autoload": {
		        "psr-4": { "Monolog\\": ["src/", "lib/"] }
		    }
		}

### 自动加载源码阅读

+ 首先执行composer命令，将当前配置加载到对应的autoload_*.php文件中。
+ 实例化 `ClassLoader` 类
+ 将 autoload_*.php 文件中属性加载到 `ClassLoader` 类中。
+ 注册 `ClassLoader` 类中的自动加载
+ 将以 `文件加载` 的内容引入进来

+ classLoader类的自动加载

	+ 先判断 `classmap` 类中是否存在
	+ 再通过 `psr-4` 和 `psr-0` 进行检测