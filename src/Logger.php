<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 07/08/13 15:30
 */
namespace src;

require_once(__DIR__."/config/environment.php");
require_once(__DIR__."/FileManager.php");
require_once(__DIR__."/DbManager.php");
require_once(__DIR__."/EmailManager.php");
require_once(__DIR__."/EmailInterface.php");

use src\FileManager;
use src\DbManager;
use src\EmailManager;
use src\EmailInterface;

class Logger
{
    const ERROR = 0;
    const WARNING = 1;
    const INFO = 2;

    static protected $types = array('ERROR', 'WARNING', 'INFO');

    static protected function getFile(){
        $file = new FileManager();
        $file->setEnvironment(ENVIRONMENT);

        return $file;
    }

    static protected function getDb(){
        return DbManager::getInstance(ENVIRONMENT);
    }

    static protected function getEmail(){
        return EmailManager::getInstance(ENVIRONMENT);
    }

    static public function log($label = null, $var, $flag = 2, EmailInterface $sender = null){
        try {

            $file = self::getFile();
            $file->checkedFile();

            file_put_contents($file->getFileName(), array(self::$types[$flag], " [", date("Y-m-d H:i:s"), "] ", $label, ": ", $var, PHP_EOL), FILE_APPEND);

            $loggerDb = self::getDb();
            if($loggerDb::isExecutable()){
                $loggerDb::save($label, $var, self::$types[$flag]);
            }

            $loggerEmail = self::getEmail();
            if($loggerEmail::isExecutable()){
                if(!is_null($sender)){
                    $loggerEmail::send($label, $var, self::$types[$flag], $sender);
                } else {
                    throw new \Exception("Debe enviar una instancia valida del parametro sender.");
                }
            }

        } catch(\Exception $e) {
            die($e->getMessage());
        }
    }
}
