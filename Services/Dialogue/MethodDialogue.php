<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Method;
use App\Services\Dialogue\Dialogue;
use Exception;

enum MethodRequirements: string
{
    case METHOD_NAME = 'name';
    case METHOD_RETURN_TYPE = 'type';

    public static function getMethodInfo()
    {
        $requirements = [];
        $questions = [];
        foreach (MethodRequirements::cases() as $requirement) {
            $requirements[] =  $requirement->value;
            $questions[] = "Enter the method's " . $requirement->value . " : ";
        }
        return (object) [
            'requirements' => $requirements,
            'questions' => $questions
        ];
    }
}

class MethodDialogue extends Dialogue
{
    private readonly object $method;
    private array $requirements;
    private array $questions;
    private array $responses;

    public function __construct()
    {
        $this->method = MethodRequirements::getMethodInfo();
        $this->requirements = $this->method->requirements ?? [];
        $this->questions = $this->method->questions ?? [];

        isset($this->method)
            ? $this->responses = $this->conversationAboutMethods()
            : throw new Exception('No method requirements or questions');
    }

    private function conversationAboutMethods() : array
    {
        $done = false;
        $responses = [];
        while (!$done) {
            $responses[] = $this->conversation(
                requirements: $this->requirements,
                questions: $this->questions
            );
            echo "Do you want to add another method? (y/n)" . PHP_EOL;
            if ($this->getNextLine() == "n") {
                break;
            }
        }
        return $responses;
    }

    public function getMethods() : array
    {
        $methods = [];
        foreach($this->responses as $method_data) {
            $methods[] = new Method($method_data);
        }
        return $methods;
    }
    




}
