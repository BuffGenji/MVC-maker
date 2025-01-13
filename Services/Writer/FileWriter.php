<?php

declare(strict_types=1);

namespace App\Services\Writer;

use App\Classes\ControllerClass;
use App\Classes\EntityClass;
use App\Classes\ModelClass;
use App\Initialise;
use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\PrettyPrinter\Standard;

/**
 * This service is dedicated to writting files to the correct directories in the new project, 
 * since namespacing is related to directory structure. It is also handled in this class
 */
class FileWriter
{
    private string $namespace;

    public function __construct($class_to_write)
    {

        // if (empty(file_get_contents('../track.txt',true))) {
        //     new Initialise;
        // } else {
        //     basename(Initialise::getSourceDirPath()) . "\\" . $directory
        // }
        new Initialise;

        $directory = match ($class_to_write) {
            EntityClass::class => "Entities",
            ControllerClass::class => "Controllers",
            ModelClass::class => "Models"
        };

        $this->writeTo($directory, $class_to_write);
    }

    /**
     * All classes treated here are subclesses of the MVCElement class 
     * meaning that they all have a create function that returns a Class node 
     */
    private function writeTo(string $directory, $class_to_write)
    {
        // instantiates a specific MVCElement
        $element_to_create = new $class_to_write;

        // sets location of file
        $namespaceStmt = $this->getNamespaceStmtFor($directory);
        $pathToClassFile = $this->getPathToClassFile($directory, $element_to_create);

        file_put_contents(
            filename: $pathToClassFile,
            data: $this->writeFileFromNodes(
                namespaceNode: $namespaceStmt,
                classNode: $element_to_create->create()
            )
        );
    }

    /** Helper functions  */

    private function writeFileFromNodes(Namespace_ $namespaceNode, Class_ $classNode)
    {
        $prettyPrinter = new Standard();
        $fileContents = $prettyPrinter->prettyPrintFile(
            array(
                $namespaceNode,
                $classNode // all other class nodes and all components inside a class
            )
        );
        return $fileContents;
    }

    private function getNamespaceStmtFor(string $directory): Namespace_
    {
        /**This is true ONLY for TOP LEVEL DIRECOTRIES */
        $namespace = basename(Initialise::getSourceDirPath()) . "\\" . $directory;

        // to refactor, don't know where to place function
        $factory = new BuilderFactory();
        $namespaceStmt = $factory->namespace($namespace)
            ->getNode();

        return  $namespaceStmt;
    }

    private function getPathToClassFile(string $directory, object $element_to_create)
    {
        $path_to_class =
            rtrim(Initialise::getSourceDirPath() .
                "\\" . $directory .
                "\\" . $element_to_create->getFileName(), "\r\n");

        return $path_to_class . ".php";
    }
}
