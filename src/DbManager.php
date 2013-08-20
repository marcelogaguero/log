<?php
/**
 * Created by Wikdos.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 12/08/13 09:46
 */
namespace src;

require_once(__DIR__."/Config.php");

use src\Config;

class DbManager
{
    static protected $conexion;
    static protected $environment;
    static protected $isExecutable;
    static protected $instance = null;
    static protected $exist;

    final private function __contruct() {}

    protected static function existTable(){
        $db = Config::getInstance(self::$environment)->getConfig('db_name');
        $query = mysqli_query(self::$conexion, "SELECT TABLE_NAME FROM `information_schema`.`TABLES` WHERE TABLE_NAME = 'log_rotator' AND TABLE_SCHEMA='".$db."'");

        $tablesExists = array();
        while( null!==($row=mysqli_fetch_row($query)) ){
            $tablesExists[] = $row[0];
        }

        return count($tablesExists) > 0;
    }

    protected static function createTableLog(){
        $str = "CREATE TABLE `log_rotator` (
               `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
               `crate_dt` DATETIME NOT NULL,
               `type` ENUM('info','error','warning') NOT NULL,
               `label` VARCHAR(30) NOT NULL,
               `message` MEDIUMTEXT NOT NULL,
               PRIMARY KEY (`id`)
             ) ENGINE=MYISAM DEFAULT CHARSET=latin1";
        $query = mysqli_query(self::$conexion, $str);

        if(!$query) throw new \Exception("No se pudo crear la tabla en la base de datos.");
        return true;
    }

    public static function getInstance($env) {
        if (self::$instance == null) {
            
            self::$instance = new DbManager();
            self::$environment = $env;
            self::$isExecutable = (boolean) Config::getInstance(self::$environment)->getConfig('db_save');

            $server = Config::getInstance(self::$environment)->getConfig('db_server');
            $user = Config::getInstance(self::$environment)->getConfig('db_user');
            $pass = Config::getInstance(self::$environment)->getConfig('db_pass');
            $db = Config::getInstance(self::$environment)->getConfig('db_name');

            self::$conexion = mysqli_connect($server, $user, $pass, $db);
            if(!self::$conexion) {
                throw new \Exception("No se pudo conectar a la base de datos");
            }

            if(self::$isExecutable){
                if(self::$exist == null or self::$exist === false){
                   if(!self::existTable()) {
                       self::$exist = self::createTableLog();
                   }
                }
            }
        }
        return self::$instance;
    }

    static public function isExecutable(){
        return self::$isExecutable;
    }

    static public function save($label, $var, $flag){
        $query = "INSERT INTO `log_rotator` (`id`,`crate_dt`,`type`, `label`,`message`)
                  VALUES (null, '".date('Y-m-d H:i:s')."', '".$flag."','".$label."', '".$var."') ";
        $result = mysqli_query(self::$conexion, $query);

        if(!$result) throw new \Exception("No se pudo guardar el log en la base de datos");
        return true;
    }
}
