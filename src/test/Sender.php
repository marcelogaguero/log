<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 16/08/13 16:24
 */
namespace test;

require_once(__DIR__."/../EmailInterface.php");
use src\EmailInterface;

class Sender implements EmailInterface
{

    /**
     * @param $from email de salida
     * @param $to email de llegada
     * @param $subject asunto del correo
     * @param $message cuerpo del correo
     * @return boolean true|false si se envia correctamente el resultado es true, en otro caso false
     */
     public function send($from, $to, $subject, $message)
     {
         $message = "esto es una prueba";
         $cabeceras = 'From: '.$from. "\r\n" .
             'X-Mailer: PHP/' . phpversion();

         return mail($to, $subject, $message, $cabeceras);
     }

}
