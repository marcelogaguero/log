<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 13/08/13 07:53
 */
namespace src;

require_once(__DIR__."/Config.php");

use src\Config;

class LoggerEmail
{
    static protected $environment;
    static protected $isExecutable;
    static protected $instance = null;

    final private function __contruct() {}

    public static function getInstance($env) {
        if (self::$instance == null) {

            self::$instance = new LoggerEmail();
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
        include __DIR__.DIRECTORY_SEPARATOR."templates".DIRECTORY_SEPARATOR."email_template";
        $html = ob_get_clean();
        return $html;
    }

    static public function send($label, $var, $flag){

        $from = Config::getInstance(self::$environment)->getConfig('email_from');
        $to = Config::getInstance(self::$environment)->getConfig('email_to');
        $subject = Config::getInstance(self::$environment)->getConfig('subject')." - ".$label;
        $message = self::createMessage($var, $flag);

        $headers = "From: " . strip_tags($from) . "\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        mail($to, $subject, $message, $headers);
    }
}
