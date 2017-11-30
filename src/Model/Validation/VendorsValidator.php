<?php

namespace App\Model\Validation;

use Cake\Validation\Validator;
use Cake\ORM\Rule\IsUnique;

class VendorsValidator extends Validator {

    public function __construct() {
        parent::__construct();
        $this->notEmpty('name', 'NAME IS REQUIRD')
                ->notEmpty('email', 'EMAIL IS REQUIRED')
                ->notEmpty('phone_number_1', 'PHONE NUMBER 1 IS REQUIRED')
                ->notEmpty('agreement', 'AGREEMENT IS REQUIRED')
                ->notEmpty('id_proof', 'ID PROOF IS REQUIRED')
                ->notEmpty('shift_start', 'SHIFT START IS REQUIRED')
                ->notEmpty('shift_end', 'SHIFT END IS REQUIRED')
                ->notEmpty('status', 'STATUS IS REQUIRED');
    }

}
