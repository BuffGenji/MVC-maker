<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Parameter;
use App\Services\Dialogue\Dialogue;
use Exception;

enum ParameterRequirements: string
{
    case PARAMETER_NAME = 'name';
    case PARAMETER_TYPE = 'type';
    public static function getParameterInfo()
    {
        $requirements = [];
        $questions = [];
        foreach (ParameterRequirements::cases() as $requirement) {
            $requirements[] =  $requirement->value;
            $questions[] = "Enter the parameter's " . $requirement->value . " : ";
        }
        return (object) [
            'requirements' => $requirements,
            'questions' => $questions
        ];
    }
}

class ParameterDialogue extends Dialogue
{
    private readonly object $parameter;
    private array $requirements;
    private array $questions;
    private array $responses;

    public function __construct()
    {
        $this->parameter = PropertyRequirements::getPropertyInfo();
        $this->requirements = $this->parameter->requirements ?? [];
        $this->questions = $this->parameter->questions ?? [];

        isset($this->parameter)
            ? $this->responses = $this->conversationAboutParameters()
            : throw new Exception('No parameter requirements or questions');
    }


    private function conversationAboutParameters()
    {
        $done = false;
        $responses = [];
        while (!$done) {
            $responses[] = $this->conversation(
                requirements: $this->requirements,
                questions: $this->questions
            );
            echo "Do you want to add another parameter? (y/n)" . PHP_EOL;
            if ($this->getNextLine() == "n") {
                break;
            }
        }
        return $responses;
    }

    public function getParameters(): array
    {
        $parameters = [];
        foreach ($this->responses as $parameter_requirements) {
            $parameters[] = new Parameter($parameter_requirements);
        }
        return $parameters;
    }
}
