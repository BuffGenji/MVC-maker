<?php
declare(strict_types=1);

namespace App\Services\Dialog;


enum MethodQuestions : string {
    case METHOD_NAME = 'method_name';
    case METHOD_RETURN_TYPE = 'method_return_type';
    case METHOD_PARAMETER_NAME = 'parameter_name';
    case METHOD_PARAMETER_RETURN_TYPE = 'parameter_return_type';
}

class MethodDialog extends Dialog {


}