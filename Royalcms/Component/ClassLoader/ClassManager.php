<?php

namespace Royalcms\Component\ClassLoader;

use Royalcms\Component\Support\Str;

class ClassManager
{
    
    protected static $loader;
    
    /**
     * 手动类和文件加载
     *
     * @param string $class
     */
    public static function import($class)
    {
        $loader = self::auto_loader_class();
        if (! $loader->loadClass($class)) {
            rc_throw_exception($class . __('加载失败'));
        }
    }

    /**
     * 实现类的自动加载
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public static function auto_loader_class()
    {
//        if (! isset(self::$loader)) {
//            $dirname = dirname(ROYALCMS_PATH) . DS . 'class-loader' . DS;
//            require_once $dirname . 'Royalcms/Component/ClassLoader/ClassLoader.php';
//            $dir = rtrim(ROYALCMS_PATH, DIRECTORY_SEPARATOR);
//            self::$loader = new ClassLoader();
//            self::$loader->registerPrefix('Component', $dir . '/Royalcms');
//            self::$loader->register();
//        }

        if (is_null(self::$loader)) {
            self::$loader = new \Composer\Autoload\ClassLoader();
        }

        return self::$loader;
    }
    
    /**
     * Add namespace to the class loader.
     *
     * @param  string|array  $directories
     * @return void
     */
    public static function addNamespace($namespace, $directorie)
    {
        if (! Str::endsWith('\\', $namespace)) {
            $namespace = $namespace . '\\';
        }

        self::$loader->addPsr4($namespace, $directorie);
    }
    
    /**
     * Add namespaces to the class loader.
     *
     * @param  string|array  $directories
     * @return void
     */
    public static function addNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $directorie) {
            self::addNamespace($namespace, $directorie);
        }
    }
    
    /**
     * Gets all the namespaces registered with the loader.
     *
     * @return array
     */
    public static function getNamespaces()
    {
        return self::$loader->getPrefixesPsr4();
    }
}

// end