<?php
/**
 * Service Locator приложения
 * сохраняет компоненты приложения в статические свойствах
 * регистрирует автозагрузчик классов и обработчик исключений
 * Class App
 */
class App
{

    public static $router;
    public static $db;
    public static $core;

    /**
     * Базовая инициализация
     */
    public static function init()
    {
        spl_autoload_register(['static','loadClass']);
        static::bootstrap();
        set_exception_handler(['App','handleException']);
    }

    /**
     * Минимальный набор классов
     */
    public static function bootstrap()
    {
        static::$router = new App\Router();
        static::$core = new App\Core();
        static::$db = new App\Db();
    }

    /**
     * Динамическая загрузка классов
     * @param $className
     */
    public static function loadClass ($className)
    {
        $className = str_replace('\\', DIRECTORY_SEPARATOR, $className);
        require_once ROOTPATH.DIRECTORY_SEPARATOR.$className.'.php';
    }

    /**
     * Исключения
     * @param Throwable $e
     */
    public function handleException (Throwable $e)
    {
        echo static::$core->runAction('Error', 'exeption', [$e]);
    }

}