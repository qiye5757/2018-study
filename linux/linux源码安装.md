基本上软件安装的方法可以分为两大类，分别是：

+ 直接以源代码通过编译来安装与升级；（源码安装）
+ 直接以编译好的 binary program 来安装与升级。（yum、apt-get等）

## Linux 源码安装

1. [引子](#one)
2. [简介](#two)
3. [gcc简单用法](#three)
4. [make](#four)
5. [使用源码安装的建议](#five)
### <span id="one">引子</span>

 Linux系统下的可执行文件为 `二进制文件(binary program)`。  
 可以使用 `file` 命令来获取文件的格式，如果为可执行的文件会显示可执行文件类别 （ELF 64-bit LSB executable）， 同时会说明是否使用动态函数库 （shared libs），而如果是一般的 script ，那他就会显示出 text executables 之类的字样！  
 生成 binary program 文件的过程如下:

1. 编写源代码（就是人看的，机器并不认识）
2. 使用编译器调用系统已有的函数库对源代码进行编译。如linux上c语言的默认编译器为gcc。
3. 可执行文件，即使用编译器生成的可执行文件  

### <span id="two">简介</span>

#### 函数库

 内核中包含，系统中包含的库文件。编写程序的时候只需要引用就可以使用函数库的功能。

#### 什么是makefile和configure

 make是一个软件，当执行 `make` 的时候会搜寻makefile（或者Makefile）。而 Makefile 里面则记录了源代码如何编译的详细信息！  
 通常软件开发商都会写一支侦测程序来侦测使用者的作业环境， 以及该作业环境是否有软件开发商所需要的其他功能，该侦测程序侦测完毕后，就会主动的创建这个 Makefile 的规则文件啦！通常这支侦测程序的文件名为 configure 或者是 config 。  
 一般来说，侦测程序会侦测的数据大约有下面这些：

+ 是否有适合的编译器可以编译本软件的程序码；
+ 是否已经存在本软件所需要的函数库，或其他需要的相依软件；
+ 操作系统平台是否适合本软件，包括 Linux 的核心版本；
+ 核心的表头定义文件 （header include） 是否存在 （驱动程序必须要的侦测）

#### 什么是Tarball

 所谓的 Tarball 文件，其实就是将软件的所有源代码文件先以 tar 打包，然后再以压缩技术来压缩，通常最常见的就是以 gzip 来压缩了。因为利用了 tar 与 gzip 的功能，所以 tarball 文件一般的扩展名就会写成 .tar.gz 或者是简写为 .tgz 啰！不过，近来由于 bzip2 与 xz 的压缩率较佳，所以 Tarball 渐渐的以 bzip2 及 xz 的压缩技术来取代 gzip 啰！因此文件名也会变成 .tar.bz2, .tar.xz 之类的哩。所以说， Tarball 是一个软件包， 你将他解压缩之后，里面的文件通常就会有：

+ 原始程序码文件；
+ 侦测程序文件 （可能是 configure 或 config 等文件名）；
+ 本软件的简易说明与安装说明 （INSTALL 或 README）

#### 源码安装对的基本流程
1. 将 Tarball 由厂商的网页下载下来；
2. 将 Tarball 解开，产生很多的源代码文件；
3. 开始以 gcc 进行源代码的编译 （会产生目标文件 object files）；
4. 然后以 gcc 进行函数库、主、副程序的链接，以形成主要的 binary file；
5. 将上述的 binary file 以及相关的配置文件安装至自己的主机上面。

 上面第 3, 4 步骤当中，我们可以通过 make 这个指令的功能来简化他， 所以整个步骤其实是很简单的啦！只不过你就得需要至少有 gcc 以及 make 这两个软件在你的 Linux 系统里面才行喔！

### <span id="three">gcc的简单用法</span>

	# 仅将源代码编译成为目标文件，并不制作链接等功能：
	[root@study ~]# gcc -c hello.c
	# 会自动的产生 hello.o 这个文件，但是并不会产生 binary 可执行文件。
	
	# 在编译的时候，依据作业环境给予最优化执行速度
	[root@study ~]# gcc -O hello.c -c
	# 会自动的产生 hello.o 这个文件，并且进行最优化喔！
	
	# 在进行 binary file 制作时，将链接的函数库与相关的路径填入
	[root@study ~]# gcc sin.c -lm -L/lib -I/usr/include
	# 这个指令较常下达在最终链接成 binary file 的时候，
	# -lm 指的是 libm.so 或 libm.a 这个函数库文件；
	# -L 后面接的路径是刚刚上面那个函数库的搜寻目录；
	# -I 后面接的是源代码内的 include 文件之所在目录。
	
	# 将编译的结果输出成某个特定文件名
	[root@study ~]# gcc -o hello hello.c
	# -o 后面接的是要输出的 binary file 文件名
	
	# 在编译的时候，输出较多的讯息说明
	[root@study ~]# gcc -o hello hello.c -Wall
	# 加入 -Wall 之后，程序的编译会变的较为严谨一点，所以警告讯息也会显示出来！

### <span id="four">make</span>

#### 为什么要用make

 make 有这些好处：

+ 简化编译时所需要下达的指令；（要不然使用gcc得敲很多指令）
+ 若在编译完成之后，修改了某个源代码文件，则 make 仅会针对被修改了的文件进行编译，其他的 object file 不会被更动；
+ 最后可以依照相依性来更新 （update） 可执行文件

#### makefile写法

基本的 makefile 规则是这样的：

	标的（target）: 目标文件1 目标文件2
	&lt;tab&gt;   gcc -o 欲创建的可执行文件 目标文件1 目标文件2

他的规则基本上是这样的：

+ 在 makefile 当中的 # 代表注解；
+ <tab> 需要在命令行 （例如 gcc 这个编译器指令） 的第一个字符；
+ 标的 （target） 与相依文件（就是目标文件）之间需以“:”隔

我们可以再借由 shell script 那时学到的“变量”来更简化 makefile。变量的基本语法为：

+ 变量与变量内容以“=”隔开，同时两边可以具有空格；
+ 变量左边不可以有 <tab> ，例如上面范例的第一行 LIBS 左边不可以是 <tab>；
+ 变量与变量内容在“=”两边不能具有“:”；
+ 在习惯上，变量最好是以“大写字母”为主；
+ 运用变量时，以 ${变量} 或 $（变量） 使用；
+ 在该 shell 的环境变量是可以被套用的，例如提到的 CFLAGS 这个变量！
+ 在命令行界面也可以给予变量。

环境变量取用的规则是这样的：

+ make 命令行后面加上的环境变量为优先；
+ makefile 里面指定的环境变量第二；
+ shell 原本具有的环境变量第三。

此外，还有一些特殊的变量需要了解的喔：

	$@：代表目前的标的（target）

例子：

	main: main.o haha.o sin_value.o cos_value.o
     	 gcc -o main main.o haha.o sin_value.o cos_value.o -lm
	clean:
     	 rm -f main main.o haha.o sin_value.o cos_value.o

    可以使用 `make main`和 `make clean`命令

### <span id="five">使用源码安装的建议</span>

#### 使用源代码管理软件所需要的基础软件

+ gcc 或 cc 等 C 语言编译器 （compiler）
+ make 及 autoconfig 等软件
+ 需要 Kernel 提供的 Library 以及相关的 Include 文件

 如果没这些软件的话，可以这样做：

+ 如果是要安装 gcc 等软件发展工具，请使用“ yum groupinstall "Development Tools" ”
+ 若待安装的软件需要图形接口支持，一般还需要“ yum groupinstall "X Software Development" ”
+ 若安装的软件较旧，可能需要“ yum groupinstall "Legacy Software Development" ”

#### 安装步骤

所以整个安装的基础动作大多是这样的：

+ 取得原始文件：将 tarball 文件在 /usr/local/src 目录下解压缩；
+ 取得步骤流程：进入新创建的目录下面，去查阅 INSTALL 与 README 等相关文件内容 （很重要的步骤！）；
+ 相依属性软件安装：根据 INSTALL/README 的内容察看并安装好一些相依的软件 （非必要）；
创建 makefile：以自动侦测程序 （configure 或 config） 侦测作业环境，并创建 Makefile 这个文件；
+ 编译：以 make 这个程序并使用该目录下的 Makefile 做为他的参数配置文件，来进行 make （编译或其他） 的动作；
+ 安装：以 make 这个程序，并以 Makefile 这个参数配置文件，依据 install 这个标的 （target） 的指定来安装到正确的路径！

大部分的 tarball 软件之安装的指令下达方式：

1. ./configure： 这个步骤就是在创建 Makefile 这个文件啰！通常程序开发者会写一支 scripts 来检查你的 Linux 系统、相关的软件属性等等，这个步骤相当的重要， 因为未来你的安装信息都是这一步骤内完成的！另外，这个步骤的相关信息应该要参考一下该目录下的 README 或 INSTALL 相关的文件！

2. make clean： make 会读取 Makefile 中关于 clean 的工作。这个步骤不一定会有，但是希望执行一下，因为他可以去除目标文件！因为谁也不确定源代码里面到底有没有包含上次编译过的目标文件 （*.o） 存在，所以当然还是清除一下比较妥当的。 至少等一下新编译出来的可执行文件我们可以确定是使用自己的机器所编译完成的嘛！

3. make： make 会依据 Makefile 当中的默认工作进行编译的行为！编译的工作主要是进行 gcc 来将源代码编译成为可以被执行的 object files ，但是这些 object files 通常还需要一些函数库之类的 link 后，才能产生一个完整的可执行文件！使用 make 就是要将源代码编译成为可以被执行的可可执行文件，而这个可可执行文件会放置在目前所在的目录之下， 尚未被安装到预定安装的目录中；

4. make install ：通常这就是最后的安装步骤了，make 会依据 Makefile 这个文件里面关于 install 的项目，将上一个步骤所编译完成的数据给他安装到预定的目录中，就完成安装啦！

请注意，上面的步骤是一步一步来进行的，而其中只要一个步骤无法成功，那么后续的步骤就完全没有办法进行的！ 因此，要确定每一的步骤都是成功的才可以！

#### 建议

基本上，在默认的情况下，原本的 Linux distribution 释出安装的软件大多是在 /usr 里面的，而使用者自行安装的软件则建议放置在 /usr/local 里面。这是考虑到管理使用者所安装软件的便利性。

通常我们会建议大家将自己安装的软件放置在 /usr/local 下，至于源代码 （Tarball）则建议放置在 /usr/local/src （src 为 source 的缩写）下面啊。

+ 最好将 tarball 的原始数据解压缩到 /usr/local/src 当中；

+ 安装时，最好安装到 /usr/local 这个默认路径下；

+ 考虑未来的反安装步骤，最好可以将每个软件单独的安装在 /usr/local 下面；

+ 为安装到单独目录的软件之 man page 加入 man path 搜寻： 如果你安装的软件放置到 /usr/local/software/ ，那么 man page 搜寻的设置中，可能就得要在 /etc/man_db.conf 内的 40~50 行左右处，写入如下的一行：

   	> MANPATH_MAP /usr/local/software/bin /usr/local/software/man

这样才可以使用 man 来查询该软件的线上文件啰！

### 重点回顾

+ 源代码其实大多是纯文本文件，需要通过编译器的编译动作后，才能够制作出 Linux 系统能够认识的可执行的 binary file ；
+ 开放源代码可以加速软件的更新速度，让软件性能更快、漏洞修补更实时；
+ 在 Linux 系统当中，最标准的 C 语言编译器为 gcc ；
+ 在编译的过程当中，可以借由其他软件提供的函数库来使用该软件的相关机制与功能；
+ 为了简化编译过程当中的复杂的指令输入，可以借由 make 与 makefile 规则定义，来简化程序的更新、编译与链接等动作；
+ Tarball 为使用 tar 与 gzip/bzip2/xz 压缩功能所打包与压缩的，具有源代码的文件；
+ 一般而言，要使用 Tarball 管理 Linux 系统上的软件，最好需要 gcc, make, autoconfig, kernel source, kernel header 等前驱软件才行，所以在安装 Linux 之初，最好就能够选择 Software development 以及 kernel development 之类的群组；
+ 函数库有动态函数库与静态函数库，动态函数库在升级上具有较佳的优势。动态函数库的扩展名为 .so 而静态则是 .a ；
+ patch 的主要功能在更新源代码，所以更新源代码之后，还需要进行重新编译的动作才行；
可以利用 ldconfig 与 /etc/ld.so.conf /etc/ld.so.conf.d/*.conf 来制作动态函数库的链接与高速缓存！
+ 通过 MD5/SHA1/SHA256 的编码可以判断下载的文件是否为原本厂商所释出的文件。