## ThinkPHP5.0.20源码阅读

### 写在前面

#### 项目根目录
 
 约定为 `/`

### 启动流程 

#### 第一步：访问入口文件（/public/index.php）

 入口文件代码如下

	// [ 应用入口文件 ]
	
	// 定义应用目录
	define('APP_PATH', __DIR__ . '/../application/');
	// 加载框架引导文件
	require __DIR__ . '/../thinkphp/start.php';

 框架引导文件如下

	// ThinkPHP 引导文件
	// 1. 加载基础文件
	require __DIR__ . '/base.php';
	
	// 2. 执行应用
	App::run()->send();

#### 第二步： 定义常量(/thinkphp/base.php)

 常量如下，在代码中都可以直接使用的

	define('THINK_VERSION', '5.0.22');
	define('THINK_START_TIME', microtime(true));
	define('THINK_START_MEM', memory_get_usage());
	define('EXT', '.php');
	define('DS', DIRECTORY_SEPARATOR);
	defined('THINK_PATH') or define('THINK_PATH', __DIR__ . DS);
	define('LIB_PATH', THINK_PATH . 'library' . DS);
	define('CORE_PATH', LIB_PATH . 'think' . DS);
	define('TRAIT_PATH', LIB_PATH . 'traits' . DS);
	defined('APP_PATH') or define('APP_PATH', dirname($_SERVER['SCRIPT_FILENAME']) . DS);
	defined('ROOT_PATH') or define('ROOT_PATH', dirname(realpath(APP_PATH)) . DS);
	defined('EXTEND_PATH') or define('EXTEND_PATH', ROOT_PATH . 'extend' . DS);
	defined('VENDOR_PATH') or define('VENDOR_PATH', ROOT_PATH . 'vendor' . DS);
	defined('RUNTIME_PATH') or define('RUNTIME_PATH', ROOT_PATH . 'runtime' . DS);
	defined('LOG_PATH') or define('LOG_PATH', RUNTIME_PATH . 'log' . DS);
	defined('CACHE_PATH') or define('CACHE_PATH', RUNTIME_PATH . 'cache' . DS);
	defined('TEMP_PATH') or define('TEMP_PATH', RUNTIME_PATH . 'temp' . DS);
	defined('CONF_PATH') or define('CONF_PATH', APP_PATH); // 配置文件目录
	defined('CONF_EXT') or define('CONF_EXT', EXT); // 配置文件后缀
	defined('ENV_PREFIX') or define('ENV_PREFIX', 'PHP_'); // 环境变量的配置前缀

	// 环境常量
	define('IS_CLI', PHP_SAPI == 'cli' ? true : false);
	define('IS_WIN', strpos(PHP_OS, 'WIN') !== false);

 加载`.env`文件（laravel中使用的较多），[可以参考手册](https://www.kancloud.cn/manual/thinkphp5/126074)
	
	// 加载环境变量配置文件
	if (is_file(ROOT_PATH . '.env')) {
	    $env = parse_ini_file(ROOT_PATH . '.env', true);
	
	    foreach ($env as $key => $val) {
	        $name = ENV_PREFIX . strtoupper($key);
	
	        if (is_array($val)) {
	            foreach ($val as $k => $v) {
	                $item = $name . '_' . strtoupper($k);
	                putenv("$item=$v");
	            }
	        } else {
	            putenv("$name=$val");
	        }
	    }
	}

#### 第三步：注册自动加载

 
 通过以下两行代码实现的(/thinkphp/base.php)

	//将Loader.php文件引入
	require CORE_PATH . 'Loader.php';

	//通过register方法来注册自动加载
	\think\Loader::register();

##### 1.注册的系统自动加载的类

	// 注册系统自动加载
    spl_autoload_register($autoload ?: 'think\\Loader::autoload', true, true);	

##### 2.将Composer 自动加载的内容获取到当前的自动加载类中
	
 可以参考 [composer学习](https://www.cnblogs.com/qiye5757/p/9487688.html)，（强行兼容composer，直接使用composer的自动加载不好吗，这个是设计问题）

##### 3.注册命名空间的别名

 	self::addNamespace([
        'think'    => LIB_PATH . 'think' . DS,
        'behavior' => LIB_PATH . 'behavior' . DS,
        'traits'   => LIB_PATH . 'traits' . DS,
    ]);

##### 4.查看缓存中是否存在 `classmap`文件，有则直接加载到该loader类中

	// 加载类库映射文件
    if (is_file(RUNTIME_PATH . 'classmap' . EXT)) {
        self::addClassMap(__include_file(RUNTIME_PATH . 'classmap' . EXT));
    }

##### 5.将从composer中加载的files放入$GLOBALS超全局变量中（PHP自带的一些插件库就是通过这种方式做的）
	
	self::loadComposerAutoloadFiles();

##### 6.自动加载 extend 目录
        
	self::$fallbackDirsPsr4[] = rtrim(EXTEND_PATH, DS);

##### 系统自带的自动加载如何工作的

1. 检测是否定义了类的命名空间别名，有则直接加载

		if (!empty(self::$namespaceAlias)) {
            //获取该模块的命名空间
            $namespace = dirname($class);
            //如果该数组存在命名空间对应的别名，加载别名
            if (isset(self::$namespaceAlias[$namespace])) {
                $original = self::$namespaceAlias[$namespace] . '\\' . basename($class);
                if (class_exists($original)) {
                    return class_alias($original, $class, false);
                }
            }
	    }

2. 查找文件，能找到则将类包含进来

	+ 首先检测类库映射中是否存在（即classmap）
	+ 检测psr-4是否能加载到该文件
	+ 检测psr-0是否能加载到该文件
	+ 都无则定义该类的类库映射为false


#### 第四步：注册系统自定义的错误与异常类

  代码如下(/thinkphp/base.php)，已经注册了自动加载了，所以可以直接执行，不用require。

	// 注册错误和异常处理机制
	\think\Error::register();

 注册异常处理方法如下(/thinkphp/library/think/Loader.php)

 	error_reporting(E_ALL); 
    set_error_handler([__CLASS__, 'appError']); 
    set_exception_handler([__CLASS__, 'appException']);
    register_shutdown_function([__CLASS__, 'appShutdown']);

#### 第五步，加载默认配置文件(/thinkphp/base.php)

	// 加载惯例配置文件
	\think\Config::set(include THINK_PATH . 'convention' . EXT);

#### 第六步，启动应用

	+ 加载配置文件
	+ 加载过滤信息
	+ 配置调试模式
	+ 设置时区
	+ 监听 `app_init` 钩子
	+ 开启多语言
	+ 监听 `app_dispatch` 钩子
	+ 加载调度信息
	+ 无则解析URL组建调度信息
	+ 记录调度信息
	+ 记录访问日志
	+ 监听 `app_begin` 钩子
	+ 将请求缓存
	+ **根据调度信息和配置信息执行应用**
	+ 调度为 `model` 模式下，是默认流程
	+ 检测是否支持多模块，如果支持则初始化模块，即第一步
	+ 加载过滤信息
	+ 设置当前请求的控制器、操作
	+ 监听 `module_init` 钩子
	+ 实例化控制器
	+ 监听 `action_begin` 钩子
	+ 调用反射执行类的方法
	+ 清除类的实例化
	+ 根据配置信息创建response对象
	+ 监听 `app_end` 钩子
	+ 监听 `response_send` 钩子
	+ 构建response发送到客户端
	+ 监听 `response_end` 钩子

