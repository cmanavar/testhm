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
        $this->Auth->allow(['getServicesList']);
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
            $tmp['usertype'] = $this->getUserType($order['user_id']);
            $tmp['category_id'] = $order['category_id'];
            $tmp['category_name'] = $this->getCategoryName($order['category_id']);
            $tmp['service_id'] = $order['service_id'];
            $tmp['service_name'] = $this->getServiceName($order['service_id']);
            $tmp['order_id'] = $order['order_id'];
            $tmp['user_address'] = $order['service_id'];
            $tmp['created_at'] = $order['created_at']->format('d-M-Y h:i A');
            $tmp['schedule_date'] = $order['schedule_date']->format('d-M-Y');
            $tmp['schedule_time'] = $order['schedule_time'];
            $tmp['on_inspections'] = $order['on_inspections'];
            $tmp['is_minimum_charge'] = $order['is_minimum_charge'];
            $tmp['total_amount'] = number_format($order['total_amount'], 2);
            $tmp['status'] = $order['status'];
            $tmp['vandor_name'] = $this->getUserName($order['vendors_id']);
//            if (isset($order['status']) && !empty($order['status'])) {
//                if ($order['status'] == 'PLACED') {
//                    $tmp['vandor_name'] = $this->getUserName($order['vendors_id']);
//                }
//                if ($order['status'] == 'SCHEDULE') {
//                    $tmp['vandor_name'] = $this->getUserName($order['vendors_id']);
//                }
//                if ($order['status'] == 'ON_INSPECTION') {
//                    $tmp['vandor_name'] = $this->getUserName($order['vendors_id']);
//                }
//            }
            $rslt[] = $tmp;
        }
        $this->set('orders', $rslt);
    }

    public function add() {
        $this->loadModel('Orders');
        $this->loadModel('ServiceCategory');
        $order = $this->Orders->newEntity();
        // Categories List
        $serviceCategory = $this->ServiceCategory->find('list')->hydrate(false)->toArray();
        $this->set('serviceCategory', $serviceCategory);
        // All Status of Orders
        $this->set('orderStatus', $this->getAllOrderStatus());
        $this->set('order', $order);
    }

    public function getServicesNameUsingCartId($cartID) {
        if (isset($cartID) && $cartID != '') {
            $servicesName = [];
            $this->loadModel('CartOrders');
            $this->loadModel('Services');
            $cart_orders = $this->CartOrders->find('list', ['keyField' => 'id', 'valueField' => 'service_id'])->select(['service_id'])->where(['cart_id' => $cartID])->hydrate(false)->toArray();
            if (isset($cart_orders) && !empty($cart_orders)) {
                foreach ($cart_orders as $key => $val) {
                    $servicesDetails = $this->Services->find('all')->select(['service_name'])->where(['id' => $val])->hydrate(false)->first();
                    if (isset($servicesDetails) && !empty($servicesDetails)) {
                        $servicesName[] = $servicesDetails['service_name'];
                    }
                }
            }
            if (isset($servicesName) && !empty($servicesName)) {
                return implode(', ', $servicesName);
            } else {
                return '-';
            }
        } else {
            return '-';
        }
    }

    public function getServicesList($id) {
        $this->loadModel('Services');
        $servicesArr = $this->Services->find('list', ['keyField' => 'id', 'valueField' => 'service_name'])->where(['category_id' => $id])->toArray();
        if (isset($servicesArr) && !empty($servicesArr)) {
            $rslt = [];
            foreach ($servicesArr as $key => $val) {
                $tmp = [];
                $tmp['key'] = $key;
                $tmp['val'] = $val;
                $rslt[] = $tmp;
            }
            $this->data = ['status' => 'success', 'msg' => 'Services Fetched!', 'data' => $rslt];
        } else {
            $this->data = ['status' => 'fail', 'msg' => 'Services not found!'];
        }
        echo json_encode($this->data);
        exit;
    }

    public function view($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $this->loadModel('Coupons');
            $order_id = $id;
            $order = $this->Orders->find('all')->where(['id' => $order_id])->hydrate(false)->first();
//            pr($order); exit;
            if (!empty($order)) {
                //pr($orderExist); exit;
                $orderDetails = [];
                $orderDetails['id'] = $order['id'];
                $orderDetails['user_id'] = $order['user_id'];
                $orderDetails['order_id'] = $order['order_id'];
                $orderDetails['username'] = $this->getUserName($order['user_id']);
                $orderDetails['useremail'] = $this->getEmail($order['user_id']);
                $orderDetails['userphone'] = $this->getPhone($order['user_id']);
                $orderDetails['user_address'] = $order['user_address'];
                $orderDetails['created_at'] = $order['created_at']->format('d-M-Y h:i A');
                $orderDetails['schedule_date'] = $order['schedule_date']->format('d-M-Y');
                $orderDetails['schedule_time'] = $order['schedule_time'];
                $orderDetails['on_inspections'] = $order['on_inspections'];
                $orderDetails['is_minimum_charge'] = $order['is_minimum_charge'];
                $orderDetails['is_visiting_charge'] = $order['is_visiting_charge'];
                $orderDetails['is_coupon_applied'] = $order['is_coupon_applied'];
                $orderDetails['coupon_code'] = $order['coupon_code'];
                $orderDetails['discount'] = (is_string($order['discount'])) ? $order['discount'] : number_format($order['discount'], 2);
                $orderDetails['wallet_amount'] = number_format($order['wallet_amount'], 2);
                $orderDetails['amount'] = number_format($order['amount'], 2);
                $orderDetails['on_inspections_cost'] = number_format($order['on_inspections_cost'], 2);
                $orderDetails['tax'] = number_format($order['tax'], 2);
                $orderDetails['total_amount'] = number_format($order['total_amount'], 2);
                $orderDetails['status'] = $order['status'];
                if (isset($order['status']) && !empty($order['status'])) {
                    if ($order['status'] == 'SCHEDULE') {
                        $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                    }
                    if ($order['status'] == 'ON_INSPECTION') {
                        $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                    }
                }
                $orderDetails['payment_status'] = $order['payment_status'];
                $orderDetails['images'] = '';
                $orderDetails['services'] = [];
                $orderDetails['total']['amount'] = number_format($order['amount'], 2);
                $orderDetails['total']['tax'] = number_format($order['tax'], 2);
                $orderDetails['total']['discount'] = (is_string($order['discount'])) ? $order['discount'] : number_format($order['discount'], 2);
                $orderDetails['total']['wallet_amount'] = number_format($order['wallet_amount'], 2);
                if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') {
                    $sum = $order['amount'] + $order['tax'];
                    $orderDetails['total']['bill_amount'] = number_format($sum, 2);
                }
                $orderDetails['total']['total_amount'] = number_format($order['total_amount'], 2);
                $condArr = ['cart_id' => $order['cart_id']];
                $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
                $ordersItems = [];
                foreach ($cartOrders as $order) {
                    $tmp = [];
                    $tmp['cart_order_id'] = $order['id'];
                    $tmp['category_id'] = $order['category_id'];
                    $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                    $tmp['service_id'] = $order['service_id'];
                    $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                    $tmp['banner_img'] = $this->Services->getServiceImagePAth($order['service_id']);
                    $orderDetails['images'] = $this->Services->getServiceImagePAth($order['service_id']);
                    //$tmp['banner_img'] = $this->Services->getServiceName($order['service_id']);
                    $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $order['id']])->hydrate(false)->toArray();
                    foreach ($tmpDetails as $orderQues) {
                        $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
                        //pr($questArr); exit;
                        if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                            if ($questArr['parent_question'] != '' && $questArr['parent_answer'] != '') {
                                $answerTitle = $this->ServiceQuestionAnswers->find('all')->where(['id' => $questArr['parent_answer']])->hydrate(false)->first();
                                $tmp['serviceDescription'] = $answerTitle['label'];
                                $tmp['quantity'] = $orderQues['question_quantity'];
                                $tmp['total_amount'] = $order['total_amount'];
                            } else {
                                $tmp['serviceDescription'] = $questArr['answer'];
                                $tmp['quantity'] = $orderQues['question_quantity'];
                            }
                            if ($tmp['quantity'] == 0) {
                                $tmp['amount'] = 0;
                                $tmp['total_amount'] = $order['total_amount'];
                            } else {
                                $tmp['amount'] = $order['total_amount'] / $tmp['quantity'];
                                $tmp['total_amount'] = $order['total_amount'];
                            }
                            $tmp['on_inspection'] = 'N';
                        } else {
                            $tmp['serviceDescription'] = $questArr['answer'];
                            $tmp['quantity'] = $orderQues['question_quantity'];
                            $tmp['on_inspection'] = 'Y';
                            $tmp['amount'] = 0;
                            $tmp['total_amount'] = $order['total_amount'];
                        }
                    }

                    $ordersDetails[$order['category_id']]['category'] = $this->Services->getCategoryName($order['category_id']);
                    $ordersDetails[$order['category_id']]['services'][] = $tmp;
                }
                $finalOrderDetails = [];
                if (!empty($ordersDetails)) {
                    foreach ($ordersDetails as $key => $val) {
                        $finalOrderDetails[] = $val;
                    }
                }
                $orderDetails['services'] = $finalOrderDetails;
                //pr($orderDetails); exit;
                $this->set('orders', $orderDetails);
            } else {
                $this->Flash->error('Sorry, Order not found!');
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error('Unable to found order data!');
            return $this->redirect(['action' => 'index']);
        }
    }

    public function edit($id) {
        if (isset($id) && $id != '') {
            $order = [];
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $this->loadModel('Coupons');
            $order_id = $id;
            $order = $this->Orders->get($order_id);
            $this->set('order', $order);
            $this->set('orderStatus', $this->getAllOrderStatus());
            if (!empty($order)) {
                $orderDetails = [];
                $orderDetails['id'] = $order['id'];
                $orderDetails['user_id'] = $order['user_id'];
                $orderDetails['order_id'] = $order['order_id'];
                $orderDetails['username'] = $this->getUserName($order['user_id']);
                $orderDetails['useremail'] = $this->getEmail($order['user_id']);
                $orderDetails['userphone'] = $this->getPhone($order['user_id']);
                $orderDetails['user_address'] = $order['user_address'];
                $orderDetails['category_name'] = $this->getCategoryName($order['category_id']);
                $orderDetails['service_name'] = $this->getServiceName($order['service_id']);
                $orderDetails['created_at'] = $order['created_at']->format('d-M-Y h:i A');
                $orderDetails['schedule_date'] = $order['schedule_date']->format('d-M-Y');
                $orderDetails['schedule_time'] = $order['schedule_time'];
                $orderDetails['on_inspections'] = $order['on_inspections'];
                $orderDetails['is_minimum_charge'] = $order['is_minimum_charge'];
                $orderDetails['is_visiting_charge'] = $order['is_visiting_charge'];
                $orderDetails['is_coupon_applied'] = $order['is_coupon_applied'];
                $orderDetails['coupon_code'] = $order['coupon_code'];
                $orderDetails['discount'] = (is_string($order['discount'])) ? $order['discount'] : number_format($order['discount'], 2);
                $orderDetails['wallet_amount'] = number_format($order['wallet_amount'], 2);
                $orderDetails['amount'] = number_format($order['amount'], 2);
                $orderDetails['on_inspections_cost'] = number_format($order['on_inspections_cost'], 2);
                $orderDetails['tax'] = number_format($order['tax'], 2);
                $orderDetails['total_amount'] = number_format($order['total_amount'], 2);
                $orderDetails['status'] = $order['status'];
                $orderDetails['payment_status'] = $order['payment_status'];
                $orderDetails['images'] = '';
                $orderDetails['services'] = [];
                $orderDetails['total']['amount'] = number_format($order['amount'], 2);
                $orderDetails['total']['tax'] = number_format($order['tax'], 2);
                $orderDetails['total']['discount'] = (is_string($order['discount'])) ? $order['discount'] : number_format($order['discount'], 2);
                $orderDetails['total']['wallet_amount'] = number_format($order['wallet_amount'], 2);
                if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') {
                    $sum = $order['amount'] + $order['tax'];
                    $orderDetails['total']['bill_amount'] = number_format($sum, 2);
                }
                $orderDetails['total']['total_amount'] = number_format($order['total_amount'], 2);
                $condArr = ['cart_id' => $order['cart_id']];
                $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
                $ordersItems = [];
                $serviceArr = [];
                foreach ($cartOrders as $order) {
                    $tmp = [];
                    $tmp['cart_order_id'] = $order['id'];
                    $tmp['category_id'] = $order['category_id'];
                    $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                    $tmp['service_id'] = $serviceArr[] = $order['service_id'];
                    $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                    $tmp['banner_img'] = $this->Services->getServiceImagePAth($order['service_id']);
                    $orderDetails['images'] = $this->Services->getServiceImagePAth($order['service_id']);
                    $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $order['id']])->hydrate(false)->toArray();
                    $serviceDesc = '';
                    foreach ($tmpDetails as $orderQues) {
                        $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
                        if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                            if ($questArr['parent_question'] != '' && $questArr['parent_answer'] != '') {
                                $answerTitle = $this->ServiceQuestionAnswers->find('all')->where(['id' => $questArr['parent_answer']])->hydrate(false)->first();
                                $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                                $tmp['serviceDescription'] = trim($serviceDesc);
                                $tmp['quantity'] = $orderQues['question_quantity'];
                                $tmp['total_amount'] = $order['total_amount'];
                            } else {
                                $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                                $tmp['serviceDescription'] = trim($serviceDesc);
                                $tmp['quantity'] = $orderQues['question_quantity'];
                            }
                            if ($tmp['quantity'] == 0) {
                                $tmp['amount'] = 0;
                                $tmp['total_amount'] = $order['total_amount'];
                            } else {
                                $tmp['amount'] = $order['total_amount'] / $tmp['quantity'];
                                $tmp['total_amount'] = $order['total_amount'];
                            }
                            $tmp['on_inspection'] = 'N';
                        } else {
                            $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                            $tmp['serviceDescription'] = trim($serviceDesc);
                            $tmp['quantity'] = $orderQues['question_quantity'];
                            $tmp['on_inspection'] = 'Y';
                            $tmp['amount'] = 0;
                            $tmp['total_amount'] = $order['total_amount'];
                        }
                    }

                    $ordersDetails[$order['category_id']]['category'] = $this->Services->getCategoryName($order['category_id']);
                    $ordersDetails[$order['category_id']]['services'][] = $tmp;
                }
                $vendors = $this->getVendorsofServices($serviceArr);
                $this->set('vendors', $vendors);
                $finalOrderDetails = [];
                if (!empty($ordersDetails)) {
                    foreach ($ordersDetails as $key => $val) {
                        $finalOrderDetails[] = $val;
                    }
                }
                $orderDetails['services'] = $finalOrderDetails;
                $this->set('orderDetails', $orderDetails);
                if ($this->request->is(['patch', 'post', 'put'])) {
                    //echo $this->request->data['status']; exit;
                    if ($this->request->data['status'] == 'COMPLETED') {
                        $orderDetails['new_status'] = 'COMPLETED';
                        $this->sendOrderInvoiceEmails($orderDetails);
                    }
                    $orders = $this->Orders->get($order_id);
                    $orders = $this->Orders->patchEntity($orders, $this->request->data);
                    $orders->modified = date("Y-m-d H:i:s");
                    $orders->modified_by = $this->request->session()->read('Auth.User.id');
                    if ($this->Orders->save($orders)) {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                    }
                }
            } else {
                $this->Flash->error('Sorry, Order not found!');
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error('UNABLE TO FOUND ORDER DATA');
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

    public function getVendorsofServices($serviceArr) {
        if (isset($serviceArr) && !empty($serviceArr)) {
            $this->loadModel('VendorDetails');
            $serviceArr = array_unique($serviceArr);
            $vendors = [];
            foreach ($serviceArr as $key => $val) {
                $vendorsUsers = $this->VendorDetails->find('all')->select(['user_id', 'service_id'])->where(['service_id' => $val])->hydrate(false)->toArray();
                if (!empty($vendorsUsers)) {
                    foreach ($vendorsUsers as $key => $val) {
                        $vendors[$val['service_id']][$val['user_id']] = $this->getUserName($val['user_id']);
                    }
                }
            }
            return $vendors;
        } else {
            return [];
        }
    }

}
