<?php
/**
 * Created by Wikdos.
 *
 * @package mga/logrotator
 * @author: Marcelo Agüero <marcelogaguero@hotmail.com>
 * @since: 07/08/13 15:31
 */
namespace src;

class Config
{
    static protected $environment = null;
    static protected $config = null;
    static protected $instance = null;

    final private function __contruct() {}

    public static function getInstance($env) {
        if (self::$instance == null) {
            self::$instance = new Config();
            self::$environment = $env;
        }
        return self::$instance;
    }

    public function getConfig($param){
        if(is_null(self::$config)) {
            $env = self::$environment;
            $path = __DIR__.DIRECTORY_SEPARATOR."config/parameters_{$env}.ini";
            if (!file_exists($path)) throw new \Exception("No existe el archivo de configuración ". $path);
            self::$config = parse_ini_file($path, true);
        }
        if(isset(self::$config[$param])) return self::$config[$param];
        throw new \Exception("No existe el parametro de configuración ". $path);
    }

}
