<?php
declare(strict_types=1);

namespace App\Services\Factories;

use App\Components\Parameter;
use Exception;
use PhpParser\BuilderFactory;
use PhpParser\Node\Param;

class ParameterStatementFactory  {

    private BuilderFactory $factory;
    private array $parameters;

    public function __construct(?array $parameters = null)
    {
        $this->factory = new BuilderFactory;
        if (isset($parameters)) {
            $this->createParameters($parameters);
        }
    }

    public function createParameter(Parameter $parameter) : Param
    {
        $node = $this->factory->param($parameter->getName())
        ->setType($parameter->getType())
        ->getNode();
        return $node;
    }

    private function createParameters(array $parameters) : bool
    {
        foreach($parameters as $parameter) {
            $this->$parameters[] = $this->createParameter($parameter);
        }
        return count($parameters) == count($this->parameters);
    }

    public function getParametersStmt() : array
    {
        return $this->parameters;
    }
}