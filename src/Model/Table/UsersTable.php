<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class UsersTable extends Table {

    public function initialize(array $config) {
        //  $this->table('Patients');
    }

    public function beforeSave($event, $entity, $options = array()) {
//        $e = $entity->toArray();
//        foreach ($e as $key => $value) {
//            if ($key != "created" && $key != "email" && $key != "password" && $key != "confirm_password") {
//
//                $entity->$key = trim(strtoupper($e[$key]));
//            }
//        }
// return true;
    }

    //*******************************************************************************//
    // * Function          :  getuservalidation
    // * Parameter         :  $getdata //$this->requesr->data
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR USER VALIDATION FROM EMAIL
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getuservalidation($getdata) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find()
                        ->select('email')
                        ->where(['email' => $getdata['email']])->toArray();
        return $user;
    }

    //*******************************************************************************//
    // * Function          :  getuserlisting
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getuserlisting($user_type = '') {
        $userTable = TableRegistry::get('Users');
        if ($user_type == 'hmen') {
            $user = $userTable->find('all')->where(['OR' => [['user_type IN' => ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER']]]])->order(['id' => 'DESC']);
        } elseif ($user_type == 'app') {
            $user = $userTable->find('all')->where(['user_type IN' => ['CUSTOMER', 'MEMBERSHIP']])->order(['id' => 'DESC']);
        } elseif ($user_type == 'vendors') {
            $user = $userTable->find('all')->where(['user_type IN' => ['VENDORS', 'SALES']])->order(['id' => 'DESC']);
        } else {
            $user = $userTable->find('all')->order(['id' => 'DESC']);
        }
        return $user;
    }

    //*******************************************************************************//
    // * Function          :  getuserlisting
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getuserId($id) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->get($id);
        return $user;
    }

    //*******************************************************************************//
    // * Function          :  getuservalidationID
    // * Parameter         :  $id
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR VALIDATION
    // * Author            :  Chirag Manavar
    // * Date              :  08-April-2017
    //******************************************************************************//
    public function getuservalidationID($id) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find('all')->where(['id' => $id])->hydrate(false);
        return $user;
    }

    public function uniqueEmailOrPhone($email, $phone) {
        $userTable = TableRegistry::get('Users');
        if ($email != "") {
            $user = $userTable->find('all')->where(['email' => $email])->hydrate(false)->first();
            if (isset($user) && !empty($user)) {
                $msg = 'Account with Email ID ' . $email . ' already exist!';
                if ($user['active'] == 'N') {
                    $msg = 'Account with Email ID ' . $email . ' already created but account is inactive!';
                }
                return ['status' => 'fail', 'msg' => $msg];
            }
        }
        if ($phone != "") {
            $user = $userTable->find('all')->where(['phone_no' => $phone])->hydrate(false)->first();
            if (isset($user) && !empty($user)) {
                $msg = 'Account with phone number ' . $phone . ' already exist!';
                if ($user['active'] == 'N') {
                    $msg = 'Account with phone number ' . $phone . ' already created but account is inactive!';
                }
                return ['status' => 'fail', 'msg' => $msg];
            }
        }
        return true;
    }

    public function generateAPIkey() {
        $userMappingTable = TableRegistry::get('UserMapping');
        $api_key = md5(microtime() . rand());
        $user = $userMappingTable->find('all')->where(['mapping_key' => 'api_key', 'mapping_value' => $api_key])->hydrate(false)->first();
        if (isset($user) && !empty($user)) {
            $this->generateAPIkey();
        } else {
            return $api_key;
        }
    }

    public function isOtpTrue($user_id, $otp) {
        $otpsTable = TableRegistry::get('Otps');
        $user = $otpsTable->find('all')->where(['user_id' => $user_id, 'otp_number' => $otp])->hydrate(false)->first();
        if (isset($user) && !empty($user)) {
            $expiredTime = strtotime($user['expired']->format('Y-m-d H:i:s'));
            $currentTime = strtotime(date('Y-m-d H:i:s'));
            $diffTime = $expiredTime - $currentTime;
            if ($diffTime > 0) {
                return ['status' => 'success', 'msg' => 'OTP Verified!'];
            } else {
                return ['status' => 'fail', 'msg' => 'Entered OTP number is expired.'];
            }
        } else {
            return ['status' => 'fail', 'msg' => 'Invalid OTP number'];
        }
    }

    public function getApiKey($user_id) {
        $userMappingTable = TableRegistry::get('UserMapping');
        $user = $userMappingTable->find('all')->where(['user_id' => $user_id, 'mapping_key' => 'api_key'])->hydrate(false)->first();
        if (isset($user) && !empty($user)) {
            return $user['mapping_value'];
        } else {
            return ['status' => 'fail', 'msg' => 'Invalid User Id'];
        }
    }

}

?>