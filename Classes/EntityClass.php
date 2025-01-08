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
    private readonly string $file_name;

    public function __construct() {
        $this->introduction(); //  sets the file_name
        $this->properties = $this->askForProperties();
        $this->getters_and_setters = (new MethodStatementFactory(
            properties_for_get_and_set: $this->properties->objects, 
            getters_and_setters: true ))
            ->createGettersAndSetters($this->properties->objects); // looks a bit ridiculous, will change later
    }

    public function create(): Class_
    {
        $node = (new BuilderFactory())->class($this->file_name . "Entity")
            ->addStmts(
                $this->properties->statements
            )
            ->addStmts(
                $this->getters_and_setters
            )
            ->addStmts($this->constructor_and_hydration())
            ->getNode();
        return $node;
    }

    private function introduction() {
        echo "Making an Entity " . PHP_EOL;
        echo "Enter entity name : " . PHP_EOL;
        $this->file_name = fgets(STDIN);
    }

}
