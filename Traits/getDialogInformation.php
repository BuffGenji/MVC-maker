<?php

declare(strict_types=1);

namespace App\Traits;

trait getDialogInformation
{

    public static function getQuestionsAndRequirementsFor(string $component)
    {
        $requirements = [];
        $questions = [];
        foreach (self::cases() as $requirement) {
            $requirements[] =  $requirement->value;
            $questions[] = "Enter the $component " . $requirement->value . " : ";
        }
        return (object) [
            'requirements' => $requirements,
            'questions' => $questions

        ];
    }
}
