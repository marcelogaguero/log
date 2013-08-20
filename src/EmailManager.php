<?php
/**
 * Created by Wikdos.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 13/08/13 07:53
 */
namespace src;

require_once(__DIR__."/Config.php");
require(__DIR__."/Smpt.php");

use src\Config;
use src\Smpt;
use src\EmailInterface;

class EmailManager
{
    static protected $environment;
    static protected $isExecutable;
    static protected $instance = null;

    final private function __contruct() {}

    public static function getInstance($env) {
        if (self::$instance == null) {

            self::$instance = new EmailManager();
            self::$environment = $env;
            self::$isExecutable = (boolean) Config::getInstance(self::$environment)->getConfig('email_send');

            if(self::$isExecutable){

            }
        }
        return self::$instance;
    }

    static public function isExecutable(){
        return self::$isExecutable;
    }

    static private function createMessage($var, $flag){
        ob_start();

        include __DIR__.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."email_template.php";
        $html = ob_get_clean();
        return $html;
    }

    static public function send($label, $var, $flag, $sender){

        $from = Config::getInstance(self::$environment)->getConfig('email_from');
        $to = Config::getInstance(self::$environment)->getConfig('email_to');
        $subject = Config::getInstance(self::$environment)->getConfig('subject')." - ".$label;
        $message = self::createMessage($var, $flag);

        $snd = $sender->send($from, $to, $subject, $message);

        if(!$snd) throw new \Exception("No se pudo enviar el correo verifique la configuración.");

        return true;
    }
}
