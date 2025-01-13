<?php
declare(strict_types=1);

namespace App\Services\Factories;

use App\Components\Parameter;
use Exception;
use PhpParser\BuilderFactory;

class ParameterStatementFactory extends AbstractStatementFactory  {


    public function __construct(?array $parameters = null)
    {
        $this->builder = new BuilderFactory;
        $this->products = $this->createStatementsFor($parameters);
    }

    /**
     * Creates Parameter statement node
     */
    public function createProduct($component_object)
    {
        if (!($component_object instanceof Parameter)) {
            throw new Exception("A Parameter object is expected");
        }

        $node = $this->builder->param($component_object->getName())
        ->setType($component_object->getType())
        ->getNode();
        return $node;
    }
}