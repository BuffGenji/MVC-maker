<?php

declare(strict_types=1);

namespace App\Services\Writer;

use App\Classes\EntityClass;
use App\Initialise;
use PhpParser\PrettyPrinter\Standard;

/**
 * This service is dedicated to writting files to the correct directories in the new project
 */
class FileWriter
{

    public function __construct($class_to_write)
    {
        if (!Initialise::isInitialised()) {
            new Initialise();
        }
        $directory = match ($class_to_write) {
            EntityClass::class => "Entities",
            // other types of classes . . .
        };
        $this->writeTo($directory, $class_to_write);
    }

    private function writeTo(string $directory, $class_to_write)
    {
        $element_to_create = new $class_to_write;
        
        $path_to_class = 
        rtrim(Initialise::getSourceDirPath() . 
        "\\" . $directory . 
        "\\" . $element_to_create->getFileName(),"\r\n");

        $prettyPrinter = new Standard();
        file_put_contents(
            $path_to_class . '.php',
            $prettyPrinter->prettyPrintFile(array($element_to_create->create()))
        );
    }
}
