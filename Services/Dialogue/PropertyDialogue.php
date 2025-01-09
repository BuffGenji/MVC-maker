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
    private readonly object $property;

    public function __construct()
    {
        $this->property = $this->setUpDialogInformation(PropertyRequirements::class);

        isset($this->property)
            ? $this->responses = $this->conversationAboutProperties()
            : throw new Exception('No property requirements or questions');
    }

    private function conversationAboutProperties()
    {
        $done = false;
        $responses = [];
        while (!$done) {
            $responses[] = $this->conversation(
                requirements: $this->requirements,
                questions: $this->questions
            );
            echo "Do you want to add another property? (y/n)" . PHP_EOL;
            if ($this->getNextLine() == "n") {
                break;
            }
        }
        return $responses;
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
