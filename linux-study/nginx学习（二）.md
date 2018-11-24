# nginx学习（二）之conf文件配置学习

## conf学习

 nginx可以有多个配置文件，其中主配置文件为nginx.conf。以下为nginx官方网站上的[配置文件示例](https://www.nginx.com/resources/wiki/start/topics/examples/fullexample2/),结合着以下配置文件、`Nginx高性能Web服务器详解`这本书以及服务器自带的配置文件对常见的配置文件进行学习。（对配置的深入学习需要大量的实践）

 > 在Nginx配置文件中，每一条指令配置都必须以分号结束

### 常用配置项

#### 配置运行Nginx服务组用户（组） `全局块`

语法格式如下：

	user user [group]

+ user为指定可以运行Nginx服务器的用户
+ group为指定可以运行Nginx服务器的用户组

#### 允许生成的worker process数  `全局块`

语法格式如下：

    work_processes number | auto
    
+ number，指定Nginx进程最多可以产生的worker process数
+ auto，设置此值，Nginx将自动检测

worker process数是Nginx实现并发处理服务的关键所在。

#### 配置Nginx进程PID存放路径  `全局块`

语法格式如下：

    pid file;
    
其中，file指定存放路径和文件名称。可以为绝对路径，也可以为Nginx安装目录下绝对路径，如

    pid sbin/web_nginx
    
就是将pid命名为web_nginx,放在sbin目录下。

#### 配置错误日志的存放路径    `全局块/http块/server块/location`

语法格式如下：

    error_log file | stderr [debug | info | notice | warn | error | crit | alert | emery]
    
+ 可以输出到file中或者stderr
+ 日志的级别从低到高，设置某一级别的时候，高级别的也会显示
+ 想要显示debug，必须--enable-debug

#### 配置文件的引入 `所有地方`

语法格式如下：

    include file;
    

语法格式如下：

    accept_mutex on | off






 