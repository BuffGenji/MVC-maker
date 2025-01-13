<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use Error;
use Exception;
use InvalidArgumentException;

/**
 * This class is to regulate what a dialogue will do. Every time we need a component we will open a dialogue
 * which will instantiate a subclass. 
 * 
 * All sublasses have their corresponding getters, which will export entities ( here Components ) 
 * that the facotries can work with later on to create statements to be added to the AST
 * 
 */

abstract class Dialogue
{
    /**
     * In every dialogue there are requirements, which sprout questions which then result in reponses.
     * 
     * It is for this reason that they are here, and are used in every subclass
     */
    protected array $requirements;
    protected array $questions;
    protected array $responses;

    /** 
     * Furthermore, there is also a component for each dialogue 
     * which will contain the class name of the enum that contains the components requirements
     * 
     * A component is the sum of its requirements
     */
    protected string $component;

    /**
     * This funciton will set up the correct enums for the dialogues
     * returns an anonymous object holding the requirements and the questions, handing off exceptions 
     * to individual subclasses for a better error handling system
     * @return object  
     */
    protected function setUpDialogInformationFor($enum_of_requirements_class_name)
    {
        // PHP doesn't differentiate enums and objects, but we can check with these funcitons
        if (!enum_exists($enum_of_requirements_class_name)) {
            throw new InvalidArgumentException('Parameter has to be an enum');
        }

        // get the component name, to insert into the quesitons asked in the CLI
        // current enum class names : MethodRequirements, PropertyRequirements  . . .
        $component = basename(mb_strtolower(str_replace('Requirements', '', $enum_of_requirements_class_name)));

        // handling possible invalid classname or missing trait, resulting in uncallable method
        try {
            $element = $enum_of_requirements_class_name::getQuestionsAndRequirementsFor($component);
        } catch (Error) {
            throw new Exception("Trait getDialogInformation not set on $enum_of_requirements_class_name");
        }

        /** This checks that the enums have been filled correctly, 
         * and correctly return an object with both properties
         */
        $this->requirements = $element->requirements
            ?? throw new Exception("No requirements found in $enum_of_requirements_class_name");
        $this->questions = $element->questions
            ?? throw new Exception("No questions found in $enum_of_requirements_class_name");
        return $element;
    }

    protected function getNextLine()
    {
        return trim(fgets(STDIN));
    }

    /**
     * Goes through each question and gets the response from the user
     */
    public function conversation(array $questions, array $requirements): array
    {
        $responses = [];
        for ($i = 0; $i < count($questions); $i++) {
            echo $questions[$i] . PHP_EOL;
            $responses[$requirements[$i]] = $this->getNextLine();
        }
        return $responses;
    }


    /**
     * Here we abstract away having a conversation about something - the requirements of a component
     * ( Method , Parameter . . . ) . This is mean to make dependency injecttion easier
     * while maintaining the classnames for clarity
     * 
     * It is protected so that it can only be used in a Dialog
     * 
     */

    protected function conversationAbout(string $enum_requirements_name)
    {
        $done = false;
        $responses = [];
        $component_name = basename(mb_strtolower(str_replace('Requirements', '', $enum_requirements_name)));
        while (!$done) {
            $responses[] = $this->conversation(
                requirements: $this->requirements,
                questions: $this->questions
            );
            echo "Do you want to add another {$component_name}? (y/n)" . PHP_EOL;
            if ($this->getNextLine() == "n") {
                break;
            }
        }
        return $responses;
    }
}
