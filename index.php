<?php

declare(strict_types=1);

namespace App;

use App\Classes\EntityClass;
use App\Services\Writer\FileWriter;

require '../vendor/autoload.php';


new FileWriter(new EntityClass());

