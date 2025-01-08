<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Property;
use App\Services\Dialogue\Dialogue;
use Exception;

enum PropertyRequirements: string
{
    case PROPERTY_NAME = "name";
    case PROPERTY_TYPE = "type";

    public static function getPropertyInfo()
    {
        $requirements = [];
        $questions = [];
        foreach (PropertyRequirements::cases() as $requirement) {
            $requirements[] =  $requirement->value;
            $questions[] = "Enter the property " . $requirement->value . " : ";
        }
        return (object) [
            'requirements' => $requirements,
            'questions' => $questions

        ];
    }
}

class PropertyDialogue extends Dialogue
{
    private readonly object $property;
    private array $requirements;
    private array $questions;
    private array $responses;

    public function __construct()
    {
        $this->property = PropertyRequirements::getPropertyInfo();
        $this->requirements = $this->property->requirements ?? [];
        $this->questions = $this->property->questions ?? [];

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
