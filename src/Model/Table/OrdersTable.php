<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class OrdersTable extends Table {

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
    public function createOrderId() {
        //echo $id; exit;
        $ordersTable = TableRegistry::get('Orders');
        $order_id = rand(100000000, 999999999);
        $condArr = ['order_id' => $order_id];
        $count = $ordersTable->find('all')->where(['order_id' => $order_id])->count();
        if ($count > 0) {
            $this->createOrderId();
        } else {
            return $order_id;
        }
    }


}

?>