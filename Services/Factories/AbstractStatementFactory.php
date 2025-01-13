<?php

declare(strict_types=1);

namespace App\Services\Factories;

use PhpParser\BuilderFactory;


/**
 * This was created because of the recurring pattern in our statement factories,
 * the process of creating individual statemennts and sanding an array of them to be used with the ->addStmts()
 * in the final creation of a class
 */

abstract class AbstractStatementFactory
{

    /**
     * In each factory there is a builder and there is a product
     * in which will go all of the nodes generated y that class so as to be accessible at a later date
     * with a factory-specific getter
     */
    protected BuilderFactory $builder;
    protected array $products;

    /**
     * All of them will have to implement a method in which they craete a singular node - which later will be used
     * in a loop to make all of the elements
     */

    abstract public function createProduct($component_object);


    /**
     * Now that all of the subclasses have a singluar create and already house an array of properties 
     * due to their constructors, we can iterathe through them and set them in the classes
     * 
     * Here we accept an array of componenet objects such as Property, Method and Parameter
     * and using the createProduct (enforced above) we can centralize the creation of all of the statements
     */
    protected function createStatementsFor(array $component_objects)
    {
        $component_stmts = [];
        foreach ($component_objects as $component) {
            $component_stmts[] = $this->createProduct($component);
        }
        return $component_stmts;
    }

    /**
     * We will also supply the factories with getters since their class name already provide sufficient context
     * as to what is being treated in other functions - wherever they may be
     * 
     * This function return an array of statements of that specific class, it uses the $this->products
     */

    public function getProducedStatements(): array
    {
        return $this->products;
    }
}
