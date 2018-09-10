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


 