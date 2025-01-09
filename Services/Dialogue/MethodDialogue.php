<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Method;
use App\Services\Dialogue\Dialogue;
use App\Traits\getDialogInformation;
use Exception;

enum MethodRequirements: string
{
    use getDialogInformation;
    case METHOD_NAME = 'name';
    case METHOD_RETURN_TYPE = 'type';
}

class MethodDialogue extends Dialogue
{
    private readonly object $method;

    public function __construct()
    {   
        $this->method = $this->setUpDialogInformation(MethodRequirements::class);
        
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
