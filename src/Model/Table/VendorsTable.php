<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class VendorsTable extends Table {

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
    public function getVendorId($id) {
        $usersTable = TableRegistry::get('Users');
        $vendorsTable = TableRegistry::get('VendorDetails');
        $vendors = $usersTable->find('all')->where(['user_type IN' => ['VENDOR', 'SALES'], 'id' => $id])->order(['id' => 'DESC'])->hydrate(false)->first();
        $vendorsData = $vendorsTable->find('all')->where(['user_id' => $vendors['id']])->hydrate(false)->first();
        $vendors['service_id'] = $vendorsData['service_id'];
        $vendors['service_name'] = $this->getServiceName($vendorsData['service_id']);
        $vendors['agreement'] = $vendorsData['agreement'];
        $vendors['id_proof'] = $vendorsData['id_proof'];
        return $vendors;
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
    public function getVendors() {
        $usersTable = TableRegistry::get('Users');
        $vendorsTable = TableRegistry::get('VendorDetails');
        $vendors = $usersTable->find('all')->where(['user_type IN' => ['VENDOR', 'SALES']])->order(['id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($vendors as $key => $val) {
            $vendorsData = $vendorsTable->find('all')->where(['user_id' => $val['id']])->hydrate(false)->first();
            $vendors[$key]['service_id'] = $vendorsData['service_id'];
            $vendors[$key]['service_name'] = $this->getServiceName($vendorsData['service_id']);
            $vendors[$key]['agreement'] = $vendorsData['agreement'];
            $vendors[$key]['id_proof'] = $vendorsData['id_proof'];
        }
        //pr($vendors); exit;
        return $vendors;
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
    public function vendorExists($vendor_id) {
        $userTable = TableRegistry::get('Users');
        return $userTable->find('all')->where(['id' => $vendor_id, 'user_type IN' => ['VENDOR', 'SALES']]);
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

    public function getVendorDetailsID($user_id) {
        $vendorsDetailTable = TableRegistry::get('VendorDetails');
        $vendor = $vendorsDetailTable->find('all')->where(['user_id' => $user_id])->hydrate(false)->first();
        if (isset($vendor) && !empty($vendor)) {
            return $vendor['id'];
        } else {
            return 0;
        }
    }
    
}

?>