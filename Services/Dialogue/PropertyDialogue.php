<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Property;
use App\Services\Dialogue\Dialogue;
use App\Traits\getDialogInformation;
use Exception;

enum PropertyRequirements: string
{
    use getDialogInformation;
    case PROPERTY_NAME = "name";
    case PROPERTY_TYPE = "type";
}

class PropertyDialogue extends Dialogue
{
    public function __construct()
    {
        $this->component = PropertyRequirements::class;
        if ($this->setUpDialogInformationFor($this->component) !== null)
            $this->responses = $this->conversationAbout($this->component);
    }
    
    public function getProperties(): array
    {
        $properties = [];
        foreach ($this->responses as $property_requirements) {
            $properties[] = new Property($property_requirements);
        }
        return $properties;
    }
}
