<?php
declare(strict_types=1);

namespace App\Services\Writer;

use App\Classes\EntityClass;
use App\Classes\MVCElement;
use PhpParser\PrettyPrinter\Standard;

/**
 * This service is dedicated to writting files to the correct directories in the new project
 * it will be the middleman between the MVCmaker file
 */
class FileWriter {

    public function __construct($class_to_write)
    {
        $directory = match($class_to_write::class) {
            EntityClass::class => "Entity",
        };
        $prettyPrinter = new Standard();
        file_put_contents(SOURCE_DIR_PATH. "\\" . $directory ,
                          $prettyPrinter->prettyPrintFile($class_to_write->create()));

    }
}
