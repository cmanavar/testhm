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

class PaymentsController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['orderCreate', 'storeResponse']);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function orderCreate() {
//        echo 1; exit;
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Orders');
            $this->loadModel('PaymentDetails');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $order = $this->Orders->find('all')->where(['order_id' => $order_id, 'user_id' => $userId])->hydrate(false)->first();
            if (!empty($order)) {
                if (isset($order['total_amount']) && $order['total_amount'] != 0) {
                    $orderPaymentPendingDetails = $this->PaymentDetails->find('all')->where(['order_id' => $order_id, 'user_id' => $userId, 'payment_status' => 'PENDING'])->hydrate(false)->first();
                    if (isset($orderPaymentPendingDetails) && !empty($orderPaymentPendingDetails)) {
                        $data = [];
                        $data['order_id'] = $orderPaymentPendingDetails['payment_order_id'];
                        $data['amount'] = $orderPaymentPendingDetails['payment_amount'];
                        $data['receipt'] = $orderPaymentPendingDetails['order_id'];
                        $tmpCreated = $orderPaymentPendingDetails['created_at']->format('Y-m-d H:i:s');
                        $data['created_at'] = strtotime($tmpCreated);
                        $this->success('Order Created!', $data);
                    } else {
                        $orderData = [
                            'receipt' => $order['order_id'],
                            'amount' => $order['total_amount'] * 100, // 2000 rupees in paise
                            'currency' => 'INR',
                            'payment_capture' => 1 // auto capture
                        ];
                        //echo "https://".RAZORPAY_KEY_ID.":".RAZORPAY_KEY_SECRET."@api.razorpay.com/v1/orders"; exit;
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, "https://" . RAZORPAY_KEY_ID . ":" . RAZORPAY_KEY_SECRET . "@api.razorpay.com/v1/orders");
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8'));
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
                        curl_setopt($ch, CURLOPT_HEADER, FALSE);
                        curl_setopt($ch, CURLOPT_POST, TRUE);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                        $response = curl_exec($ch);
                        $err = curl_error($ch);
                        curl_close($ch);
                        if ($err) {
                            $this->wrong("cURL Error #:" . $err);
                        } else {
                            if (isset($response) && !empty($response)) {
                                $orders = json_decode($response, true);
                                $paymentDetails = $this->PaymentDetails->newEntity();
                                $order_data = array(
                                    'user_id' => $userId,
                                    'order_id' => $order_id,
                                    'payment_method' => 'RAZORPAY',
                                    'payment_order_id' => $orders['id'],
                                    'payment_amount' => $orders['amount'],
                                    'payment_id' => '',
                                    'payment_status' => 'PENDING',
                                    'user_email' => '',
                                    'user_phone' => '',
                                    'razorpay_response_code' => '',
                                    'notes' => ''
                                );
                                $paymentDetails = $this->PaymentDetails->patchEntity($paymentDetails, $order_data);
                                $paymentDetails->created_at = date('Y-m-d H:i:s');
                                $paymentDetails->modified_at = '';
                                if ($this->PaymentDetails->save($paymentDetails)) {
                                    $data = [];
                                    $data['order_id'] = $orders['id'];
                                    $data['amount'] = $orders['amount'];
                                    $data['receipt'] = $orders['receipt'];
                                    $data['created_at'] = $orders['created_at'];
                                    $this->success('Order Created!', $data);
                                } else {
                                    $this->wrong(__('UNABLE TO GET PAYMENT DATA.'));
                                }
                            }
                        }
                    }
                }
            } else {
                $this->wrong('Sorry, Order not found!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function storeResponse() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Orders');
            $this->loadModel('PaymentDetails');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'razorpay_order_id' => (isset($requestArr['razorpay_order_id']) && $requestArr['razorpay_order_id'] != '') ? $requestArr['razorpay_order_id'] : '',
                'status' => (isset($requestArr['status']) && $requestArr['status'] != '') ? $requestArr['status'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $razorpay_order_id = $requestArr['razorpay_order_id'];
            $status = $requestArr['status'];
            $razorpay_payment_id = (isset($requestArr['razorpay_payment_id']) && $requestArr['razorpay_payment_id'] != '') ? $requestArr['razorpay_payment_id'] : '';
            $razorpay_signature_id = (isset($requestArr['razorpay_signature']) && $requestArr['razorpay_signature'] != '') ? $requestArr['razorpay_signature'] : '';
            $contact = (isset($requestArr['contact']) && $requestArr['contact'] != '') ? $requestArr['contact'] : '';
            $email = (isset($requestArr['email']) && $requestArr['email'] != '') ? $requestArr['email'] : '';
            $code = (isset($requestArr['code']) && $requestArr['code'] != '') ? $requestArr['code'] : '';
            $orderExist = $this->PaymentDetails->find('all')->where(['payment_order_id' => $razorpay_order_id, 'user_id' => $userId])->hydrate(false)->first();
            //pr($orderExist); exit;
            if (isset($orderExist) && !empty($orderExist)) {
                if (isset($status) && $status != '') {
                    $payment_data = [];
                    if ($status == 'CANCELLED') {
                        $payment_data = array(
                            'payment_status' => $status,
                            'user_email' => $email,
                            'user_phone' => $contact,
                            'razorpay_response_code' => $code,
                            'notes' => 'Your payment was cancelled.',
                        );
                    }
                    if ($status == 'SUCCESS') {
                        $requiredFields = array(
                            'razor_payment_id' => $razorpay_payment_id,
                            'razorpay_signature_id' => $razorpay_signature_id
                        );
                        $validate = $this->checkRequiredFields($requiredFields);
                        if ($validate != "") {
                            $this->wrong($validate);
                        }
                        $payload = $razorpay_order_id . "|" . $razorpay_payment_id;
                        $expectedSignature = hash_hmac('sha256', $payload, RAZORPAY_KEY_SECRET);
                        if (function_exists('hash_equals')) {
                            $verified = hash_equals($expectedSignature, $razorpay_signature_id);
                        } else {
                            $verified = $this->hashEquals($expectedSignature, $razorpay_signature_id);
                        }
                        if ($verified === false) {
                            $error = 'Invalid signature passed';
                            $payment_data = array(
                                'payment_status' => 'FAILED',
                                'user_email' => $email,
                                'user_phone' => $contact,
                                'payment_id' => $razorpay_payment_id,
                                'razorpay_signature' => $razorpay_signature_id,
                                'notes' => $error
                            );
                        } else {
                            $payment_data = array(
                                'payment_status' => $status,
                                'user_email' => $email,
                                'user_phone' => $contact,
                                'payment_id' => $razorpay_payment_id,
                                'razorpay_signature' => $razorpay_signature_id,
                                'notes' => 'Your payment was successfully done.'
                            );
                        }
                    }
                    if (isset($payment_data) && !empty($payment_data)) {
                        //echo $orderExist['id']; exit;
                        $paymentDetailData = $this->PaymentDetails->get($orderExist['id']);
                        $paymentDetailData = $this->PaymentDetails->patchEntity($paymentDetailData, $payment_data);
                        $paymentDetailData->modified_at = date('Y-m-d H:i:s');
                        if ($this->PaymentDetails->save($paymentDetailData)) {
                            $this->data = ['status' => strtolower($payment_data['payment_status']), 'msg' => $payment_data['notes']];
                            if ($payment_data['payment_status'] == 'SUCCESS') {
                                $this->success($payment_data['notes']);
                            } else {
                                $this->wrong($payment_data['notes']);
                            }
                        } else {
                            $this->wrong(__('UNABLE TO GET PAYMENT.'));
                        }
                    } else {
                        $this->wrong(__('UNABLE TO GET PAYMENT.'));
                    }
                }
            } else {
                $this->wrong("Sorry Order Details not found!");
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

}
