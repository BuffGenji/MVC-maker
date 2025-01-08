<?php

declare(strict_types=1);

namespace App\Services\Dialog;

use App\Components\Property;
use App\Services\Dialog\Dialog;


enum PropertyRequirements: string
{
    case PROPERTY_NAME = "property_name";
    case PROPERTY_TYPE = "property_type";

    public static function getRequirements()
    {
        $requirements = [];
        foreach (PropertyRequirements::cases() as $requirement) {
            $requirements[] =  $requirement->value;
        }
        return $requirements;
    }
    public static function getQuestions()
    {
        $questions = [];
        foreach (PropertyRequirements::cases() as $requirement) {
            $questions[] = "Enter the " . str_replace('_', ' ', $requirement->value) . " : ";
        }
        return $questions;
    }
}

class PropertyDialog extends Dialog
{
    private array $requirements;
    private array $questions;
    private array $responses;

    public function __construct()
    {
        $this->requirements = PropertyRequirements::getRequirements();
        $this->questions = PropertyRequirements::getQuestions();
        $this->responses = $this->conversationAboutProperties();
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
            !$this->isDone() ?: $done = true;
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
