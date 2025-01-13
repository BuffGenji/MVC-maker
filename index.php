<?php

declare(strict_types=1);

namespace App;

use App\Classes\ControllerClass;
use App\Classes\EntityClass;
use App\Classes\ModelClass;
use App\Services\Writer\FileWriter;

require '../vendor/autoload.php';


// $done = false;

// $mvc_elements = ['Controller', 'Model', 'Entity'];

// echo "What do you want to make?" . PHP_EOL;
// foreach ($mvc_elements as $element) {
//     echo " - " . $element . PHP_EOL;
// }
// echo "Enter 1 for Controller, 2 for Model 3 for Entity " . PHP_EOL;

// $choice = (function () {
//     return fgetc(STDIN);
// })();

// if ($choice == "3") {
//     new FileWriter(EntityClass::class);
// }
    new FileWriter(ControllerClass::class);
