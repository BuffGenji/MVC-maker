<?php
declare(strict_types=1);

namespace App\Components;

class Parameter {
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
    public function getType(): string
    {
        return $this->type;
    }

    public function setName(string $name) 
    {
        $this->name = $name;
    }
    public function setType(string $type)
    {
        $this->type = $type;
    }
}