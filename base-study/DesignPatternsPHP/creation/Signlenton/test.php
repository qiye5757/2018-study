<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/11/7 0007
 * Time: 下午 8:31
 */
//(懒汉式加载)
class Signlenton{
    /**
     * @var Singleton
     */
    private static $instance;
    /**
     * 不允许从外部调用以防止创建多个实例
     * 要使用单例，必须通过 Singleton::getInstance() 方法获取实例
     */
    private function __construct()
    {
    }

    /**
     * 防止实例被克隆（这会创建实例的副本）
     */
    private function __clone()
    {
    }

    /**
     * 防止反序列化（这将创建它的副本）
     */
    private function __wakeup()
    {
    }

    public function test(){
        echo "test";
    }
    /**
     * 通过懒加载获得实例（在第一次使用的时候创建）
     */
    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static();
        }

        return static::$instance;
    }
}

$signlenton = Signlenton::getInstance();
$signlenton->test();
