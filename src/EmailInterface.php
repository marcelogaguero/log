<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 16/08/13 15:39
 */
namespace src;

/**
 * Envio de email con el log
 */
interface EmailInterface
{
    /**
     * @abstract
     * @param $from email de salida
     * @param $to email de llegada
     * @param $subject asunto del correo
     * @param $message cuerpo del correo
     * @return boolean true|false si se envia correctamente el resultado es true, en otro caso false
     */
    public function send($from, $to, $subject, $message);
}
