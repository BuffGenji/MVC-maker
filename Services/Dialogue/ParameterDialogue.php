<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use App\Components\Parameter;
use App\Services\Dialogue\Dialogue;
use App\Traits\getDialogInformation;

enum ParameterRequirements: string
{
    use getDialogInformation;
    case PARAMETER_NAME = 'name';
    case PARAMETER_TYPE = 'type';
}

class ParameterDialogue extends Dialogue
{
    private readonly object $parameter;

    public function __construct()
    {
        $this->component = ParameterRequirements::class;
        if ($this->setUpDialogInformationFor($this->component) !== null)
            $this->responses = $this->conversationAbout($this->component);
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
