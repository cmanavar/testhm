<?php
// Custom Handler - goes in src/Error/AppError.php
namespace App\Controller\Error;

use Cake\Routing\Exception\MissingControllerException;
use Cake\Error\ErrorHandler;

class AppError extends ErrorHandler
{
    public function _displayException($exception)
    {
        if ($exception instanceof MissingControllerException) {
           //echo "1";exit;
        } else {
            parent::_displayException($exception);
        }
    }
}