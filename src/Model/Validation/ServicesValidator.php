<?php

namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class ServicesValidator extends Validator {

    public function __construct() {
        parent::__construct();
        $this->notEmpty('service_name', 'NAME IS REQUIRD')
                ->notEmpty('service_description', 'DESCRIPTIONS IS REQUIRED')
                ->notEmpty('category_id', 'PLEASE SELECT CATEGORY')
                ->notEmpty('status', 'STATUS IS REQUIRED')
                ->notEmpty('visit_charge', 'VISIT CHARGE IS REQUIRED')
                ->notEmpty('minimum_charge', 'MINIMUM CHARGE IS REQUIRED')
                ->notEmpty('banner', 'BANNER IMAGE IS REQUIRED')
                ->notEmpty('square', 'SQUARE IMAGE IS REQUIRED');
    }

}
