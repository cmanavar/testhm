<?php

namespace App\Model\Validation;

use Cake\Validation\Validator;

class UsersValidator extends Validator {

    public function __construct() {
        parent::__construct();
        $this->notEmpty('name', 'NAME IS REQUIRD')
                ->notEmpty('email', 'EMAIL IS REQUIRED')
                ->add('email', 'validFormat', [
                    'rule' => 'email',
                    'message' => 'THE EMAIL MUST BE A VALID EMAIL ADDRESS'
                ])
                ->notEmpty('password', 'PASSWORD IS REQUIRED')
                ->notEmpty('confirm_password', 'CONFIRM PASSWORD IS REQUIRED')
                ->add('confirm_password', 'no-misspelling', [
                    'rule' => ['compareWith', 'password'],
                    'message' => 'PASSWORD ARE NOT EQUAL',
                ])
                ->notEmpty('user_type', 'USER TYPE IS REQUIRED');
    }

}
