## ThinkPHP源码阅读

### 启动流程 

1. 进入public/index.php
2. 定义常量，为运行路径、开发路径、类库路径等等
3. 注册自动加载，注册系统自带的和composer自带的
4. 注册系统自定义的错误与异常类
5. 加载默认配置文件
6. 应用启动

### 注册自动加载

	public static function register($autoload = null)
    {
        // 注册系统自动加载
        spl_autoload_register($autoload ?: 'think\\Loader::autoload', true, true);

        // Composer 自动加载支持
        if (is_dir(VENDOR_PATH . 'composer')) {
            if (PHP_VERSION_ID >= 50600 && is_file(VENDOR_PATH . 'composer' . DS . 'autoload_static.php')) {
                require VENDOR_PATH . 'composer' . DS . 'autoload_static.php';

                $declaredClass = get_declared_classes();
                $composerClass = array_pop($declaredClass);

                foreach (['prefixLengthsPsr4', 'prefixDirsPsr4', 'fallbackDirsPsr4', 'prefixesPsr0', 'fallbackDirsPsr0', 'classMap', 'files'] as $attr) {
                    if (property_exists($composerClass, $attr)) {
                        self::${$attr} = $composerClass::${$attr};
                    }
                }
            } else {
                self::registerComposerLoader();
            }
        }

        // 注册命名空间定义
        self::addNamespace([
            'think'    => LIB_PATH . 'think' . DS,
            'behavior' => LIB_PATH . 'behavior' . DS,
            'traits'   => LIB_PATH . 'traits' . DS,
        ]);

        // 加载类库映射文件
        if (is_file(RUNTIME_PATH . 'classmap' . EXT)) {
            self::addClassMap(__include_file(RUNTIME_PATH . 'classmap' . EXT));
        }

        self::loadComposerAutoloadFiles();

        // 自动加载 extend 目录
        self::$fallbackDirsPsr4[] = rtrim(EXTEND_PATH, DS);
    } 