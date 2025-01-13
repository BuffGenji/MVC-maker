<?php
declare(strict_types=1);

namespace App\Classes;

use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;


/**
 * A model will normally have a connection to the dtaabase as a property and in the constructorvit establishes
 * or gets this connection (if it already exists)
 * 
 */

class ModelClass extends MVCElement {

    private $database;


    public function __construct()
    {
        $this->introduction("Model");
        $this->database = null;
    }


    public function create(): Class_
    {
        $node = (new BuilderFactory())->class($this->file_name)
            ->addStmts(
                []
            )
            ->addStmts(
                []
            )
            ->getNode();
        return $node;
    }
    
}
