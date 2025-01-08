<?php

declare(strict_types=1);

namespace App\Services\Factories;

use App\Components\Method;
use App\Components\Parameter;
use App\Components\Property;
use App\Services\Dialogue\ParameterDialogue;
use Exception;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\PropertyFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Return_;

/**
 * This factory is slightly trickier given that parameters are embedded into the methodd and then for one we need the other
 * there are two situations here.
 * 
 * The first is that the methods are unique and the user will need to enter specific details
 * The second is boilerplate, such as getters and setters
 * 
 * In the event of the second one there will be special getter and setter functions
 * For the first there will be a dilogue created whenn the methods are made
 */

class MethodStatementFactory
{

    private BuilderFactory $factory;
    private array $methods;
    /**
     * The getters and setters option is there for added comfort when asking for elements in the EntityClass
     */
    public function __construct(?array $methods = null, $getters_and_setters =  false, $properties_for_get_and_set = [])
    {
        $this->factory = new BuilderFactory();
        // isset($methods) && empty($getters_and_setters)
        //     ? $this->createMethods($methods)
        //     : throw new Exception('No properties listed');
        !isset($methods) && !empty($properties_for_get_and_set) && $getters_and_setters == true
            ? $this->createGettersAndSetters($properties_for_get_and_set)
            : throw new Exception("Problem generating getters and setters");
    }

    // private function createMethod(Method $method): ClassMethod
    // {
    //     $node = $this->factory->method($method->getName())
    //         ->makePrivate()
    //         ->setReturnType($method->getReturnType())
    //         ->addParam()
    //         ->getNode();
    //     return $node;
    // }


    private function createGetter(Property $property): ClassMethod
    {
        $node = $this->factory->method('get' . ucfirst($property->getName()))
            ->makePublic()
            ->setReturnType($property->getType())
            ->addStmt(new Return_(new PropertyFetch(new Variable('this'), $property->getName())))
            ->getNode();
        return $node;
    }




    private function createSetter(Property $property): ClassMethod
    {
        // to refactor, very very ugly
        $factory= (new ParameterStatementFactory());
        $parameter = new Parameter([
            'name' => $property->getName(),
            'type' => $property->getType()
        ]);
        $parameter = $factory->createParameter($parameter);

        $node = $this->factory->method('set' . ucfirst($property->getName()))
            ->makePublic()
            ->addParam($parameter)
            ->addStmt(new Assign( new PropertyFetch(new Variable('this'), $property->getName()), new Variable($property->getName())))
            ->setReturnType('void')
            ->getNode();
        return $node;
    }

    /**
     * The properties are in fact Property objects,
     * this returns an array of statemenst ready to place ina a class
     */
    public function createGettersAndSetters(array $properties_for_get_and_set): array
    {
        $boilerplate_get_set = [];
        foreach ($properties_for_get_and_set as $method) {
            $boilerplate_get_set[] = $this->createGetter($method);
            $boilerplate_get_set[] = $this->createSetter($method);
        }
        return $boilerplate_get_set;
    }
}
