<?php

declare(strict_types=1);

namespace App\Classes;

use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;

/**
 * There are a few assumptions when building a controller, 
 * first one is that a controller will have a model by default. Which means that when we choose to make
 * a new controller element, we will need to make a model for it. 
 */


class ControllerClass extends MVCElement
{
    private $model;
    private $methods;

    public function __construct()
    {
        $this->introduction("Controller");
        $this->methods = $this->askForMethods();
    }

    public function create(): Class_
    {
        $node = (new BuilderFactory())->class($this->file_name)
            ->addStmts(
                $this->methods->statements
            )
            ->addStmts(
                []
            )
            ->getNode();
        return $node;
    }
}
