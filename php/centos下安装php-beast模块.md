## centos7下安装php-beast模块

### 第一步：在php中安装php-beast扩展

 基本流程如下：

	$ wget https://github.com/liexusong/php-beast/archive/master.zip
	$ unzip master.zip
	$ cd php-beast-master
	$ phpize
	$ ./configure
	$ make
	$ sudo make install

+ 执行phpize报错如何解决？

	如果产生了报以下错误

		Can't find PHP headers in /usr/include/php
		The php-devel package is required for use of this command

	意思为你没安装 php-devel 这个扩展包。**phpize是用来扩展php扩展模块的，通过phpize可以建立php的外挂模块**，phpize 是属于php-devel的内容，所以只要运行yum install php-devel 就行

		### 首先查看php版本
		php -v
		### 获取php版本后安装指定版本的devel扩展包
		如 php7.0版本执行
		yum install php70w-devel

+ 如何确认安装成功？

	查看php扩展目录中是否有该扩展, `make install` 完成后会有php模块目录，进去查看是否有 `beast.so` 文件

### 第二步：将PHP扩展加入PHP配置文件中

+ 修改php.ini 添加配置：extension=beast.so，运行php -m查看扩展是否添加成功
+ 重启服务器

### 第三步：使用php-beast扩展进行加密

下载的tarball中有tools目录，就是用来加密的工具。

其中有一个encode_file.php文件，即为用来加密单个文件的工具

	$ php encode_file.php --oldfile old_file_path --newfile new_file_path --encrypt DES --expire "2016-10-10 10:10:10"

有一个encode_files.php文件，用来加密文件夹的。

	### 先配置好configure.ini
	$ php encode_files.php

### 相关配置

	 beast.cache_size = size
	 beast.log_file = "path_to_log"
	 beast.log_user = "user"
	 beast.log_level = "debug"
	 beast.enable = On
beast.log_level支持参数：

 1. DEBUG
 2. NOTICE
 3. ERROR
 
支持的模块有：

 1. AES
 2. DES
 3. Base64

### 进行扩展开发
 本模块是开源的，所以可以对源代码进行私人定制。具体请参考：[作者的github](https://github.com/liexusong/php-beast)

### 参考

[PHP 源码加密模块 php-beast](https://www.huzs.net/?p=1694)  
[作者的github](https://github.com/liexusong/php-beast)







		