## ThinkPHP5.0.20源码阅读

### 启动流程 

1. 进入public/index.php
2. 定义常量，为运行路径、开发路径、类库路径等等
3. 注册自动加载，注册系统自带的和composer自带的
4. 注册系统自定义的错误与异常类
5. 加载默认配置文件
6. 应用启动
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

### 注册自动加载

 通过以下两行代码实现的

	//将Loader.php文件引入
	require CORE_PATH . 'Loader.php';

	//通过register方法来注册自动加载
	\think\Loader::register();

#### 第一步：注册的系统自动加载的类

	// 注册系统自动加载
    spl_autoload_register($autoload ?: 'think\\Loader::autoload', true, true);	

#### 第二步：将Composer 自动加载的内容获取到当前的自动加载类中
	
 可以参考 [composer学习](https://www.cnblogs.com/qiye5757/p/9487688.html)，（强行兼容composer，直接使用composer的自动加载不好吗，这个是设计问题）

#### 第三步：注册命名空间的别名

 	self::addNamespace([
        'think'    => LIB_PATH . 'think' . DS,
        'behavior' => LIB_PATH . 'behavior' . DS,
        'traits'   => LIB_PATH . 'traits' . DS,
    ]);

#### 第四步：查看缓存中是否存在 `classmap`文件，有则直接加载到该loader类中

	// 加载类库映射文件
    if (is_file(RUNTIME_PATH . 'classmap' . EXT)) {
        self::addClassMap(__include_file(RUNTIME_PATH . 'classmap' . EXT));
    }

#### 第五步：将从composer中加载的files放入$GLOBALS超全局变量中（PHP自带的一些插件库就是通过这种方式做的）
	
	self::loadComposerAutoloadFiles();

#### 第六步：自动加载 extend 目录
        
	self::$fallbackDirsPsr4[] = rtrim(EXTEND_PATH, DS);

#### 系统自带的自动加载如何工作的

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

### 注册错误和异常的处理机制

 前面已经设置了自动加载了，所以就直接使用就可以了
