<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Components\Property;
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
class PropertyStatementFactory
{

    private BuilderFactory $factory;
    private array $properties;

    public function __construct(array $properties)
    {
        $this->factory = new BuilderFactory;
        isset($properties)
            ? $this->createProperties($properties)
            : throw new Exception('No properties listed');
    }

    /**
     * Creates the property Statement node in the AST 
     */
    private function createProperty(Property $property) : StmtProperty
    {
        $node = $this->factory->property($property->getName())
            ->makePrivate()
            ->setType($property->getType())
            ->getNode();
        return $node;
    }

    /**
     * The array $properties is populated with Property objects
     */
    private function createProperties(array $properties) : bool
    {
        foreach ($properties as $property) {
            $this->properties[] = $this->createProperty($property);
        }
        // if the funciton worked or not. so it can be used as a condition
        return count($properties) == count($this->properties); 
    }

    public function getPropertiesStmt()
    {
        return $this->properties;
    }
}
