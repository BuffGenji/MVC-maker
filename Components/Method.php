<?php

declare(strict_types=1);

namespace App\Components;


/**
 * As of right now this only supports one parameter
 * I will figure this out later, it is not of upmost importance
 */

class Method
{
    private string $name;
    private string $return_type;
    private string $parameter_name;
    private string $parameter_type;


    public  function __construct(array $data)
    {
        $this->hydrate($data);
    }


    public function hydrate(array $donnees): void
    {
        foreach ($donnees as $key => $value) {
            $method = 'set' . ucfirst($key);

            if (method_exists($this, $method)) {
                $this->$method($value);
            }
        }
    }


    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getReturnType(): string
    {
        return $this->return_type;
    }

    public function setReturnType(string $return_type): void
    {
        $this->return_type = $return_type;
    }

    public function getParameterName(): string
    {
        return $this->parameter_name;
    }

    public function setParameterName(string $parameter_name): void
    {
        $this->parameter_name = $parameter_name;
    }

    public function getParameterType() : string
    {
        return $this->parameter_type;
    }

    public function setParameterType(string $parameter_type) : void
    {
        $this->parameter_type = $parameter_type;
    }
}
