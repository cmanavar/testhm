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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class GreenCashbacksController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN'])) {
            AppController::checkNormalAccess();
        }
        //$this->Auth->allow(['delete']);
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function index() {
        $this->loadModel('Users');
        $this->loadModel('GreenCashbacks');
        $filters = $this->GreenCashbacks->newEntity();
        $this->set('filters', $filters);
        if ($this->request->is('post')) {
            if (isset($this->request->data['from_date']) && $this->request->data['from_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
            }
            if (isset($this->request->data['to_date']) && $this->request->data['to_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
            }
            $filter = $this->request->data;
            $this->set('filter', $filter);
        }
        $rslt = [];
        $members = $this->Users->find('all')->select(['id', 'name'])->where(['user_type' => 'MEMBERSHIP'])->order(['id' => 'DESC'])->hydrate(false)->toArray();
        if (!empty($members)) {
            foreach ($members as $key => $val) {
                $tmp = [];
                $condArr['user_id'] = $val['id'];
                $tmp['id'] = $val['id'];
                $tmp['name'] = $val['name'];
                $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArr)->hydrate(false)->first();
                $totGreenCash = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                $tmp['totalCash'] = $totGreenCash;
                $rslt[] = $tmp;
            }
        }
        $this->set('greencash', $rslt);
    }

    public function view($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Users');
            $this->loadModel('GreenCashbacks');
            $this->loadModel('GreenCashpayments');
            $rslt = [];
            $users = $this->Users->find('all')->select(['id', 'name'])->where(['id' => $id])->hydrate(false)->first();
            if (isset($users) && !empty($users)) {
                $rslt['id'] = $users['id'];
                $rslt['name'] = $users['name'];
                $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where(['user_id' => $id])->hydrate(false)->first();
                $rslt['totalCash'] = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                $paidgreenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where(['user_id' => $id, 'status' => 'CLEAR'])->hydrate(false)->first();
                $rslt['totalpaidCash'] = (isset($paidgreenCash['tot']) && $paidgreenCash['tot'] != '') ? $paidgreenCash['tot'] : 0;
                $greenCashHistory = $this->GreenCashbacks->find('all')->select(['id', 'user_id', 'amount', 'refer_membership_id', 'status', 'created'])->where(['user_id' => $id])->hydrate(false)->toArray();
                foreach ($greenCashHistory as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['user_id'] = $val['user_id'];
                    $tmp['refer_membership_name'] = $this->getUserName($val['refer_membership_id']);
                    $plan_id = $this->getPlanId($val['refer_membership_id']);
                    $tmp['plan_name'] = $this->getPlanNames($plan_id);
                    $tmp['amount'] = $val['amount'];
                    $tmp['status'] = $val['status'];
                    $tmp['date'] = $val['created']->format('d-m-Y');
                    $rslt['cashHistory'][] = $tmp;
                }
                $paymentHistory = $this->GreenCashpayments->find('all')->where(['user_id' => $id])->hydrate(false)->toArray();
                $totPayments = $this->GreenCashpayments->find('all')->select(['tot' => 'SUM(GreenCashpayments.payment_amount)'])->where(['user_id' => $id])->hydrate(false)->first();
                $rslt['paymentHistory'] = $paymentHistory;
                $rslt['totPayments'] = $totPayments;
                //pr($rslt); exit;
                $this->set('greencash', $rslt);
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function edit($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Users');
            $this->loadModel('GreenCashbacks');
            $this->loadModel('GreenCashpayments');
            $rslt = [];
            $filter = $this->GreenCashbacks->newEntity();
            $condArr = [];
            if ($this->request->is('post')) {
                $months = $years = 0;
                if (isset($this->request->data['months']) && $this->request->data['months'] != '') {
                    $months = $this->request->data['months'];
                }
                if (isset($this->request->data['years']) && $this->request->data['years'] != '') {
                    $years = $this->request->data['years'];
                }
                if ($months != 0 && $years != 0) {
                    $firstdate = "01-" . $months . "-" . $years;
                    $monthStartDate = date('Y-m-d', strtotime($firstdate));
                    $monthEndDate = date('Y-m-t', strtotime($firstdate));
                    $filter['months'] = $months;
                    $filter['years'] = $years;
                } else {
                    $currentMonth = date('m');
                    $currentYear = date('Y');
                    if ($currentMonth == 1) {
                        $lastMonth = 12;
                        $currentYear = $currentYear - 1;
                    } else {
                        $lastMonth = $currentMonth - 1;
                    }
                    $firstdate = "01-" . $lastMonth . "-" . $currentYear;
                    $monthStartDate = date('Y-m-d', strtotime($firstdate));
                    $monthEndDate = date('Y-m-t', strtotime($firstdate));
                    $filter['months'] = $currentMonth;
                    $filter['years'] = $currentYear;
                }
                //$filter = $this->request->data;
                //pr($filter);
                $this->set('filter', $filter);
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($monthStartDate));
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($monthEndDate));
                $this->request->session()->write('greencashFilter', $condArr); // WRITE SESSION FOR SEARCHED DATA
            } else {
                $currentMonth = date('m');
                $currentYear = date('Y');
                if ($currentMonth == 1) {
                    $lastMonth = 12;
                    $currentYear = $currentYear - 1;
                } else {
                    $lastMonth = $currentMonth - 1;
                }
                $firstdate = "01-" . $lastMonth . "-" . $currentYear;
                $monthStartDate = date('Y-m-d', strtotime($firstdate));
                $monthEndDate = date('Y-m-t', strtotime($firstdate));
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($monthStartDate));
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($monthEndDate));
                $this->request->session()->write('greencashFilter', $condArr); // WRITE SESSION FOR SEARCHED DATA
                $filter['months'] = $lastMonth;
                $filter['years'] = $currentYear;
                $this->set('filter', $filter);
            }
            $condArr['user_id'] = $id;
            //pr($condArr); exit;
            $payment_id = 0;
            $users = $this->Users->find('all')->select(['id', 'name'])->where(['id' => $id])->hydrate(false)->first();
            if (isset($users) && !empty($users)) {
                $rslt['id'] = $users['id'];
                $rslt['name'] = $users['name'];
                $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArr)->hydrate(false)->first();
                $rslt['totalCash'] = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                $condArrC = $condArr;
                $condArrC['status'] = 'CLEAR';
                $paidgreenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArrC)->hydrate(false)->first();
                $rslt['totalpaidCash'] = (isset($paidgreenCash['tot']) && $paidgreenCash['tot'] != '') ? $paidgreenCash['tot'] : 0;
                $greenCashHistory = $this->GreenCashbacks->find('all')->select(['id', 'user_id', 'amount', 'refer_membership_id', 'status', 'payment_id', 'created'])->where($condArr)->hydrate(false)->toArray();
                //  pr($greenCashHistory); exit;
                foreach ($greenCashHistory as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['user_id'] = $val['user_id'];
                    $tmp['refer_membership_name'] = $this->getUserName($val['refer_membership_id']);
                    $plan_id = $this->getPlanId($val['refer_membership_id']);
                    $tmp['plan_name'] = $this->getPlanNames($plan_id);
                    $tmp['amount'] = $val['amount'];
                    $tmp['status'] = $val['status'];
                    $tmp['date'] = $val['created']->format('d-m-Y');
                    $payment_id = $val['payment_id'];
                    $rslt['cashHistory'][] = $tmp;
                }
                $rslt['paymnetHistory'] = [];
                if (isset($payment_id) && $payment_id != 0) {
                    //echo $payment_id; exit;
                    $paymentHistory = $this->GreenCashpayments->find('all')->where(['id' => $payment_id])->hydrate(false)->first();
                    $rslt['paymnetHistory'] = $paymentHistory;
                }
//                pr($rslt);
//                exit;
                $this->set('greencash', $rslt);
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function paymentClear($id) {
        $this->data = [];
        if (isset($id) && $id != '') {
            $this->layout = 'ajax';
            $this->loadModel('GreenCashbacks');
            $cash = $this->GreenCashbacks->get($id);
            //pr($user); exit;
            if (!empty($cash)) {
                $updatedArr = [];
                $updatedArr['status'] = 'CLEAR';
                $cash = $this->GreenCashbacks->patchEntity($cash, $updatedArr);
                $cash->modified = date('Y-m-d H:i:s', strtotime($_POST['modified']));
                $cash->modified_by = $_POST['modified_by'];
                if ($this->GreenCashbacks->save($cash)) {
                    $this->data['status'] = 'success';
                    $this->data['msg'] = 'Cash Paid!';
                } else {
                    $this->data['status'] = 'fail';
                    $this->data['msg'] = 'Cash Paid Failed!';
                }
            } else {
                $this->data['status'] = 'fail';
                $this->data['msg'] = 'Sorry, Cash data is missing!';
            }
        } else {
            $this->data['status'] = 'fail';
            $this->data['msg'] = 'Sorry, Cashid is missing!';
        }
        echo json_encode($this->data);
        exit;
    }

    public function payment($id) {
        if (isset($id) && $id != '') {
            $searchdatagreencash = $this->request->session()->read('greencashFilter');
            if (isset($searchdatagreencash) && !empty($searchdatagreencash)) {
                $this->loadModel('Users');
                $this->loadModel('GreenCashbacks');
                $this->loadModel('GreenCashpayments');
                $rslt = [];
                $condArr = $searchdatagreencash;
                $condArr['user_id'] = $id;
                $users = $this->Users->find('all')->select(['id', 'name'])->where(['id' => $id])->hydrate(false)->first();
                if (isset($users) && !empty($users)) {
                    $rslt['id'] = $users['id'];
                    $rslt['name'] = $users['name'];
                    $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArr)->hydrate(false)->first();
                    $rslt['totalCash'] = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                    $condArrC = $condArr;
                    $condArrC['status'] = 'CLEAR';
                    $paidgreenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArrC)->hydrate(false)->first();
                    $rslt['totalpaidCash'] = (isset($paidgreenCash['tot']) && $paidgreenCash['tot'] != '') ? $paidgreenCash['tot'] : 0;
                    $greenCashHistory = $this->GreenCashbacks->find('all')->select(['id', 'user_id', 'amount', 'refer_membership_id', 'status', 'created'])->where($condArr)->hydrate(false)->toArray();
                    //  pr($greenCashHistory); exit;
                    foreach ($greenCashHistory as $key => $val) {
                        $tmp = [];
                        $tmp['id'] = $val['id'];
                        $tmp['user_id'] = $val['user_id'];
                        $tmp['refer_membership_name'] = $this->getUserName($val['refer_membership_id']);
                        $plan_id = $this->getPlanId($val['refer_membership_id']);
                        $tmp['plan_name'] = $this->getPlanNames($plan_id);
                        $tmp['amount'] = $val['amount'];
                        $tmp['status'] = $val['status'];
                        $tmp['date'] = $val['created']->format('d-m-Y');
                        $rslt['cashHistory'][] = $tmp;
                    }
                    //pr($rslt); exit;
                    $this->set('greencash', $rslt);
                    $greencashpayment = $this->GreenCashpayments->newEntity();
                    if ($this->request->is('post')) {
                        $cheque_dates = $this->request->data['cheque_date'];
                        unset($this->request->data['cheque_date']);
                        $greencashpayment = $this->GreenCashpayments->patchEntity($greencashpayment, $this->request->data);
                        $greencashpayment->user_id = $id;
                        $greencashpayment->cheque_date = date('Y-m-d', strtotime($cheque_dates));
                        $greencashpayment->created = date('Y-m-d');
                        $greencashpayment->created_by = $this->request->session()->read('Auth.User.id');
                        $payment_id = $this->GreenCashpayments->save($greencashpayment);
                        if ($payment_id) {
                            $updateRecords = ['status' => 'CLEAR', 'payment_id' => $payment_id];
                            $updateQueries = $this->GreenCashbacks->updateAll($updateRecords, $condArr);
                            if ($updateQueries) {
                                $this->Flash->success(Configure::read('Settings.SAVE'));
                                return $this->redirect(['action' => 'index']);
                            } else {
                                $this->Flash->error(Configure::read('Settings.FAIL'));
                                return $this->redirect(['action' => 'payment', $id]);
                            }
                        } else {
                            $this->Flash->error(Configure::read('Settings.FAIL'));
                            return $this->redirect(['action' => 'payment', $id]);
                        }
                    }
                    $this->set('greencashpayment', $greencashpayment);
                } else {
                    $this->Flash->error(__('RECORD DOES NOT EXIST'));
                    return $this->redirect(['action' => 'index']);
                }
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function resetfilters($id) {
        $this->request->session()->delete('greencashFilter');
        return $this->redirect(array('action' => 'edit', $id));
    }

}
