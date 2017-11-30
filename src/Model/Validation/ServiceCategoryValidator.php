<?php

namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class ServiceCategoryValidator extends Validator {

    public function __construct() {
        parent::__construct();
        $this->notEmpty('name', 'NAME IS REQUIRD')
                ->notEmpty('order_id', 'ORDER ID IS REQUIRED')
                ->notEmpty('is_popular', 'POPULAR CATEGORY IS REQUIRED')
                ->notEmpty('status', 'CATEGORY STATUS IS REQUIRED')
                ->notEmpty('icon', 'CATEGORY ICON IS REQUIRED')
                ->notEmpty('banner', 'CATEGORY ICON IS REQUIRED')
                ->notEmpty('icon', 'CATEGORY ICON IS REQUIRED');
    }

}
