<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class ServicesTable extends Table {

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
    public function getservicesId($id) {
        //echo $id; exit;
        $servicesTable = TableRegistry::get('Services');
        $services = $servicesTable->get($id);
        return $services;
    }

    //*******************************************************************************//
    // * Function          :  getservicevalidationID
    // * Parameter         :  $id
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR VALIDATION
    // * Author            :  Chirag Manavar
    // * Date              :  08-April-2017
    //******************************************************************************//
    public function getservicevalidationID($id) {
        $serviceTable = TableRegistry::get('Services');
        $services = $serviceTable->find('all')->where(['id' => $id])->hydrate(false);

        return $services;
    }

    //*******************************************************************************//
    // * Function          :  getcategorylisting
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getserviceslisting() {
        $servicesTable = TableRegistry::get('Services');
        $services = $servicesTable->find('all')->order(['id' => 'ASC']);
        return $services;
    }

    //*******************************************************************************//
    // * Function          :  getCategoryName
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function getCategoryName($category_id) {
        $serviceCategoryTable = TableRegistry::get('ServiceCategory');
        $categories = $serviceCategoryTable->find()->select(['name'])->where(['id' => $category_id])->hydrate(false)->first();
        return (isset($categories['name']) && $categories['name'] != '') ? $categories['name'] : '-';
    }

    //*******************************************************************************//
    // * Function          :  hasOrders
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK SERVICES HAS ORDERS OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function hasOrders($service_id) {
        $ordersTable = TableRegistry::get('Orders');
        $count = $ordersTable->find('all')->where(['service_id' => $service_id])->count();
        if ($count == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getServiceName($id) {
        $serviceTable = TableRegistry::get('Services');
        $services = $serviceTable->find()->select(['service_name'])->where(['id' => $id])->hydrate(false)->first();
        return (isset($services['service_name']) && $services['service_name'] != '') ? $services['service_name'] : '-';
    }

    public function getCategoryIdusingServiceId($id) {
        $serviceTable = TableRegistry::get('Services');
        $services = $serviceTable->find()->select(['category_id'])->where(['id' => $id])->hydrate(false)->first();
        return (isset($services['category_id']) && $services['category_id'] != '') ? $services['category_id'] : '-';
    }

    public function getServiceBannerPath($id) {
        $serviceTable = TableRegistry::get('Services');
        $services = $serviceTable->find()->select(['banner_image'])->where(['id' => $id])->hydrate(false)->first();
        return (isset($services['banner_image']) && $services['banner_image'] != '') ? IMAGE_URL_PATH . 'services/banner/' . $services['banner_image'] : '';
    }

    public function getServiceImagePath($id) {
        $serviceTable = TableRegistry::get('Services');
        $services = $serviceTable->find()->select(['square_image'])->where(['id' => $id])->hydrate(false)->first();
        return (isset($services['square_image']) && $services['square_image'] != '') ? IMAGE_URL_PATH . 'services/square/' . $services['square_image'] : '';
    }
    
}

?>