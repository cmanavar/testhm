<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class ServiceCategoryTable extends Table {

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
    public function getcategoryId($id) {
        $servicecategoryTable = TableRegistry::get('ServiceCategory');
        $category = $servicecategoryTable->get($id);
        return $category;
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
    public function getcategorylisting() {
        $servicecategoryTable = TableRegistry::get('ServiceCategory');
        $category = $servicecategoryTable->find('all')->order(['id' => 'ASC']);
        return $category;
    }

    //*******************************************************************************//
    // * Function          :  checkorderidExist
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR Check Ordering id is Exist or not
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function checkorderidExist($order_id) {
        $servicecategoryTable = TableRegistry::get('ServiceCategory');
        $count = $servicecategoryTable->find('all')->where(['order_id' => $order_id])->count();
        if ($count > 0) {
            return true;
        } else {
            return false;
        }
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

}

?>