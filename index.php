<?php
declare(strict_types=1);

namespace App;

use App\Services\Dialog\PropertyDialog;

require '../vendor/autoload.php';


echo "Making an Entity" . PHP_EOL;
$entity_properties = new PropertyDialog;
// $entity_properties->getProperties();