<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 07/08/13 16:40
 */
namespace test {
    require_once (__DIR__."/../Logger.php");

    $obj = new \stdClass();
    $obj->nombre = 'Marcelo';
    $obj->apellido = 'Aguero';

    \src\Logger::log("Cuidado", serialize($obj), \src\Logger::WARNING); echo "Log 1".PHP_EOL;
    \src\Logger::log("Error", "SE ROMPIO", \src\Logger::ERROR); echo "Log 2".PHP_EOL;


    die("listo");
}

