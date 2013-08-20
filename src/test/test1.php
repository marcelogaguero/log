<?php
/**
 * Created by Wikdos.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 07/08/13 16:40
 */
namespace test {
    require_once (__DIR__."/Sender.php");
    require_once (__DIR__."/../Logger.php");

    $obj = new \stdClass();
    $obj->nombre = 'Marcelo';
    $obj->apellido = 'Aguero';

    $sender = new Sender();

    \src\Logger::log("Cuidado", serialize($obj), \src\Logger::WARNING, $sender); echo "Log 1".PHP_EOL;
    \src\Logger::log("Error", "SE ROMPIO", \src\Logger::ERROR, $sender); echo "Log 2".PHP_EOL;

    die("listo");
}

