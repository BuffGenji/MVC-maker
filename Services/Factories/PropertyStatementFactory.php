<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Components\Property;
use BadMethodCallException;
use Exception;
use PhpParser\BuilderFactory;
use PhpParser\Node;
use PhpParser\Node\Stmt\Property as StmtProperty;

/**
 * The purpose of this class is to convert object to the Property class
 * to statemennts we can use to build our classes with the nikic/PHP-Parser library
 * 
 * NOTE: All properties are set to private by default
 */
class PropertyStatementFactory extends AbstractStatementFactory
{

    public function __construct(array $properties)
    {
        $this->builder = new BuilderFactory;
        $this->products = $this->createStatementsFor($properties);
    }

    /**
     * Creates property statement node
     */
    public function createProduct($component_object): StmtProperty
    {
        // object check
        if (!($component_object instanceof Property)) {
            throw new Exception("A Property object is expected");
        }

        // creation of node
        $node = $this->builder
            ->property($component_object->getName())
            ->makePrivate()
            ->setType($component_object->getType())
            ->getNode();

        return $node;
    }
}
