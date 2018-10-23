## PHP常用技术之session使用

 由于Http协议是无状态协议，即每次请求都相当于一次新的请求。为了达到一些类似于用户登录等的效果，需要访问网站的时候“有记忆”,基于此诞生了cookie，前面已经学习过了。cookie的缺点是保存客户端，不安全。基于此，session诞生了，session和cookie一样都是将用户认证等的数据保存到文件中，只不过区别是一个是在客户端，一个是在服务端。当然肯定是服务端更安全了。

### 一、session底层实现简介，[详细点击这篇文章](https://blog.csdn.net/hao508506/article/details/52422025)

 PHP是以扩展形式加载进来的，扩展一般有以下几个步骤

#### 扩展模块初始化

 session的初始化分以下三个步骤

1. 注册$_SESSION超全局变量
2. 读取ini中相关的配置
3. 注册`SessionHandler`和`SessionHandlerInterface`这两个Class(PHP5.4实现)

#### session请求时的准备

 分以下几个步骤

1. 初始化session相关的全局变量
2. 根据ini的`save_handler`配置来，确定使用哪种方式来存储与读取session数据（即`会话保存管理器`）。
3. session的序列化和反序列化选择，在前面[PHP常见概念（六）之序列化](https://www.cnblogs.com/qiye5757/p/9435722.html)提到过，选择哪种方式来对session数据读取时进行序列化处理。
4. `save_handler`和`serializer` 如果有一个不成功，则无法向下进行
5. 如果ini 中 `session.auto_start` 为1 自动`session_start` 

#### session_start

 使用session_start()会经过以下几个步骤

1. 检测当前的session是否开启了，如果开启则开启，具体就是将`php_session_status`的值变为`active`
2. 获取`session_id`。会通过多种方式来获取session。如果获取到的`session_id`格式不对则会初始化为空。
3. 执行`php_session_initialize`完成session的初始化工作。 

		static void php_session_initialize(TSRMLS_D) /* {{{ */  
		{  
		    char *val = NULL;  
		    int vallen;  
	  
	    // 第一步，验证PS(mod) 是否存在  
	    if (!PS(mod)) {  
	        php_error_docref(NULL TSRMLS_CC, E_ERROR, "No storage module chosen - failed to initialize session");  
	        return;  
	    }  
	    // 第二步，打开session文件    
	    /* Open session handler first */  
	    if (PS(mod)->s_open(&PS(mod_data), PS(save_path), PS(session_name) TSRMLS_CC) == FAILURE) {  
	        php_error_docref(NULL TSRMLS_CC, E_ERROR, "Failed to initialize storage module: %s (path: %s)", PS(mod)->s_name, PS(save_path));  
	        return;  
	    }  
	  
	    // 第三步，判断session_id,如果session_id 不存在，创建一个  
	    /* If there is no ID, use session module to create one */  
	    if (!PS(id)) {  
	        PS(id) = PS(mod)->s_create_sid(&PS(mod_data), NULL TSRMLS_CC);  
	        if (!PS(id)) {  
	            php_error_docref(NULL TSRMLS_CC, E_ERROR, "Failed to create session ID: %s (path: %s)", PS(mod)->s_name, PS(save_path));  
	            return;  
	        }  
	        if (PS(use_cookies)) {  
	            PS(send_cookie) = 1;  
	        }  
	    }  
	    // 第四步 session.use_strict_mode指定是否将使用严格的会话ID模式。如果该模式被激活，模块不接受未初始化会话ID。  
	    // 如果未初始化会话ID从浏览器发送的，新的会话ID被发送到浏览器。  
	    // 应用程序通过会议通过严格的方式保护会话固定。默认为0 （禁用） 。  
	    /* Set session ID for compatibility for older/3rd party save handlers */  
	    if (!PS(use_strict_mode)) {  
	        php_session_reset_id(TSRMLS_C);  
	        PS(session_status) = php_session_active;  
	    }  
	  
	    // 第五步 读取session数据到val中  
	    /* Read data */  
	    php_session_track_init(TSRMLS_C);   // 无条件地摧毁现有的session 数组，可能是脏数据  
	    if (PS(mod)->s_read(&PS(mod_data), PS(id), &val, &vallen TSRMLS_CC) == FAILURE) {  
	        /* Some broken save handler implementation returns FAILURE for non-existent session ID */  
	        /* It's better to raise error for this, but disabled error for better compatibility */  
	        /* 
	        php_error_docref(NULL TSRMLS_CC, E_NOTICE, "Failed to read session data: %s (path: %s)", PS(mod)->s_name, PS(save_path)); 
	        */  
	    }  
	    // 如果使用严格会话ID模式，在session不活跃状态下重置    
	    // 这里涉及到session安全机制，详细可以看http://php.net/manual/zh/session.security.php  
	    /* Set session ID if session read didn't activated session */  
	    if (PS(use_strict_mode) && PS(session_status) != php_session_active) {  
	        php_session_reset_id(TSRMLS_C);  
	        PS(session_status) = php_session_active;  
	    }  
	    // 第六步 对文件中读取到的session数据进行反序列化  
	    if (val) {  
	        php_session_decode(val, vallen TSRMLS_CC);  
	        str_efree(val);  
	    }  
	    // 第七步 session全局配置参数的值use_cookie_only和use_trans_sid，  
	    // 两者分别表示sessionid在客户端只能通过cookie保存和只能通过url传递  
	    if (!PS(use_cookies) && PS(send_cookie)) {  
	        if (PS(use_trans_sid) && !PS(use_only_cookies)) {  
	            PS(apply_trans_sid) = 1;  
	        }  
	        PS(send_cookie) = 0;  
	    }  
		}  
		/* }}} */    

4. session的gc

 	在PHP中, 如果使用`files`作为Session的`save_handler`, 那么就有概率在每次`session_start`的时候运行Session的Gc过程。

### 二、session如何设计的？

 通过上面可以大致了解在PHP中session是如何运行的。那么session是如何设计的。正常来说，session实现需要以下几个属性来实现

+ `session_id`:一个唯一标识符，随机产生的。
+ `session data`：保存session数据的
+ `created`:创建时间
+ `max_age`: 记录的有效时间

#### PHP中的session声明周期

 PHP中的session的声明周期如下：

+ 开启
+ 设置/读取数据
+ 关闭
+ 销毁
+ 垃圾回收

##### 开启

调用session_start()函数，开启session，服务器会分配一个session_id并将session_id保存到cookie中。

1. session_start()函数前面不能有任何输出，因为session_start会往cookie中添加数据
2. PHP在ini中配置 session.auto_start 会自动开启session。

##### 设置/读取数据

 通过$_SESSION来进行设置/读取数据

##### 关闭

 PHP 脚本执行完毕之后，会话会自动关闭。 同时，也可以通过调用函数 session_write_close() 来手动关闭会话。

##### 销毁

+ 通常情况下，在你的代码中不必调用 `session_destroy()` 函数， 可以直接清除 $_SESSION 数组中的数据来实现会话数据清理。 

	> $_SESSION = array();

+ 调用session_destroy()函数可以清除跟这个用户相关的所有的数据，但是调用之前必须将cookie中保存的数据清除.

		if (ini_get("session.use_cookies")) {
	    $params = session_get_cookie_params();
	    setcookie(session_name(), '', time() - 42000,
	        $params["path"], $params["domain"],
	        $params["secure"], $params["httponly"]
		    );
		}
		
		// 最后，销毁会话
		session_destroy();

### 三、详解PHP中的files会话保存管理器

### 常见问题

#### 为什么要自定义session

 因为使用files方式有很大的局限性，如分布式就不能用了，而且对一些高性能网站来说，读写文件的性能消耗太大。
 



 

