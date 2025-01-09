<?php

declare(strict_types=1);

namespace App\Services\Dialogue;

use InvalidArgumentException;

/**
 * This  class is to regulate what a dialogue will do. Every time we need a component we will open a dialogue
 * which is just to create an object of a subclass of this.
 * 
 * 
 * Given that all of the *Dialogue classes do the same things and now 
 * 
 */
abstract class Dialogue
{
    /**
     * In every dialogue there are requirements, which sprout questions which then result in reponses.
     * 
     * It is for this reason that they are here, and are used in eveyr subclass
     */

    protected array $requirements;
    protected array $questions;
    protected array $responses;

    /**
     * This funciton will set up the correct enums for the dialogues
     * returns an anonymous object holding the requirements and the questions, handing off exceptions 
     * to individual subclasses for a better error handling system
     * @return object  
     */
    protected function setUpDialogInformation($enum_of_requirements_class_name) 
    {
        // PHP doesn't differentiate enums and objects, but we can check with these funcitons
        if (!enum_exists($enum_of_requirements_class_name)) {
            throw new InvalidArgumentException('Parameter has to be an enum');
        

        // get the component name, to insert into the quesitons asked in the CLI
        $component = basename(mb_strtolower(str_replace('Requirements','',$enum_of_requirements_class_name)));

        // setting the class properties requirements and questions
        $element = $enum_of_requirements_class_name::getQuestionsAndRequirementsFor($component);
            
        if($element == null) {
            throw new \Exception('Trait getDialogInformation not set');
        }

        $this->requirements = $element?->requirements ?? [];
        $this->questions = $element?->questions ?? [];

        return $element;
        
    }


    public function getNextLine()
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
    public function isDone()
    {
        echo "Are you finished ? (y/n) " . PHP_EOL;
        if ($this->getNextLine() == "y") {
            return true;
        }
        return false;
    }
}
