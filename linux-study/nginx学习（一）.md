# nginx学习（一）之入门

## nginx源码安装

### step1：下载源码

 最好下载在`usr/local/src`目录下，方便自己也方便别人，然后下载，我这是下载的最新的稳定版本

> wget http://nginx.org/download/nginx-1.14.0.tar.gz

### step2:解压

> tar -zxvf nginx-1.14.0.tar.gz

### step3:安装

 可以参考这篇文章[CentOS 7 下安装 Nginx](https://www.cnblogs.com/liujuncm5/p/6713784.html)

#### step3.1 当然首先得安装一些必须的软件

##### gcc安装

 >  yum install gcc gcc-c++ automake

##### PCRE pcre-devel 安装
PCRE(Perl Compatible Regular Expressions) 是一个Perl库，包括 perl 兼容的正则表达式库。nginx 的 http 模块使用 pcre 来解析正则表达式，所以需要在 linux 上安装 pcre 库，pcre-devel 是使用 pcre 开发的一个二次开发库。nginx也需要此库。命令： 
 >  yum install pcre  pcre-devel

##### zlib 安装
zlib 库提供了很多种压缩和解压缩的方式， nginx 使用 zlib 对 http 包的内容进行 gzip ，所以需要在 Centos 上安装 zlib 库。

 > yum install -y zlib zlib-devel

##### OpenSSL 安装
OpenSSL 是一个强大的安全套接字层密码库，囊括主要的密码算法、常用的密钥和证书封装管理功能及 SSL 协议，并提供丰富的应用程序供测试或其它目的使用。
nginx 不仅支持 http 协议，还支持 https（即在ssl协议上传输http），所以需要在 Centos 安装 OpenSSL 库。

 > yum install -y openssl openssl-devel

#### step3.2 配置

 支持特别多的选项，使用`./configure --help`可以获取所有的配置项，以下为nginx1.14.0常见的通用的配置项，具体可以参考[nginx的configure各个模块详解](https://blog.csdn.net/qq_41036832/article/details/80695981)来查看。

 > ./configure --prefix=/usr/local/nginx

##### 通用配置项

+ `--prefix=<path>`：Nginx 安装的根路径，所有其他的路径都要依赖于该选项。默认为`/usr/local/nginx/`
+ `--sbin-path=<path>`：指定 Nginx 二进制文件的路径。如果没有指定,默认情况下该文件被命名`prefix/sbin/nginx`。
+ `--modules-path=<>`:定义一个将安装的nginx的的动态模块的目录。情况默认下使用该`prefix/modules`目录。
+ `--conf-path=<path>`：如果在命令行没有指定配置文件`-c /filename`，那么将会通过这里指定路径，Nginx 将会去那里查找它的配置文件。默认情况下为`prefix/conf/`目录下。
+ `--error-log-path=<path>`：在Nginx配置文件中没有定义`error_log`指令的情况下，指定默认的访问日志的路径，默认为`prefix/logs/error.log`。
+ `--pid-path=<path>`：在Nginx配置文件中没有指定`pid`指令的情况下，指定的默认的nginx.pid路径，默认为`prefix/logs/nginx.pid`。nginx.pid保存了当前运行的Nginx服务的进程号。
+ `--lock-path=<path>`：共享存储器互斥锁文件的路径。默认为`prefix/logs/nginx.log`
+ `--user=<user>`：在nginx.conf文件中未定义的情况下，指定Nginx服务器进程的属主用户。默认为nobody
+ `--group=<group>`：在nginx.conf文件中未定义的情况下，指定Nginx服务器进程的属主用户组。默认为nobody
+ `--build=name`:设置一个可选的nginx的的的构建名称。
+ `--builddir=path`:设置编译时的目录
+ `--with-threads`:使用线程池
+ `--with-file-aio`：为 FreeBSD 4.3+ 和 Linux 2.6.22+ 系统启用异步 I/O。
+ `--with-debug`：这个选项用于启用调试日志。在生产环境的系统中不推荐使用该选项。
+ `--with-select_module`:启用select信号处理模式。如果平台似乎不支持更合适的方法，例如kqueue，epoll或/ dev / poll，则会自动构建此模块。
+ `--without-select_module`:禁用select信号处理模式。
+ `--with-poll_module`：启用poll模块
+ `--without-poll_module`：禁用poll模块

#### step3.3 编译安装

 > make & make install

### 编写nginx启动脚本

> cat /usr/lib/systemd/system/nginx.service 

	[Unit]
	Description=The NGINX HTTP and reverse proxy server
	After=syslog.target network.target remote-fs.target nss-lookup.target
	
	[Service]
	Type=forking     
	PIDFile=/usr/local/nginx/logs/nginx.pid    
	ExecStartPre=/usr/local/nginx/sbin/nginx -t    
	ExecStart=/usr/local/nginx/sbin/nginx   
	ExecReload=/usr/bin/kill -HUP $MAINPID    
	ExecStop=/usr/bin/kill -QUIT $MAINPID 
	PrivateTmp=true       
	
	[Install]
	WantedBy=multi-user.target

> systemctl daemon-reload 

#### 设置开机启动

> systemctl enable nginx

## nginx服务的控制

 在centos下有多种方式实现对nginx的控制

### 第一种：使用信号机制控制

 nginx在运行的时候，会保持一个主进程和多个worker process工作进程。使用信号机制对nginx进行控制只需要知道nginx主进程的PID即可。

#### 获取主进程PID

 方式有两种：

1. `ps -ef | grep nginx`
2. nginx运行的时候安装目录下的logs文件中会生成一个nginx.pid,只需要执行`cat nginx.pid`即可获取

#### 控制nginx

 控制nginx服务，需要将信号发送给nginx主进程，发送的方式有两种：

1. `kill -s SIGNAL PID`命令，其中`SIGNAL`为信号，`PID`为nginx主进程的pid，举例：平滑停止Nginx服务 `kill -s QUIT PID` 
2. `kill -s SIGNAL filepath`命令，其中`filepath`即为上面所说的`nginx.pid`路径

##### Nginx可接受的信号

1. `TERM或INT`：快速停止Nginx服务
2. `QUIT`：平缓停止Nginx服务
3. `HUP`：平滑重启Nginx服务，即使用心得配置文件启动进程，之后平滑停止原有进程
4. `USR1`：重新打开日志文件，常用有日志切割
5. `USR2`：平滑升级Nginx服务，即使用版本的Nginx文件启动服务，之后平滑停止原有的Nginx进程
6. `WINCH`：平滑停止worker process，用于Nginx服务器平滑升级

### 第二种：nginx二进制文件进行控制

 安装好nginx以后，再nginx安装目录下有个`sbin/nginx`二进制文件，使用`nginx -h`即可获取所有的参数.

+ `-s` ：向nginx发送 `stop`、`quit`、`reopen`、`reload`信号

## Nginx配置文件

 默认的配置文件都放在安装目录的conf中，主配置文件为nginx.conf。以下为nginx.conf文件的基本结构

	···											#全局块
	
	events {									#events块

	}

	http{										#http块
			
		···										#http全局块
		
		server{									#server块
				
			···									#server全局块

			location [PATTERN]{ 				#location块
				···
			}

			location [PATTERN]{ 				#location块
				···
			}
		}

		server{									#server块
			...
		}

		···										#http全局块
	}

### 配置文件目录详解

 nginx.conf共由三部分组成，分别为`全局块`、`event块`、`http块`三部分组成。`http块`由`http全局块`、多个`server块`两部分组成，每个`server块`由`server全局块`和多个`location块`组成。在同一配置块中嵌套的配置块，各个之间并不存在次序关系。  

 配置文件中支持大量的指令，绝大多数的指令不是特定属于某一块的。**如果某个指令在两个不同层级的块中同时存在，则采用`就近原则`，即以较低层次块中的配置为准**

#### 全局快

 主要设置一些影响Nginx服务器整体运行的配置指令，这些指令的作用域是Nginx服务器全局。  
 通常包括配置运行Nginx服务器的用户(组)、允许生成的worker process数、nginx进行PID的存放位置、日志的存放位置和类型以及配置文件的引入等等。

#### events块

 events块涉及的指令主要影响Nginx服务器与用户的网络连接。  
 常用到的设置包括是否开启对多worker process下网络连接进行序列化，是否允许同时接受多个网络连接，选取哪种事件驱动模型处理连接请求，每个worker process同时支持的最大连接数等。

#### http块

 http块是Nginx服务器配置中的重要部分，代理、缓存和日志定义等绝大多数的功能和第三方的模块的配置都可以放在这个模块中。  
 可以在http全局块中配置的指令包括文件引入、MIME-Type定义、日志自定义、是否使用sendfile传输文件、连接超时时间、单连接请求数上限等。

#### server块

 虚拟主机技术使得Nginx服务器可以在一台服务器上只运行一组Nginx进程，就可以运行多个网站。server块就是完成这个功能的，每个server块相当于一台虚拟主机。  
 在server块中的最常见的两个配置项是本虚拟机主机的监听配置和本虚拟机的名称或者IP配置

#### location块

 每个server块中可以包含多个location块。严格意义上来将，location其实是server块的一个指令。  
 这些location块的作用，基于Nginx服务器收到的请求字符串，对除虚拟主机名称以外的字符串进行匹配等等。