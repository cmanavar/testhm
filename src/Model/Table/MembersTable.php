<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class MembersTable extends Table {

    public function initialize(array $config) {
        
    }

    public function beforeSave($event, $entity, $options = array()) {
        
    }

    //*******************************************************************************//
    // * Function          :  getcategoryId
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function geMemberId($id) {
        $usersTable = TableRegistry::get('Users');
        $plansTable = TableRegistry::get('Plans');
        $usersDetailTable = TableRegistry::get('UserDetails');
        $members = $usersTable->find('all')->where(['user_type' => 'MEMBERSHIP', 'id' => $id])->order(['id' => 'DESC'])->hydrate(false)->first();
        $userData = $usersDetailTable->find('all')->where(['user_id' => $members['id']])->hydrate(false)->first();
        $reference_user_name = '-';
        $reference_user_name = $this->getUserName($members['referral_id']);
        $members['reference_user_name'] = $reference_user_name;
        $members['plan_name'] = $this->getPlanName($members['plan_id']);
        $members['person_1'] = $userData['person_1'];
        $members['birthdate_1'] = (isset($userData['birthdate_1']) && $userData['birthdate_1']->format('Y-m-d') != '1980-01-01') ? $userData['birthdate_1'] : '';
        $members['person_2'] = $userData['person_2'];
        $members['birthdate_2'] = (isset($userData['birthdate_2']) && $userData['birthdate_2']->format('Y-m-d') != '1980-01-01') ? $userData['birthdate_2'] : '';
        $members['person_3'] = $userData['person_3'];
        $members['birthdate_3'] = (isset($userData['birthdate_3']) && $userData['birthdate_3']->format('Y-m-d') != '1980-01-01') ? $userData['birthdate_3'] : '';
        $members['person_4'] = $userData['person_4'];
        $members['birthdate_4'] = (isset($userData['birthdate_4']) && $userData['birthdate_4']->format('Y-m-d') != '1980-01-01') ? $userData['birthdate_4'] : '';
        $members['person_5'] = $userData['person_5'];
        $members['birthdate_5'] = (isset($userData['birthdate_5']) && $userData['birthdate_5']->format('Y-m-d') != '1980-01-01') ? $userData['birthdate_5'] : '';
        $members['occupation'] = $userData['occupation'];
        $members['company_name'] = $userData['company_name'];
        $members['company_website'] = $userData['company_website'];
        $members['payment_type'] = $userData['payment_type'];
        $members['bank_name'] = $userData['bank_name'];
        $members['cheque_no'] = $userData['cheque_no'];
        $members['cheque_date'] = $userData['cheque_date'];
        $members['transcation_id'] = $userData['transcation_id'];
        $members['other_details'] = $userData['other_details'];
        //pr($members); exit;
        return $members;
    }

    //*******************************************************************************//
    // * Function          :  getcategoryvalidationID
    // * Parameter         :  $id
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR VALIDATION
    // * Author            :  Chirag Manavar
    // * Date              :  08-April-2017
    //******************************************************************************//
    public function getcategoryvalidationID($id) {
        $servicecategoryTable = TableRegistry::get('ServiceCategory');
        $category = $servicecategoryTable->find('all')->where(['id' => $id])->hydrate(false);
        return $category;
    }

    //*******************************************************************************//
    // * Function          :  getcategorylisting
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getMembers() {
        $usersTable = TableRegistry::get('Users');
        $userDetailsTable = TableRegistry::get('UserDetails');
        $plansTable = TableRegistry::get('Plans');
        $users = $usersTable->find('all')->select(['id', 'name', 'phone_no', 'email', 'address', 'city', 'active', 'plan_id'])->where(['user_type' => 'MEMBERSHIP'])->order(['id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($users as $key => $val) {
            $planName = $this->getPlanName($val['plan_id']);
            $users[$key]['plan_name'] = $planName;
            $userDetails = $userDetailsTable->find('all')->select(['payment_type', 'bank_name', 'cheque_no', 'cheque_date', 'transcation_id', 'other_details'])->where(['user_id' => $val['id']])->hydrate(false)->first();
            if (!empty($userDetails)) {
                foreach ($userDetails as $k => $v) {
                    $users[$key][$k] = $v;
                }
            }
        }
        return $users;
    }

    public function getPlanName($plan_id) {
        $plansTable = TableRegistry::get('Plans');
        $planDetails = $plansTable->find()->select(['name'])->where(['id' => $plan_id])->hydrate(false)->first();
        return (isset($planDetails['name']) && $planDetails['name'] != '') ? $planDetails['name'] : '-';
    }

    public function getUserName($user_id) {
        $usersTable = TableRegistry::get('Users');
        $userDetails = $usersTable->find()->select(['name'])->where(['id' => $user_id])->hydrate(false)->first();
        return (isset($userDetails['name']) && $userDetails['name'] != '') ? $userDetails['name'] : '-';
    }

    public function getServiceName($service_id) {
        $servicesTable = TableRegistry::get('Services');
        $services = $servicesTable->find()->select(['service_name'])->where(['id' => $service_id])->hydrate(false)->first();
        return (isset($services['service_name']) && $services['service_name'] != '') ? $services['service_name'] : '-';
    }

    //*******************************************************************************//
    // * Function          :  checkorderidExist
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR Check Ordering id is Exist or not
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function memberExists($user_id) {
        $userTable = TableRegistry::get('Users');
        return $userTable->find('all')->where(['id' => $user_id, 'user_type' => 'MEMBERSHIP']);
    }

    //*******************************************************************************//
    // * Function          :  hasServices
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function hasServices($category_id) {
        $servicesTable = TableRegistry::get('Services');
        $count = $servicesTable->find('all')->where(['category_id' => $category_id])->count();
        if ($count == 0) {
            return true;
        } else {
            return false;
        }
    }

    //*******************************************************************************//
    // * Function          :  servicesCount
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR GET TOTAL NUMBER OF SERVICES BASED CATGORY ID
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function servicesCount($category_id) {
        $servicesTable = TableRegistry::get('Services');
        $count = $servicesTable->find('all')->where(['category_id' => $category_id])->count();
        return $count;
    }

    public function getUserDetailsID($user_id) {
        $usersDetailTable = TableRegistry::get('UserDetails');
        $user = $usersDetailTable->find('all')->where(['user_id' => $user_id])->hydrate(false)->first();
        if (isset($user) && !empty($user)) {
            return $user['id'];
        } else {
            return ['status' => 'fail', 'msg' => 'Invalid User Id'];
        }
    }

}

?>