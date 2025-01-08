<?php

declare(strict_types=1);

namespace App\Services\Dialog;



/**
 * This  class is to regulate what a dialogue will do. Every time we need a component we will open a dialogue
 * which is just to create an object of a subclass of this.
 * 
 */
abstract class Dialog
{


    public function getNextLine()
    {
        return trim(fgets(STDIN));
    }
    /**
     * Goes through each question and gets the response from the user
     */
    public function conversation(array $questions, array $requirements) : array
    {
        $responses = [];
        for ($i=0; $i<count($questions) ; $i++) { 
            echo $questions[$i] . PHP_EOL;
            $responses[$requirements[$i]] = $this->getNextLine();
        }
        return $responses;
    }
    public function isDone() {
        echo "Are you finished ? (y/n) " . PHP_EOL;
        if ($this->getNextLine() == "y") {
            return;
        }
    }

}
