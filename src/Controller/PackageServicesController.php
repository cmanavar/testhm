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

class PackageServicesController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
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
        $this->loadModel('PackageOrders');
        $date = date('Y-m-d', strtotime('+ 7 day'));
        $orders = $this->PackageOrders->find('all')->where(["DATE_FORMAT(service_date,'%Y-%m-%d') <=" => $date])->hydrate(false)->toArray();
        $rslt = [];
        foreach ($orders as $order) {
            $tmp = [];
            $tmp['id'] = $order['id'];
            $tmp['user_id'] = $order['user_id'];
            $tmp['username'] = $this->getUserName($order['user_id']);
            $tmp['useremail'] = $this->getEmail($order['user_id']);
            $tmp['userphone'] = $this->getPhone($order['user_id']);
            $tmp['usertype'] = $this->getUserType($order['user_id']);
            $tmp['category_id'] = $category_id = $this->getCategoryId($order['service_id']);
            $tmp['category_name'] = $this->getCategoryName($category_id);
            $tmp['service_id'] = $order['service_id'];
            $tmp['service_name'] = $order['service_name'];
            $tmp['user_address'] = $this->getAddress($order['user_id']);
            $tmp['created_at'] = $order['created']->format('d-M-Y h:i A');
            $tmp['status'] = $order['service_status'];
            $tmp['vandor_name'] = $this->getUserName($order['vendors_id']);
            if (!empty($order['service_date'])) {
                $tmp['schedule_date'] = $order['service_date']->format('d-M-Y');
                $rslt[] = $tmp;
            }
        }

        $this->set('orders', $rslt);
    }

//    public function getServicesList($id) {
//        $this->loadModel('Services');
//        $servicesArr = $this->Services->find('list', ['keyField' => 'id', 'valueField' => 'service_name'])->where(['category_id' => $id])->toArray();
//        if (isset($servicesArr) && !empty($servicesArr)) {
//            $rslt = [];
//            foreach ($servicesArr as $key => $val) {
//                $tmp = [];
//                $tmp['key'] = $key;
//                $tmp['val'] = $val;
//                $rslt[] = $tmp;
//            }
//            $this->data = ['status' => 'success', 'msg' => 'Services Fetched!', 'data' => $rslt];
//        } else {
//            $this->data = ['status' => 'fail', 'msg' => 'Services not found!'];
//        }
//        echo json_encode($this->data);
//        exit;
//    }

    public function view($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('PackageOrders');
            $orders = $this->PackageOrders->find('all')->where(['id' => $id])->hydrate(false)->first();
            if (is_array($orders) && !empty($orders)) {
                $orderDetails = [];
                $orderDetails['id'] = $orders['id'];
                $orderDetails['order_id'] = $orders['id'];
                $orderDetails['username'] = $this->getUserName($orders['user_id']);
                $orderDetails['useremail'] = $this->getEmail($orders['user_id']);
                $orderDetails['userphone'] = $this->getPhone($orders['user_id']);
                $orderDetails['user_address'] = $this->getAddress($orders['user_id']);
                $orderDetails['schedule_date'] = $orders['service_date']->format('d-M-Y');
                $orderDetails['status'] = ucfirst(strtolower($orders['service_status']));
                $orderDetails['category_id'] = $category_id = $this->getCategoryId($orders['service_id']);
                $orderDetails['category_name'] = $this->getCategoryName($category_id);
                $orderDetails['service_id'] = $orders['service_id'];
                $orderDetails['service_name'] = $orders['service_name'];
                $orderDetails['vandor_name'] = $this->getUserName($orders['vendors_id']);
                $this->set('orders', $orderDetails);
            } else {
                $this->Flash->error('Unable to found order data!');
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error('Unable to found order data!');
            return $this->redirect(['action' => 'index']);
        }
    }

    public function edit($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('PackageOrders');
            $orders = $this->PackageOrders->find('all')->where(['id' => $id])->hydrate(false)->first();
            if (is_array($orders) && !empty($orders)) {
                $this->set('order', $this->PackageOrders->get($id));
                $orderDetails = [];
                $orderDetails['id'] = $orders['id'];
                $orderDetails['order_id'] = $orders['id'];
                $orderDetails['username'] = $this->getUserName($orders['user_id']);
                $orderDetails['useremail'] = $this->getEmail($orders['user_id']);
                $orderDetails['userphone'] = $this->getPhone($orders['user_id']);
                $orderDetails['user_address'] = $this->getAddress($orders['user_id']);
                $orderDetails['schedule_date'] = $orders['service_date']->format('d-M-Y');
                $orderDetails['status'] = ucfirst(strtolower($orders['service_status']));
                $orderDetails['service_name'] = $orders['service_name'];
                $orderDetails['vandor_name'] = $this->getUserName($orders['vendors_id']);
                $this->set('orders', $orderDetails);
                $vendors = $this->getVendorsofServices($orders['service_id']);
                $this->set('vendors', $vendors);
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $packageOrders = $this->PackageOrders->get($id);
                    $packageOrders = $this->PackageOrders->patchEntity($packageOrders, $this->request->data);
                    $packageOrders->modified = date("Y-m-d H:i:s");
                    $packageOrders->modified_by = $this->request->session()->read('Auth.User.id');
                    if ($this->PackageOrders->save($packageOrders)) {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                    }
                }
            } else {
                $this->Flash->error('Unable to found order data!');
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error('Unable to found order data!');
            return $this->redirect(['action' => 'index']);
        }
    }

    public function getQuestionDetails($question_id, $answer_id) {
        $this->loadModel('serviceQuestions');
        $this->loadModel('serviceQuestionAnswers');
        $rslt = [];
        $condArrQ = ['id' => $question_id];
        $service_questions = $this->serviceQuestions->find('all')->where($condArrQ)->hydrate(false)->first();
        //pr($service_questions); exit;
        if (isset($service_questions['question_title']) && $service_questions['question_title'] != '') {
            $rslt['questions'] = $service_questions['question_title'];
            $condArrA = ['id' => $answer_id];
            $service_answers = $this->serviceQuestionAnswers->find('all')->where($condArrA)->hydrate(false)->first();
            if (isset($service_answers) && !empty($service_answers)) {
                $answerData = $service_answers;
                //$rslt['question_id'] = (isset($answerData['question_id']) && $answerData['question_id'] != '') ? $answerData['question_id'] : '';
                //$rslt['answer_id'] = (isset($answerData['id']) && $answerData['id'] != '') ? $answerData['id'] : '';
                $rslt['parent_question'] = (isset($service_questions['parent_question_id']) && $service_questions['parent_question_id'] != '') ? $service_questions['parent_question_id'] : '';
                $rslt['parent_answer'] = (isset($service_questions['parent_answer_id']) && $service_questions['parent_answer_id'] != '') ? $service_questions['parent_answer_id'] : '';
                $rslt['answer'] = (isset($answerData['label']) && $answerData['label'] != '') ? $answerData['label'] : '';
                $rslt['quantity'] = (isset($answerData['quantity']) && $answerData['quantity'] != '') ? $answerData['quantity'] : '';
                $rslt['price'] = (isset($answerData['price']) && $answerData['price'] != '') ? $answerData['price'] : '';
            }
            return $rslt;
        } else {
            return $rslt;
        }
    }

    public function getVendorsofServices($serviceId) {
        if (isset($serviceId) && $serviceId != '') {
            $this->loadModel('VendorDetails');
            $vendors = [];
            $vendorsUsers = $this->VendorDetails->find('all')->select(['user_id', 'service_id'])->where(['service_id' => $serviceId])->hydrate(false)->toArray();
            if (!empty($vendorsUsers)) {
                foreach ($vendorsUsers as $key => $val) {
                    $vendors[$val['user_id']] = $this->getUserName($val['user_id']);
                }
            }
            return $vendors;
        } else {
            return [];
        }
    }

}
