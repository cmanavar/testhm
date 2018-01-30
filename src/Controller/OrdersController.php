<?php

/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */

namespace App\Controller;

use App\Controller\AppController;
use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use App\Model\Validation\ServicesValidator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : ServicecategoryController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class OrdersController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['updateanswer', 'addnewanswer']);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function index() {
        $questions = [];
        $this->loadModel('Orders');
        $orders = $this->Orders->find('all')->hydrate(false)->toArray();
        $rslt = [];
        foreach ($orders as $order) {
            $tmp = [];
            $tmp['id'] = $order['id'];
            $tmp['user_id'] = $order['user_id'];
            $tmp['username'] = $this->getUserName($order['user_id']);
            $tmp['useremail'] = $this->getEmail($order['user_id']);
            $tmp['userphone'] = $this->getPhone($order['user_id']);
            $tmp['category_id'] = $order['category_id'];
            $tmp['category_name'] = $this->getCategoryName($order['category_id']);
            $tmp['service_id'] = $order['service_id'];
            $tmp['service_name'] = $this->getServiceName($order['service_id']);
            $tmp['order_id'] = $order['service_id'];
            $tmp['user_address'] = $order['service_id'];
            $tmp['created_at'] = $order['created_at']->format('d-M-Y h:i A');
            $tmp['schedule_date'] = $order['schedule_date']->format('d-M-Y');
            $tmp['schedule_time'] = $order['schedule_time'];
            $tmp['on_inspections'] = $order['on_inspections'];
            $tmp['is_minimum_charge'] = $order['is_minimum_charge'];
            $tmp['total_amount'] = number_format($order['total_amount'], 2);
            $tmp['status'] = $order['status'];
            $rslt[] = $tmp;
        }
        $this->set('orders', $rslt);
    }

}
