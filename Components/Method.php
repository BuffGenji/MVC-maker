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
    private string $type;


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

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $return_type): void
    {
        $this->type = $return_type;
    }

    }
