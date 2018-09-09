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

 >  yum install gcc\  
 >  gcc-c++\  
 >  pcre\  
 >  pcre-devel\
 >  zlib\  
 >  zlib-devel\  
 >  openssl\  
 >  openssl-devel

#### step3.2 配置

 支持特别多的选项，可以参考[Nginx之configure选项](http://www.cnblogs.com/jimodetiantang/p/9218745.html)

 > ./configure --prefix=/usr/local/nginx

#### step3.3 编译安装

 > make & make install

## nginx服务的控制

 在centos下有多种方式实现对nginx的控制

### 第一种：使用信号机制控制

 nginx在运行的时候，会保持一个主进程和多个worker process工作进程。使用信号机制对nginx进行控制只需要知道nginx主进程的PID即可。

#### 获取主进程PID

 方式有两种：

1. `ps -ef | grep nginx`
2. nginx运行的时候安装目录下的log文件中会生成一个nginx.pid,只需要执行`cat nginx.pid`即可获取

#### 控制nginx

 控制nginx服务，需要将信号发送给nginx主进程，发送的方式有两种：

1. `kill SIGNAL PID`命令，其中`SIGNAL`为信号，`PID`为nginx主进程的pid，举例：平滑停止Nginx服务 `kill -QUIT PID` 
2. `kill SIGNAL filepath`命令，其中`filepath`即为上面所说的`nginx.pid`路径

### 第二种：nginx二进制文件进行控制

 安装好nginx以后，再nginx安装目录下有个`sbin/nginx`二进制文件，使用`nginx -h`即可获取所有的参数

 