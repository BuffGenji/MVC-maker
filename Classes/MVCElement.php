<?php
declare(strict_types=1);

namespace App\Classes;

use App\Services\Dialogue\PropertyDialogue;
use App\Services\Factories\PropertyStatementFactory;
use PhpParser\BuilderFactory;
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Expr\BinaryOp\Concat;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;

/**
 * All classes will be able to do the follwing, without this being instantiable
 * 
 * All of the ask* functions will be to fill out the different elements of a class. It is the obligation of the 
 * particular class to ask for the information they need
 */
abstract class MVCElement {

    /**
     * All askFor* functions create a dialogue and return the statements from their respective Factories 
     * first we start a dialogue in which we get user innformation
     * then we create our statements to be inserted into the AST
     * 
     * @see App\Services\Factories
     */


    public function askForProperties() : object
    {
        $properties = (new PropertyDialogue())->getProperties();
        $property_statements = (new PropertyStatementFactory($properties))->getPropertiesStmt();
        return (object) [
            'objects' => $properties,
            'statements' => $property_statements
        ];
    }

    public function askForMethods() : array
    {
        // . . .
        return [];
    }


    /**
     * ChatGPT made this. it works, don't touch
     */
    public function constructor_and_hydration() {
        $factory = new BuilderFactory;
        $constructorMethod = $factory->method('__construct')
        ->makePublic()
        ->addParam($factory->param('data')->setType('array')->setDefault([]))
        ->addStmt(new MethodCall(new Variable('this'), 'hydrate', [new Variable('data')]))
        ->getNode();

    $hydrateMethod = $factory->method('hydrate')
        ->makePublic()
        ->addParam($factory->param('data')->setType('array'))
        ->addStmt(new Foreach_(
            new Variable('data'),
            new Variable('value'),
            [
                'keyVar' => new Variable('key'),
                'stmts' => [
                    new Expression(new Assign(
                        new Variable('method'),
                        new Concat(
                            new String_('set'),
                            new FuncCall(new Name('ucfirst'), [new Variable('key')])
                        )
                    )),
                    new If_(
                        new FuncCall(new Name('method_exists'), [
                            new Variable('this'),
                            new Variable('method')
                        ]),
                        [
                            'stmts' => [
                                new Expression(new MethodCall(
                                    new Variable('this'),
                                    new Variable('method'),
                                    [new Variable('value')]
                                ))
                            ]
                        ]
                    )
                ]
            ]
        ))
        ->getNode();
        return [$constructorMethod,$hydrateMethod];
    }

    /**
     * This function is to make  sure all classes can be created and when they do that they
     * are actual classes. The actual writting a class to a file will be done elsewhere.
     */
    abstract public function create() : Class_;

}