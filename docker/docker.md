## docker学习
Docker 容器通过 Docker 镜像来创建。

容器与镜像的关系类似于面向对象编程中的对象与类。

### 基础概念

+ Docker 镜像是用于创建 Docker 容器的模板。
+ Docker 容器是独立运行的一个或一组应用。
+ Docker 客户端通过命令行或者其他工具使用 Docker API (https://docs.docker.com/reference/api/docker\_remote\_api) 与 Docker 的守护进程通信。
+ Docker 主机：一个物理或者虚拟的机器用于执行 Docker 守护进程和容器。
+ Docker Registry:一个Docker Registry包含多个仓库，一个仓库包含多个标签。每个标签代表一个镜像
Docker Hub(https://hub.docker.com) 提供了庞大的镜像集合供使用。
+ Docker Machine是一个简化Docker安装的命令行工具，通过一个简单的命令行即可在相应的平台上安装Docker，比如VirtualBox、 Digital Ocean、Microsoft Azure。

### docker安装错误解决

1. 提示boot2docker ISO安装错误

     > 这是因为下载失败，在C://用户/用户名/.docker/machine/cache中删除tmp文件，并手动[下载](https://github.com/boot2docker/boot2docker/releases/download/v17.05.0-ce/boot2docker.iso)放到该目录下即可.

2. 提示This computer doesn’t have VT-X/AMD-v enabled. Enabling it in the BIOS is mandatory

     > 进入对应电脑版本bios，开启虚拟化即可

3. window下端口映射失败

	> docker在window上是先安装了一个linux虚拟机，docker在linux运行，使用docker-machine ip default获取linux的ip地址

### docker镜像使用

1. 获取镜像 

	> docker pull [选项] [Docker Registry 地址[:端口号]/]仓库名[:标签]

2. 列出镜像

	> `docker image ls` 列出所有本地已存在的镜像。列表包含了仓库名、标签、镜像 ID、创建时间 以及 所占用的空间

    + docker镜像只有在本地的时候是解压缩状态，在仓库以及传输过程中使用的都是以压缩文件的形式存在。而且由于docker镜像是分层处理的，所以实际存储空间可能更小。  
    
		> 可以使用 `docker system df` 获取镜像和容器在本地实际存储所占用的空间

	+ 无标签镜像也被称为 虚悬镜像(dangling image) 
		
		> `docker image ls -f dangling=true` 专门显示虚悬镜像

	+ 为了加速镜像构建、重复利用资源，Docker 会利用 中间层镜像

		> `docker image ls -a` 显示所有镜像列表

	+ 可以列出部分镜像

		> `docker image ls 仓库名` 列出该仓库的所有镜像
		> `docker image ls 镜像名` 列出该镜像的详细信息
		> `docker image ls -f 参数` 使用过滤器列出镜像

	+ 以特殊格式显示，--filter 配合 -q 产生出指定范围的 ID 列表，然后送给另一个 docker 命令作为参数，从而针对这组实体成批的进行某种操作的做法在 Docker 命令行使用过程中非常常见
		
		> `docker image ls -q` 列出所有镜像ID列表
		> `docker image ls --format go模板` 显示定制的列表数据

3. 删除镜像

	> `docker image rm [选项] <镜像1> [<镜像2> ...]` <镜像> 可以是 镜像短 ID、镜像长 ID、镜像名 或者 镜像摘要

	+ Untagged 和 Deleted，如果镜像没有被依赖的话Deleted，如果被依赖的话，只是会删除标签
	+ 如果有对应镜像的容器存在的话，先删除容器再删除镜像

### docker操作容器

1. 启动容器（新建重启或者已停止的重启）

	+ 新建并重启

		> docker run 

	+ 启动已终止容器

		> docker container run

2. 守护态运行

	更多的时候，需要让 Docker 在后台运行而不是直接把执行命令的结果输出在当前宿主机下。此时，可以通过添加 -d 参数来实现。
	
	+ 容器是否会长久运行，是和 docker run 指定的命令有关，和 -d 参数无关

3. 终止容器

	> docker container stop

4. 进入容器

	> docker attach 命令或 docker exec 命令，推荐大家使用 docker exec

5. 导出和导入容器

	+ docker export 7691a814370e > ubuntu.tar 导出
	+ 用户既可以使用 docker load 来导入镜像存储文件到本地镜像库，也可以使用 docker import 来导入一个容器快照到本地镜像库。这两者的区别在于容器快照文件将丢弃所有的历史记录和元数据信息（即仅保存容器当时的快照状态），而镜像存储文件将保存完整记录，体积也要大。此外，从容器快照文件导入时可以重新指定标签等元数据信息。

6. 删除容器

	> docker container rm 删除一个终止状态的容器
	> docker container prune 清除所有的终止状态的容器

### 使用网络

1. docker访问容器

	> 容器中可以运行一些网络应用，要让外部也可以访问这些应用，可以通过 -P 或 -p 参数来指定端口映射。
	
	+ 使用 docker port 来查看当前映射的端口配置，也可以查看到绑定的地址
	+ 容器有自己的内部网络和 ip 地址（使用 docker inspect 可以获取所有的变量，Docker 还可以有一个可变的网络配置。）
	+ `-p` 标记可以多次使用来绑定多个端口
