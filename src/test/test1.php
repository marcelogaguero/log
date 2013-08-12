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

    \src\Logger::log("stdClass", serialize($obj));
    die("listo");
}

