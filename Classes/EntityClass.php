<?php

declare(strict_types=1);

namespace App\Classes;

use App\Services\Factories\MethodStatementFactory;
use PhpParser\BuilderFactory;
use PhpParser\Node\Stmt\Class_;

/**
 * The EntityClass will create entity classes. Which include the properties, the correpsonnding getters and setter
 * THAT COMPLY WITH the hydrate function, and then of course the hydrate function.
 */
class EntityClass extends MVCElement
{

    /**
     * This is an interesting class because here the methods will be created dynmically, we don not ask the user for them
     * beause they are boilerplate. However, we still use the MethodFactory
     */
    private object $properties;
    private array $getters_and_setters;
    
    public function __construct() {
        $this->introduction("Entity");
        $this->properties = $this->askForProperties();
        $apples = $this->askForMethods();
        $this->getters_and_setters = $this->createGettersAndSettersAutomatically();
    }

    public function create(): Class_
    {
        $node = (new BuilderFactory())->class($this->file_name)
            ->addStmts(
                $this->properties->statements   
            )
            ->addStmts(
                $this->constructor_and_hydration()
            )
            ->addStmts(
                $this->getters_and_setters
            )
            ->getNode();
        return $node;
    }

    private function createGettersAndSettersAutomatically() : array
    {
        echo " Do you want to create getters and setters? (y/n)" . PHP_EOL;
        if (fgets(STDIN) !== 'y') {
            $method_factory = 
            new MethodStatementFactory(
                properties_for_get_and_set: $this->properties->objects, 
                want_getters_and_setters: true 
            );
            return $method_factory->createGettersAndSetters(); 
        }
    }
}
