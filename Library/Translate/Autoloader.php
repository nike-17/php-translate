<?php

class Translate_Autoloader
{
    /**
     * Is registered Translate autoload
     *
     * @var boolean
     */
    protected static $_isRegistered;

    /**
     * Translate path
     *
     * @var string
     */
    protected static $_TranslatePath;

    /**
     * Autoload callback
     *
     * @var array
     */
    protected static $_callback = array('Translate_Autoloader', 'load');

    /**
     * Register Translate autoload
     *
     * @param boolean $prepend Prepend the autoloader in the chain, the default is
     *                         'false'. This parameter is available since PHP 5.3.0
     *                         and will be silently disregarded otherwise.
     * @return boolean
     */
    public static function register($prepend = false)
    {
        if (self::isRegistered()) {
            return false;
        }
        if (!is_bool($prepend)) {
            $prepend = (bool) $prepend;
        }

        if (version_compare(PHP_VERSION, '5.3.0', '<')) {
            self::$_isRegistered = spl_autoload_register(self::$_callback);
        } else {
            self::$_isRegistered = spl_autoload_register(
                self::$_callback,
                true,
                $prepend
            );
        }
        return self::$_isRegistered;
    }

    /**
     * Unregister Translate autoload
     *
     * @return boolean
     */
    public static function unregister()
    {
        if (!self::isRegistered()) {
            return false;
        }

        self::$_isRegistered = !spl_autoload_unregister(self::$_callback);

        return self::$_isRegistered;
    }

    /**
     * Is Translate autoload registered
     *
     * @return boolean
     */
    public static function isRegistered()
    {
        return self::$_isRegistered;
    }

    /**
     * Load class
     *
     * @param string $className
     *
     * @return boolean
     */
    public static function load($className)
    {
        if (0 !== strpos($className, 'Translate')) {
            return false;
        }

        $path = self::getTranslatePath() . '/' . str_replace('_', '/', $className) . '.php';

        return include $path;
    }

    /**
     * Get Translate path
     *
     * @return string
     */
    public static function getTranslatePath()
    {
        if (!self::$_TranslatePath) {
            self::$_TranslatePath = realpath(dirname(__FILE__) . '/..');
        }

        return self::$_TranslatePath;
    }
}
