<?php
/**
 * Created by Nemogroup.
 * @author: Marcelo Agüero <marcelo.aguero@nemogroup.net>
 * @since: 07/08/13 15:29
 */
namespace src;

require_once(__DIR__."/Config.php");

use src\Config;

class FileManager
{
    private $environment;

    public function getFileName(){
        $path = ROOT.Config::getInstance($this->environment)->getConfig('folder');
        return realpath($path.DIRECTORY_SEPARATOR."log_".$this->environment.".log");
    }

    protected function getFileNameBackup($modification){
        $path = ROOT.Config::getInstance($this->environment)->getConfig('folder');
        return realpath($path).DIRECTORY_SEPARATOR.$modification."_log_".$this->environment.".bck";
    }

    public function thereIsFile(){
        return is_file($this->getFileName());
    }

    public function setEnvironment($env){
        $this->environment = $env;
    }

    public function thereIsDir(){
        return is_dir(ROOT.Config::getInstance($this->environment)->getConfig('folder'));
    }

    public function deleteLogs() {
        $path = Config::getInstance($this->environment)->getConfig('folder');
        $files = array();
        if ($handle = opendir($path)) {
            while (false !== ($entry = readdir($handle))) {
                if ($entry != "." && $entry != "..") {
                    $files[] = $path.DIRECTORY_SEPARATOR.$entry;
                }
            }
            closedir($handle);
        }

        $file_number = Config::getInstance($this->environment)->getConfig('file_number');
        foreach($files as $file){
            if(count($files) > $file_number){
                $files = array_pop($files);
            }
        }

        return true;
    }

    public function checkedFile(){
        $date = date("Y_m_d");
        $strDate = date("Y-m-d");

        if(!$this->thereIsDir()) throw new \Exception("No existe el directorio verifique el parametro folder,");

        $file = $this->getFileName();
        if(!$this->thereIsFile()){
            $write = file_put_contents($file, "-- FILE ROTATOR ({$strDate})");
            if(!$write) throw new \Exception("No se pudo guardar en el archivo de log, verifique permisos.");

            $modification = date ("Y_m_d");
            chmod($file, 0755);
        } else {
            $modification = date ("Y_m_d", filemtime($file));
        }

        if($date != $modification){
            // die(var_dump($this->getFileNameBackup($modification)));
            $rename = rename($file, $this->getFileNameBackup($modification));
            if(!$rename) throw new \Exception("No se pudo renombrar el archivo de log, verifique permisos.");

            $write = file_put_contents($file, "-- FILE ROTATOR ({$strDate})");
            if(!$write) throw new \Exception("No se pudo guardar en el archivo de log, verifique permisos.");

            $this->deleteLogs();
        }

    }
}
