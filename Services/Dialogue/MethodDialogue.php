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
        $this->component = MethodRequirements::class;
        if ($this->setUpDialogInformationFor($this->component) !== null)
            $this->responses = $this->conversationAbout($this->component);
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
