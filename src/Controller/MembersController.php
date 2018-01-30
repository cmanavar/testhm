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
use App\Model\Validation\UsersValidator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : ServicecategoryController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class MembersController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['delete', 'useractive', 'login']);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function index() {
        $this->loadModel('Users');
        $members = [];
        $members = $this->Members->getMembers();
        $this->set('members', $members);
    }

    //***********************************************************************************************//
    // * Function     :  add
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function add() {
        $this->loadModel('Plans');
        $this->loadModel('Users');
        $this->loadModel('UserDetails');
        $this->loadModel('Wallets');
        $planLists = $this->Plans->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->hydrate(false)->toArray();
        $this->set('planLists', $planLists);
        $referUsers = $this->Users->find('all')->select(['id', 'name', 'phone_no'])->where(['user_type' => 'MEMBERSHIP'])->hydrate(false)->toArray();
        $referLists = [];
        if (!empty($referUsers)) {
            foreach ($referUsers as $tuser) {
                $referLists[$tuser['id']] = $tuser['name'] . " | " . $tuser['phone_no'];
            }
        }
        $this->set('referLists', $referLists);
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if (!isset($this->request->data['plan_id']) || $this->request->data['plan_id'] == '') {
                $this->Flash->error('Sorry, Please select plan');
            }
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $this->request->data['password'] = $usersController->randomPassword();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $memberIdArrs = $this->getNewMemberIds();
                if (!empty($memberIdArrs)) {
                    $name = $this->request->data['name'];
                    $email = $this->request->data['email'];
                    $phone_no = $this->request->data['phone_no'];
                    $userExists = $this->Users->uniqueEmailOrPhone($email, $phone_no);
                    if (isset($userExists['status']) && $userExists['status'] == 'fail') {
                        $this->Flash->error($userExists['msg']);
                    } else {
                        $uController = new UsersController();
                        $password = $uController->randomPassword();
                        $userData = [];
                        $userData['name'] = $name;
                        $userData['email'] = $email;
                        $userData['phone_no'] = $phone_no;
                        $userData['password'] = $password;
                        $userData['address'] = (isset($this->request->data['address']) && $this->request->data['address'] != '') ? $this->request->data['address'] : '';
                        $userData['city'] = (isset($this->request->data['city']) && $this->request->data['city'] != '') ? $this->request->data['city'] : '';
                        $userData['signup_with'] = 'SELF';
                        $userData['user_type'] = 'MEMBERSHIP';
                        $userData['plan_id'] = $plan_id = (isset($this->request->data['plan_id']) && $this->request->data['plan_id'] != '') ? $this->request->data['plan_id'] : '';
                        $userData['ip_address'] = $this->get_client_ip();
                        $userData['refer_key'] = $this->getReferKey($name, $phone_no);
                        $userData['referral_id'] = (isset($this->request->data['refer_id']) && $this->request->data['refer_id'] != '') ? $this->request->data['refer_id'] : 0;
                        $userData['email_newsletters'] = 'Y';
                        $userData['phone_verified'] = 'Y';
                        $userData['email_verified'] = 'Y';
                        $userData['active'] = (isset($this->request->data['active']) && $this->request->data['active'] != '') ? $this->request->data['active'] : 'N';
                        $user = $this->Users->patchEntity($user, $userData);
                        $user->ids = $memberIdArrs['ids'];
                        $user->membership_id = $memberIdArrs['membership_id'];
                        $paymentType = (isset($this->request->data['payment_type']) && $this->request->data['payment_type'] != '') ? $this->request->data['payment_type'] : '';
                        $bankName = (isset($this->request->data['bank_name']) && $this->request->data['bank_name'] != '') ? $this->request->data['bank_name'] : '';
                        $chequeNo = (isset($this->request->data['cheque_no']) && $this->request->data['cheque_no'] != '') ? $this->request->data['cheque_no'] : '';
                        $transcationId = (isset($this->request->data['transcation_id']) && $this->request->data['transcation_id'] != '') ? $this->request->data['transcation_id'] : '';
                        $otherDetails = (isset($this->request->data['other_details']) && $this->request->data['other_details'] != '') ? $this->request->data['other_details'] : '';
                        if ($paymentType == 'CHEQUE') {
                            $msgT = '';
                            $sendMsg = [];
                            $msgT = "Congratulation, your payment with the Cheques No. " . $chequeNo . " has been received and in process. We will update you within 3-4 working days once it is clear. Membership ID: " . $memberIdArrs['membership_id'] . ". Hmen Services.";
                            $sendMsg = $this->sendOtp($phone_no, $msgT);
                            if ($sendMsg['status'] == 'fail') {
                                $this->wrong($sendMsg['msg']);
                            }
                        }
                        if ($paymentType == 'UPI') {
                            $msgT = '';
                            $sendMsg = [];
                            $msgT = "Congratulation, your payment with the UPI Transaction ID. " . $chequeNo . " has been received and in process. It will update in next 24 hours. Membership ID: " . $memberIdArrs['membership_id'] . ". Hmen Services.";
                            $sendMsg = $this->sendOtp($phone_no, $msgT);
                            if ($sendMsg['status'] == 'fail') {
                                $this->wrong($sendMsg['msg']);
                            }
                        }
                        // SEND SMS
                        $msgT = "Dear $name, Your Hmen Account access Email address: $email and  Password: $password You can login after your payment clearance. Regards, Hmen Service.";
                        $sendMsg = $this->sendOtp($phone_no, $msgT);
                        if ($sendMsg['status'] == 'fail') {
                            $this->Flash->error($sendMsg['msg']);
                        }
                        // SEND EMAIL
                        $this->sentEmails($name, $email, $password);
                        if (isset($this->request->data['birthdate']) && $this->request->data['birthdate'] != '') {
                            $user->birthdate = date('Y-m-d', strtotime($this->request->data['birthdate']));
                        }
                        if (isset($this->request->data['aniversary_date']) && $this->request->data['aniversary_date'] != '') {
                            $user->aniversary_date = date('Y-m-d', strtotime($this->request->data['aniversary_date']));
                        }
                        $user->created = date("Y-m-d H:i:s");
                        $user->created_by = $this->request->session()->read('Auth.User.id');
                        $saveUsers = $this->Users->save($user);
                        if ($saveUsers) {
                            $userId = $saveUsers['id'];
                            //generate api key
                            $api_key = $this->Users->generateAPIkey();
                            $mappingData = [];
                            $this->loadModel('UserMapping');
                            $userMapping = $this->UserMapping->newEntity();
                            $map_data = array(
                                'user_id' => $userId,
                                'user_type' => 'MEMBERSHIP',
                                'mapping_key' => 'api_key',
                                'mapping_value' => $api_key
                            );
                            $userMapping = $this->UserMapping->patchEntity($userMapping, $map_data);
                            $userMapping->created = date("Y-m-d H:i:s");
                            if ($this->UserMapping->save($userMapping)) {
                                $users = $this->UserDetails->newEntity();
                                $userData = [];
                                $userData['user_id'] = $userId;
                                $userData['person_1'] = (isset($this->request->data['person_1']) && $this->request->data['person_1'] != '') ? $this->request->data['person_1'] : '';
                                $userData['person_2'] = (isset($this->request->data['person_2']) && $this->request->data['person_2'] != '') ? $this->request->data['person_2'] : '';
                                $userData['person_3'] = (isset($this->request->data['person_3']) && $this->request->data['person_3'] != '') ? $this->request->data['person_3'] : '';
                                $userData['person_4'] = (isset($this->request->data['person_4']) && $this->request->data['person_4'] != '') ? $this->request->data['person_4'] : '';
                                $userData['person_5'] = (isset($this->request->data['person_5']) && $this->request->data['person_5'] != '') ? $this->request->data['person_5'] : '';
                                $userData['occupation'] = (isset($this->request->data['occupation']) && $this->request->data['occupation'] != '') ? $this->request->data['occupation'] : '';
                                $userData['company_name'] = (isset($this->request->data['company_name']) && $this->request->data['company_name'] != '') ? $this->request->data['company_name'] : '';
                                $userData['company_website'] = (isset($this->request->data['company_website']) && $this->request->data['company_website'] != '') ? $this->request->data['company_website'] : '';
                                $userData['payment_type'] = $paymentType;
                                $userData['bank_name'] = $bankName;
                                $userData['cheque_no'] = $chequeNo;
                                $userData['transcation_id'] = $transcationId;
                                $userData['other_details'] = $otherDetails;
                                $users = $this->UserDetails->patchEntity($users, $userData);
                                $users->birthdate_1 = (isset($this->request->data['birthdate_1']) && $this->request->data['birthdate_1'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_1'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_2 = (isset($this->request->data['birthdate_2']) && $this->request->data['birthdate_2'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_2'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_3 = (isset($this->request->data['birthdate_3']) && $this->request->data['birthdate_3'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_3'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_4 = (isset($this->request->data['birthdate_4']) && $this->request->data['birthdate_4'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_4'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_5 = (isset($this->request->data['birthdate_5']) && $this->request->data['birthdate_5'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_5'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->cheque_date = (isset($this->request->data['cheque_date']) && $this->request->data['cheque_date'] != '') ? date('Y-m-d', strtotime($this->request->data['cheque_date'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->created = date("Y-m-d H:i:s");
                                $users->created_by = $this->request->session()->read('Auth.User.id');
                                $planDetails = $this->Plans->find('all')->where(['id' => $plan_id])->hydrate(false)->first();
                                if ($planDetails['cashback'] != 0.00) {
                                    $vW = [];
                                    $vW['amount'] = $planDetails['cashback'];
                                    $vW['wallet_type'] = 'CREDIT';
                                    $vW['purpose'] = 'MEMBERSHIP_CASHBACK';
                                    $vW['purpose_id'] = 0;
                                    $walletId = $this->addWalletAmount($userId, $vW['amount'], $vW['wallet_type'], $vW['purpose'], $vW['purpose_id']);
                                    if ($walletId) {
                                        $this->newMsg($userId, MSG_TITLE_REFERRAL, MSG_TYPE_CASHBACK, 'Rs. ' . $planDetails['cashback'] . ' Cashback for Membership');
                                    }
                                }
                                if ($this->UserDetails->save($users)) {
                                    $this->Flash->success(__('THE MEMBER HAS BEEN SAVED.'));
                                    return $this->redirect(['action' => 'index']);
                                } else {
                                    $this->Flash->error(__('UNABLE TO ADD THE MEMBER.'));
                                }
                            } else {
                                $this->Flash->error(__('UNABLE TO ADD THE MEMBER.'));
                            }
                        }
                    }
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $this->set('user', $user);
    }

    //***********************************************************************************************//
    // * Function     :  edit
    // * Parameter    :  
    // * Description  :  This function used to edit Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function edit($id) {
        $this->loadModel('Plans');
        $this->loadModel('Users');
        $this->loadModel('UserDetails');
        $planLists = $this->Plans->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->hydrate(false)->toArray();
        $this->set('planLists', $planLists);
        //CHECK IF THE RECORD EXISTS OR NOT 
        $referUsers = $this->Users->find('all')->select(['id', 'name', 'phone_no'])->where(['user_type' => 'MEMBERSHIP', 'id !=' => $id])->hydrate(false)->toArray();
        $referLists = [];
        if (!empty($referUsers)) {
            foreach ($referUsers as $tuser) {
                $referLists[$tuser['id']] = $tuser['name'] . " | " . $tuser['phone_no'];
            }
        }
        $this->set('referLists', $referLists);
        $query = $this->Members->memberExists($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $user = $this->Members->geMemberId($id); //LISTING CATEGORYDATA
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $this->request->data['password'] = $usersController->randomPassword();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $user = $this->Users->getuserId($id); //LISTING USERDATA
                $birthdate = $this->request->data['birthdate'];
                $aniversarydate = $this->request->data['aniversary_date'];
                unset($this->request->data['birthdate']);
                unset($this->request->data['aniversary_date']);
                $user = $this->Users->patchEntity($user, $this->request->data());
                if (isset($birthdate) && $birthdate != '') {
                    $user->birthdate = date('Y-m-d', strtotime($birthdate));
                }
                if (isset($aniversarydate) && $aniversarydate != '') {
                    $user->aniversary_date = date('Y-m-d', strtotime($aniversarydate));
                }
                $user->modified_by = $this->request->session()->read('Auth.User.id');
                $user->modified = date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    $userDetailsID = $this->Members->getUserDetailsID($id);
                    $uDetails = $this->UserDetails->find('all')->where(['id' => $userDetailsID])->first();
                    $uDetailsUpdated = [];
                    $birthdate_1 = $this->request->data['birthdate_1'];
                    $birthdate_2 = $this->request->data['birthdate_2'];
                    $birthdate_3 = $this->request->data['birthdate_3'];
                    $birthdate_4 = $this->request->data['birthdate_4'];
                    $birthdate_5 = $this->request->data['birthdate_5'];
                    $cheque_date = $this->request->data['cheque_date'];
                    unset($this->request->data['birthdate_1']);
                    unset($this->request->data['birthdate_2']);
                    unset($this->request->data['birthdate_3']);
                    unset($this->request->data['birthdate_4']);
                    unset($this->request->data['birthdate_5']);
                    unset($this->request->data['cheque_date']);
                    $userData = [];
                    $userData['person_1'] = (isset($this->request->data['person_1']) && $this->request->data['person_1'] != '') ? $this->request->data['person_1'] : '';
                    $userData['person_2'] = (isset($this->request->data['person_2']) && $this->request->data['person_2'] != '') ? $this->request->data['person_2'] : '';
                    $userData['person_3'] = (isset($this->request->data['person_3']) && $this->request->data['person_3'] != '') ? $this->request->data['person_3'] : '';
                    $userData['person_4'] = (isset($this->request->data['person_4']) && $this->request->data['person_4'] != '') ? $this->request->data['person_4'] : '';
                    $userData['person_5'] = (isset($this->request->data['person_5']) && $this->request->data['person_5'] != '') ? $this->request->data['person_5'] : '';
                    $userData['occupation'] = (isset($this->request->data['occupation']) && $this->request->data['occupation'] != '') ? $this->request->data['occupation'] : '';
                    $userData['company_name'] = (isset($this->request->data['company_name']) && $this->request->data['company_name'] != '') ? $this->request->data['company_name'] : '';
                    $userData['company_website'] = (isset($this->request->data['company_website']) && $this->request->data['company_website'] != '') ? $this->request->data['company_website'] : '';
                    $userData['payment_type'] = (isset($this->request->data['payment_type']) && $this->request->data['payment_type'] != '') ? $this->request->data['payment_type'] : '';
                    $userData['bank_name'] = (isset($this->request->data['bank_name']) && $this->request->data['bank_name'] != '') ? $this->request->data['bank_name'] : '';
                    $userData['cheque_no'] = (isset($this->request->data['cheque_no']) && $this->request->data['cheque_no'] != '') ? $this->request->data['cheque_no'] : '';
                    $userData['transcation_id'] = (isset($this->request->data['transcation_id']) && $this->request->data['transcation_id'] != '') ? $this->request->data['transcation_id'] : '';
                    $userData['other_details'] = (isset($this->request->data['other_details']) && $this->request->data['other_details'] != '') ? $this->request->data['other_details'] : '';
                    $uDetails = $this->UserDetails->patchEntity($uDetails, $userData);
                    if (isset($birthdate_1) && $birthdate_1 != '') {
                        $uDetails->birthdate_1 = date('Y-m-d', strtotime($birthdate_1));
                    }
                    if (isset($birthdate_2) && $birthdate_2 != '') {
                        $uDetails->birthdate_2 = date('Y-m-d', strtotime($birthdate_2));
                    }
                    if (isset($birthdate_3) && $birthdate_3 != '') {
                        $uDetails->birthdate_3 = date('Y-m-d', strtotime($birthdate_3));
                    }
                    if (isset($birthdate_4) && $birthdate_4 != '') {
                        $uDetails->birthdate_4 = date('Y-m-d', strtotime($birthdate_4));
                    }
                    if (isset($birthdate_5) && $birthdate_5 != '') {
                        $uDetails->birthdate_5 = date('Y-m-d', strtotime($birthdate_5));
                    }
                    if (isset($cheque_date) && $cheque_date != '') {
                        $uDetails->cheque_date = date('Y-m-d', strtotime($cheque_date));
                    }
                    $uDetails->modified_by = $this->request->session()->read('Auth.User.id');
                    $uDetails->modified = date("Y-m-d H:i:s");

                    if ($this->UserDetails->save($uDetails)) {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index', '#' => 'scroll']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $this->set('user', $user);
    }

    //***********************************************************************************************//
    // * Function     :  deleteimage
    // * Parameter    :  
    // * Description  :  This function used to deleteimage of Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function deleteimage($fields, $photo = NULL) {
        $category = $this->ServiceCategory->find('all')->where([$fields => $photo])->hydrate(false)->first();
        $category_id = $category['id'];
        $category = $this->ServiceCategory->get($category_id);

        if ($fields == 'icon_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $photo;
        }
        if ($fields == 'banner_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $photo;
        }
        if ($fields == 'square_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $photo;
        }
        if (file_exists($fpath)) {
            unlink($fpath);
        }
        $category->$fields = "";
        if ($this->ServiceCategory->save($category)) {
            $this->Flash->success(Configure::read('Settings.DELETE'));
            return $this->redirect(['action' => 'edit', $category_id]);
        } else {
            $this->Flash->success(Configure::read('Settings.DELETEFAIL'));
            return $this->redirect(['action' => 'edit', $category_id]);
        }
    }

    //***********************************************************************************************//
    // * Function     :  view
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories details
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function view($id) {
        $member = [];
        $member = $this->Members->geMemberId($id);
        if (empty($member)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('member', $member);
    }

    //***********************************************************************************************//
    // * Function     :  delete
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories details
    // * Author       :  Chirag Manavar
    // * Date         :  26-October-2017
    //***********************************************************************************************//
//    public function delete() {
//        $id = $this->request->data['value'];
//        if (isset($id) && $id != '') {
//            //$hasServices = $this->Vendors->hasVendors($id);
//            if ($hasServices) {
//                $category_data = $this->ServiceCategory->getcategoryId($id); //LISTING CATEGORY
//                // Delete Icon
//                if (isset($category_data->icon_image) && $category_data->icon_image != '') {
//                    $fipath = WWW_ROOT . 'img' . SERVICE_CATEGORY_ICON_PATH . $category_data->icon_image;
//                    if (file_exists($fipath)) {
//                        unlink($fipath);
//                    }
//                }
//                // Delete Banner
//                if (isset($category_data->banner_image) && $category_data->banner_image != '') {
//                    $fbpath = WWW_ROOT . 'img' . SERVICE_CATEGORY_BANNER_PATH . $category_data->banner_image;
//                    if (file_exists($fbpath)) {
//                        unlink($fbpath);
//                    }
//                }
//                // Delete Icon
//                if (isset($category_data->square_image) && $category_data->square_image != '') {
//                    $fspath = WWW_ROOT . 'img' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $category_data->square_image;
//                    if (file_exists($fspath)) {
//                        unlink($fspath);
//                    }
//                }
//                if ($this->ServiceCategory->delete($category_data)) {
//                    $this->Flash->success(Configure::read('Settings.DELETE'));
//                    $this->redirect(array('action' => 'index'));
//                    exit;
//                } else {
//                    $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
//                    $this->redirect(array('action' => 'index'));
//                    exit;
//                }
//            } else {
//                $this->Flash->error(__("YOU CAN'T DELETE THIS CATEGORY BECAUSE IT HAVE OLD ORDERS!"));
//                $this->redirect(array('action' => 'index'));
//                exit;
//            }
//            //$user = $this->ServiceCategory->getuserId($this->request->data['value']); // GET USER DATA FROM ID
//        } else {
//            $this->Flash->error(__('RECORD DOES NOT EXIST'));
//            $this->redirect(array('action' => 'index'));
//            exit;
//        }
//    }

    public function sentEmails($name, $email, $password) {
        $this->layout = 'ajax';
        $mailData = [];
        $mailData['name'] = $name;
        $mailData['email'] = $email;
        $mailData['password'] = $password;
        $this->set('mailData', $mailData);
        //pr($mailData); exit;
        $view_output = $this->render('/Element/membership_signup');
        $fields = array(
            'msg' => $view_output,
            'tomail' => 'chiragce1992@gmail.com',
            //'cc_email' => $patient['email'],
            'subject' => 'Membership Account Details',
            'from_name' => 'Uncode Lab',
            'from_mail' => 'uncodelab@gmail.com',
        );
        $this->sendemails($fields);
        return;
    }

    public function login() {
        $this->layout = 'ajax';
        $this->loadModel('Users');
        $user = $this->Users->newEntity();
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'Email Address' => (isset($requestArr['user']) && $requestArr['user'] != '') ? $requestArr['user'] : '',
            'Password' => (isset($requestArr['password']) && $requestArr['password'] != '') ? $requestArr['password'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        } else {
            $this->request->data = [];
            $this->request->data['email'] = $requestArr['user'];
            $this->request->data['password'] = $requestArr['password'];
            $user = $this->Auth->identify();
//            print_r($user); exit;
            if ($user) {
                if (isset($user['active']) && $user['active'] == 'Y') {
                    if (isset($user['user_type']) && $user['user_type'] == 'VENDOR') {
                        $rslt = [];
                        $rslt['api_key'] = $this->Users->getApiKey($user['id']);
                        $rslt['id'] = $user['id'];
                        $rslt['name'] = $user['name'];
                        $rslt['email'] = $user['email'];
                        $rslt['phone_no'] = $user['phone_no'];
                        $rslt['profile_pic'] = '';
                        $rslt['address'] = $user['address'];
                        $rslt['city'] = $user['city'];
                        $rslt['email_verified'] = $user['email_verified'];
                        $rslt['phone_verified'] = $user['phone_verified'];
                        $rslt['wallet_amount'] = 0;
                        $this->success('LOGIN!', $rslt);
                    } else {
                        $this->wrong('Sorry, Your account is not exists!');
                    }
                } else {
                    $this->wrong('Sorry, Your account is not activated!');
                }
            } else {
                $this->wrong('Invalid email or password, try again');
            }
        }
    }

    public function getReferKey($name, $phone = '') {
        $nameArr = explode(" ", $name);
        $first = strtoupper($nameArr[0]);
        if (isset($phone) && $phone != '') {
            $second = substr($phone, -5);
        } else {
            $second = rand(11111, 99999);
        }
        return $first . $second;
    }

    public function useractive($id) {
        $this->data = [];
        if (isset($id) && $id != '') {
            $this->layout = 'ajax';
            $this->loadModel('Users');
            $user = $this->Users->get($id);
            if (!empty($user)) {
                $updatedArr = [];
                $updatedArr['active'] = 'Y';
                $user = $this->Users->patchEntity($user, $updatedArr);
                $user->modified = date('Y-m-d H:i:s', strtotime($_POST['modified']));
                $user->modified_by = $_POST['modified_by'];
                if ($this->Users->save($user)) {
                    $this->data['status'] = 'success';
                    $this->data['msg'] = 'User profile Activated!';
                } else {
                    $this->data['status'] = 'fail';
                    $this->data['msg'] = 'Sorry, User not active!';
                }
            } else {
                $this->data['status'] = 'fail';
                $this->data['msg'] = 'Sorry, User data is missing!';
            }
        } else {
            $this->data['status'] = 'fail';
            $this->data['msg'] = 'Sorry, Userid is missing!';
        }
        echo json_encode($this->data);
        exit;
    }

}
