<?php
/**
 * @author: Hahn
 * @date: 2021/6/21
 * @time: 20:10 下午
 * @description：单例
 */


namespace Kyy\Traits;


trait Single {

    //申明一个私有的成员变量，保存對象
    private static $_instance = [];


    /**
     * 静态方法，Return实例化对象，在本类内操作
     * @return mixed|static
     */
    public static function getInstance() {
        $classFullName = get_called_class();
        //检测属性$instance是否已经保存了当前类的实例
        if (!isset(self::$_instance[$classFullName])) {
            self::$_instance[$classFullName] = new static();
        }
        //返回对象实例
        return self::$_instance[$classFullName];
    }
}
