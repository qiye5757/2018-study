## composer使用

1. 安装

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