<?php 
namespace Epoch;

class Installer
{
    function install()
    {
        echo "Installing Modules... " . PHP_EOL;
        $directory = new DirectoryIterator(dirname(__FILE__));
        
        //Compile all the routes.
        foreach ($directory as $file) {
            if ($file->getType() == 'dir' && !$file->isDot()) {
                $class = "\Epoch\\" . $file->getFileName() . "\Installer";
                if (class_exists($class)) {
                    echo "Installing module:" . $file->getFileName() . "... ";
                    $moduleInstaller = new $class;
                    $moduleInstaller->install();
                    echo "... Finished" . PHP_EOL;
                }
            }
        }
        
        echo "Finished installing modules." . PHP_EOL;
    }
}