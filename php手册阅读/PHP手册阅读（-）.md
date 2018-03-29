## PHP手册阅读（-）  

1. [序言](#preface)
2. [简介](#overview)
3. [安装与配置](#install-config)
### <span id = "preface">序言</span>

+ PHP全称为"`Hypertext Preprocessor`",即为超文本语言预处理,在服务器端现将数据处理好转换为超文本即HTML，再发送给客户端。

### <span id = "overview">简介</span>
+ **PHP主要用于服务器脚本、命令行脚本、编写桌面应用程序。**
+ PHP用于服务器脚本主要得包括三个模块:web服务器、web客户端、php解析器（与Nginx交互以FastCGI形式，与Apache交互以apache_model形式/CGI/FastCGI形式）
+ PHP用于命令行脚本仅仅需要PHP解析器即可，依赖于unix、linux或者Task Scheduler
+ PHP编写桌面程序可以使用PHP-GTK来编写
+ **对于大多数服务器来说,PHP作为一个模块存在，还有一些服务器支持CGI，PHP作为一个CGI处理器来使用**

### <span id = "install-config">安装与配置</span>
+ SAPI是PHP提供给服务器（并不是所有）的一套接口，服务器通过调用接口将PHP作为一个模块使用，或者使用ISAPI，也可以将PHP作为一个CGI或者FastCGI程序来使用，这意味着你需要设置服务器来使用PHP的CGI程序来处理服务器上的所有PHP的文件请求
+ 