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

class VendorsController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['delete', 'login', 'deleteprofileimage']);
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
        $vendors = [];
        $vendors = $this->Vendors->getVendors();
        $this->set('vendors', $vendors);
    }

    //***********************************************************************************************//
    // * Function     :  add
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function add() {
        $this->loadModel('Services');
        $services = $this->Services->find('list', [ 'keyField' => 'id', 'valueField' => 'service_name'])->where(['status' => 'active'])->toArray();
        $this->set('services', $services);
        $this->loadModel('Users');
        $this->loadModel('VendorDetails');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if ($this->request->data['user_type'] == 'VENDOR') {
                if (!isset($this->request->data['service_id']) || $this->request->data['service_id'] == '') {
                    $this->Flash->error('Sorry, Please select service id');
                }
            }
            //pr($this->request->data); exit;
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $this->request->data['password'] = $usersController->randomPassword();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                //pr($this->request->data); exit;
                $name = $this->request->data['name'];
                $email = $this->request->data['email'];
                $phone_no = $this->request->data['phone_no'];
                $userExists = $this->Users->uniqueEmailOrPhone($email, $phone_no);
                //echo $name; exit;
                if (isset($userExists['status']) && $userExists['status'] == 'fail') {
                    $this->Flash->error($userExists['msg']);
                } else {
                    $uController = new UsersController();
                    $password = $uController->randomPassword();
                    $userData = [];
                    $userData['name'] = $name;
                    $userData['email'] = $email;
                    $userData['phone_no'] = $phone_no;
                    $userData['phone_number_2'] = (isset($this->request->data['phone_number_2']) && $this->request->data['phone_number_2'] != '') ? $this->request->data['phone_number_2'] : '-';
                    $userData['password'] = $password;
                    $userData['city'] = (isset($this->request->data['city']) && $this->request->data['city'] != '') ? $this->request->data['city'] : '';
                    $userData['signup_with'] = 'SELF';
                    $userData['user_type'] = 'VENDOR';
                    $userData['ip_address'] = $this->get_client_ip();
                    $userData['email_newsletters'] = (isset($this->request->data['email_newsletters']) && $this->request->data['email_newsletters'] != '') ? $this->request->data['email_newsletters'] : 'N';
                    $userData['phone_verified'] = 'Y';
                    $userData['email_verified'] = 'Y';
                    $userData['active'] = 'Y';
                    if (isset($this->request->data['profile_picture']['name']) && $this->request->data['profile_picture']['name'] != '') {
                        $filename = '';
                        $file = $this->request->data['profile_picture']['name'];
                        $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                        $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                        $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                        move_uploaded_file($this->request->data['profile_picture']['tmp_name'], WWW_ROOT . 'img/' . USER_PROFILE_PATH . $filename);
                        $userData['profile_pic'] = $filename;
                    }
                    $user = $this->Users->patchEntity($user, $userData);
                    // SEND SMS
                    $msgT = "Dear $name, Your credentials email:$email, password:$password . Regards, H-Men";
                    $sendMsg = $this->sendOtp($phone_no, $msgT);
                    if ($sendMsg['status'] == 'fail') {
                        $this->Flash->error($sendMsg['msg']);
                    }
                    // SEND EMAIL
                    $this->sentEmails($name, $email, $password);
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
                            'user_type' => 'VENDOR',
                            'mapping_key' => 'api_key',
                            'mapping_value' => $api_key
                        );
                        $userMapping = $this->UserMapping->patchEntity($userMapping, $map_data);
                        $userMapping->created = date("Y-m-d H:i:s");
                        if ($this->UserMapping->save($userMapping)) {
                            $vendor = $this->VendorDetails->newEntity();
                            $vendorData = [];
                            $vendorData['user_id'] = $userId;
                            if (isset($this->request->data['agreement']['name']) && $this->request->data['agreement']['name'] != '') {
                                $filename = '';
                                $file = $this->request->data['agreement']['name'];
                                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                                move_uploaded_file($this->request->data['agreement']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_AGREEMENT_PATH . $filename);
                                $vendorData['agreement'] = $filename;
                            }
                            if (isset($this->request->data['id_proof']['name']) && $this->request->data['id_proof']['name'] != '') {
                                $filename = '';
                                $file = $this->request->data['id_proof']['name'];
                                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                                move_uploaded_file($this->request->data['id_proof']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_IDPROOF_PATH . $filename);
                                $vendorData['id_proof'] = $filename;
                            }
                            $vendor = $this->VendorDetails->patchEntity($vendor, $vendorData);
                            $vendor->service_id = $this->request->data['service_id'];
                            $vendor->vendor_type = $this->request->data['vendor_type'];
                            $vendor->created = date("Y-m-d H:i:s");
                            $vendor->created_by = $this->request->session()->read('Auth.User.id');
                            if ($this->VendorDetails->save($vendor)) {
                                $this->Flash->success(__('THE VENDOR HAS BEEN SAVED.'));
                                return $this->redirect(['action' => 'index']);
                            } else {
                                $this->Flash->error(__('UNABLE TO ADD THE VENDOR.'));
                            }
                        } else {
                            $this->Flash->error(__('UNABLE TO ADD THE VENDOR.'));
                        }
                    } else {
                        $this->Flash->error(__('UNABLE TO ADD THE VENDOR.'));
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
        $this->loadModel('Services');
        $services = $this->Services->find('list', [ 'keyField' => 'id', 'valueField' => 'service_name'])->where(['status' => 'active'])->toArray();
        $this->set('services', $services);
        $this->loadModel('Users');
        $this->loadModel('VendorDetails');
        //CHECK IF THE RECORD EXISTS OR NOT 
        $query = $this->Vendors->vendorExists($id);
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $user = $this->Vendors->getVendorId($id); //GET VENDORS DATA
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new UsersValidator();
            $usersController = new UsersController();
            //$this->request->data['password'] = $usersController->randomPassword();
            //pr($this->request->data); exit;
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $user = $this->Users->getuserId($id); //LISTING USERDATA
                $user = $this->Users->patchEntity($user, $this->request->data());
                if (isset($this->request->data['profile_picture']['name']) && $this->request->data['profile_picture']['name'] != '') {
                    $filename = '';
                    $file = $this->request->data['profile_picture']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['profile_picture']['tmp_name'], WWW_ROOT . 'img/' . USER_PROFILE_PATH . $filename);
                    $user->profile_pic = $filename;
                }
                $user->modified_by = $this->request->session()->read('Auth.User.id');
                $user->modified = date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    $vendorDetailsId = $this->Vendors->getVendorDetailsID($id);
                    //echo $vendorDetailsId; exit;
                    if ($vendorDetailsId != 0) {
                        $vDetails = $this->VendorDetails->find('all')->where(['id' => $vendorDetailsId])->first();
                        $vData = [];
                        $vData['user_id'] = $id;
                        $vData['service_id'] = $this->request->data['service_id'];
                        $vData['vendor_type'] = $this->request->data['vendor_type'];
                        $vDetails = $this->VendorDetails->patchEntity($vDetails, $vData);
                        if (isset($this->request->data['agreement']['name']) && $this->request->data['agreement']['name'] != '') {
                            $filename = '';
                            $file = $this->request->data['agreement']['name'];
                            $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                            $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                            $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                            move_uploaded_file($this->request->data['agreement']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_AGREEMENT_PATH . $filename);
                            $vDetails['agreement'] = $filename;
                        }
                        if (isset($this->request->data['id_proof']['name']) && $this->request->data['id_proof']['name'] != '') {
                            $filename = '';
                            $file = $this->request->data['id_proof']['name'];
                            $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                            $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                            $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                            move_uploaded_file($this->request->data['id_proof']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_IDPROOF_PATH . $filename);
                            $vDetails['id_proof'] = $filename;
                        }
                        $vDetails->modified_by = $this->request->session()->read('Auth.User.id');
                        $vDetails->modified = date("Y-m-d H:i:s");
                        if ($this->VendorDetails->save($vDetails)) {
                            $this->Flash->success(Configure::read('Settings.SAVE'));
                            return $this->redirect(['action' => 'index', '#' => 'scroll']);
                        } else {
                            $this->Flash->error(Configure::read('Settings.FAIL'));
                        }
                    } else {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index', '#' => 'scroll']);
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

    public function deleteprofileimage($id) {
        $this->loadModel('Users');
        $users = $this->Users->get($id);
        if ($users->profile_pic != '') {
            $fpath = WWW_ROOT . 'img/' . USER_PROFILE_PATH . $photo;
        }
        if (file_exists($fpath)) {
            unlink($fpath);
        }
        $users->profile_pic = '';
        if ($this->Users->save($users)) {
            $this->Flash->success(Configure::read('Settings.DELETE'));
            return $this->redirect(['action' => 'edit', $id]);
        } else {
            $this->Flash->success(Configure::read('Settings.DELETEFAIL'));
            return $this->redirect(['action' => 'edit', $id]);
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
        if (isset($id) && $id != '') {
            $vendor = [];
            $vendor = $this->Vendors->getVendorId($id);
            if (empty($vendor)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
            $this->set('vendor', $vendor);
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function sentEmails($name, $email, $password) {
        $this->layout = 'ajax';
        $mailData = [];
        $mailData['name'] = $name;
        $mailData['email'] = $email;
        $mailData['password'] = $password;
        $this->set('mailData', $mailData);
        $view_output = $this->render('/Element/vendors_template');
        $fields = array(
            'msg' => $view_output,
            'tomail' => $email,
            'subject' => 'VENDOR EMAIL',
            'from_name' => EMAIL_FROM_NAME,
            'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
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
                    if (isset($user['user_type']) && ($user['user_type'] == 'VENDOR' || $user['user_type'] == 'SALES')) {
                        $rslt = [];
                        $rslt['api_key'] = $this->Users->getApiKey($user['id']);
                        $rslt['id'] = $user['id'];
                        $rslt['name'] = $user['name'];
                        $rslt['email'] = $user['email'];
                        $rslt['phone_no'] = $user['phone_no'];
                        $rslt['profile_pic'] = ($user['profile_pic'] != '') ? IMAGE_URL_PATH . 'profile_picture/' . $user['profile_pic'] : IMAGE_URL_PATH . 'users/user.png';
                        $rslt['user_type'] = $user['user_type'];
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

    public function downloadAgreements($id) {
        $vendor = $this->Vendors->getVendorId($id);
        if (empty($vendor)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $filePath = WWW_ROOT . 'img/' . VENDOR_AGREEMENT_PATH . $vendor['agreement'];
        $this->response->file($filePath, array(
            'download' => true,
            'name' => $vendor['agreement'],
        ));
        return $this->response;
    }

    public function downloadIdProof($id) {
        $vendor = $this->Vendors->getVendorId($id);
        if (empty($vendor)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $filePath = WWW_ROOT . 'img/' . VENDOR_AGREEMENT_PATH . $vendor['id_proof'];
        $this->response->file($filePath, array(
            'download' => true,
            'name' => $vendor['id_proof'],
        ));
        return $this->response;
    }

    public function workdetails($id) {
        $this->loadModel('VendorCash');
        $this->loadModel('VendorCashHistory');
        if (isset($id) && $id != '') {
            $vendor = [];
            $vendor = $this->Vendors->getVendorId($id);
            if (empty($vendor)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
            $filter = $this->VendorCash->newEntity();
            $condArrPending = [];
            $condArrClear = [];
            $condArrTotal = [];
            $condArrCashDetails = [];
            if ($this->request->is('post')) {
                //pr($this->request->data); exit;
                if (isset($this->request->data['from_date']) && $this->request->data['from_date'] != 0) {
                    $condArrPending["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
                    $condArrClear["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
                    $condArrTotal["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
                    $condArrCashDetails["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
                }
                if (isset($this->request->data['to_date']) && $this->request->data['to_date'] != 0) {
                    $condArrPending["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
                    $condArrClear["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
                    $condArrTotal["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
                    $condArrCashDetails["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
                }
                $this->request->session()->write('vendorcashFilter', $this->request->data);
            }
            $condArrPending['vendor_id'] = $id;
            $condArrPending['payment_type'] = 'CREDIT';
            $condArrPending['payment_status'] = 'PENDING';
            $condArrClear['vendor_id'] = $id;
            $condArrClear['payment_type'] = 'CREDIT';
            $condArrClear['payment_status'] = 'CLEAR';
            $condArrTotal['vendor_id'] = $id;
            $condArrTotal['payment_type'] = 'CREDIT';
            $condArrCashDetails['vendor_id'] = $id;
            $this->set('filter', $filter);
            $creditPendingCash = $this->VendorCash->find('all')->select(['tot' => 'SUM(commissions)'])->where($condArrPending)->hydrate(false)->first();
            $creditClearCash = $this->VendorCash->find('all')->select(['tot' => 'SUM(commissions)'])->where($condArrClear)->hydrate(false)->first();
            $credittotalCash = $this->VendorCash->find('all')->select(['tot' => 'SUM(commissions)'])->where($condArrTotal)->hydrate(false)->first();
            $vendor['due_cash'] = isset($creditPendingCash['tot']) && $creditPendingCash['tot'] != 0 ? $creditPendingCash['tot'] : 0.00;
            $vendor['clear_cash'] = isset($creditClearCash['tot']) && $creditClearCash['tot'] != 0 ? $creditClearCash['tot'] : 0.00;
            $vendor['total_cash'] = isset($credittotalCash['tot']) && $credittotalCash['tot'] != '' ? $credittotalCash['tot'] : 0.00;
            $vendorCashDetails = $this->VendorCash->find('all')->where($condArrCashDetails)->hydrate(false)->toArray();
            $vendor['vendor_cash'] = $vendorCashDetails;
            $vendorCashHistory = $this->VendorCashHistory->find('all')->where(['vendor_id' => $id])->hydrate(false)->toArray();
            $vendor['cash_history'] = $vendorCashHistory;
            $this->set('vendor', $vendor);
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function resetFilter($id) {
        $this->request->session()->delete('vendorcashFilter');
        return $this->redirect(array('action' => 'workdetails', $id));
    }

    public function payment($id) {
        $this->loadModel('VendorCash');
        $this->loadModel('VendorCashHistory');
        $this->loadModel('Users');
        if (isset($id) && $id != '') {
            $vendor = [];
            $vendor = $this->Vendors->getVendorId($id);
            if (empty($vendor)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
            $users = $this->Users->find('list')->select(['id', 'name'])->where(['user_type IN' => ['ADMIN', 'OPERATION_MANAGER']])->hydrate(false)->toArray();
            $this->set('users', $users);
            $searchdatavendors = $this->request->session()->read('vendorcashFilter');
            $condArr = [];

            $filterArr = $searchdatavendors;
            $condArr['vendor_id'] = $id;
            $from_date = $to_date = '';
            if (isset($filterArr['from_date']) && $filterArr['from_date'] != 0) {
                $from_date = $filterArr['from_date'];
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($filterArr['from_date']));
            }
            if (isset($filterArr['to_date']) && $filterArr['to_date'] != 0) {
                $to_date = $filterArr['to_date'];
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($filterArr['to_date']));
            }
            $condArr['payment_status'] = 'PENDING';
            $condArrCredit = $condArr;
            $condArrDebit = $condArr;
            $condArrCredit['payment_type'] = 'CREDIT';
            $condArrDebit['payment_type'] = 'DEBIT';
            $totCredits = $this->VendorCash->find('all')->select(['tot' => 'SUM(commissions)'])->where($condArrCredit)->hydrate(false)->first();
            $totDebits = $this->VendorCash->find('all')->select(['tot' => 'SUM(commissions)'])->where($condArrDebit)->hydrate(false)->first();
            $vendor['credits'] = ($totCredits['tot'] != 0) ? $totCredits['tot'] : 0.00;
            $vendor['debit'] = ($totDebits['tot'] != 0) ? $totDebits['tot'] : 0.00;
            $vendorCashDetails = $this->VendorCash->find('all')->where($condArr)->hydrate(false)->toArray();
            if (isset($vendorCashDetails) && !empty($vendorCashDetails)) {
                //pr($vendorCashDetails); exit;
                $dateArr = [];
                foreach ($vendorCashDetails as $val) {
                    $dateArr[] = $val['created']->format('Y-m-d');
                }
                if ($from_date == '') {
                    $from_date = min($dateArr);
                }
                if ($to_date == '') {
                    $to_date = max($dateArr);
                }
                //echo $from_date . " " . $to_date; exit;
                $vendor['vendor_cash'] = $vendorCashDetails;
                $this->set('vendor', $vendor);
                $vendorCashHistory = $this->VendorCashHistory->newEntity();
                $this->set('vendorCash', $vendorCashHistory);
                if ($this->request->is('post')) {
                    //pr($this->request->data); exit;
                    $newRecords = [];
                    $newRecords['vendor_id'] = $id;
                    //$newRecords['from_date'] = $from_date;
                    //$newRecords['to_date'] = $to_date;
                    if ($this->request->data['payment_amount'] < 0) {
                        $amount = $this->request->data['payment_amount'];
                        $payment_type = 'DEBIT';
                    } else {
                        $amount = $this->request->data['payment_amount'];
                        $payment_type = 'CREDIT';
                    }
                    $newRecords['payment_type'] = $payment_type;
                    $newRecords['amount'] = $amount;
                    $newRecords['payment_received_by'] = $this->request->session()->read('Auth.User.id');
                    $vendorCashHistory = $this->VendorCashHistory->patchEntity($vendorCashHistory, $newRecords);
                    $vendorCashHistory->from_date = $from_date;
                    $vendorCashHistory->to_date = $to_date;
                    $vendorCashHistory->created = date('Y-m-d H:i:s');
                    $vendorCashHistory->created_by = $this->request->session()->read('Auth.User.id');
                    if ($this->VendorCashHistory->save($vendorCashHistory)) {
                        $newCondArr = [];
                        $newCondArr["vendor_id"] = $id;
                        $newCondArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($from_date));
                        $newCondArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($to_date));
                        $updateRecords = ['payment_status' => 'CLEAR', 'payment_receivedby' => $this->request->session()->read('Auth.User.id')];
                        $this->VendorCash->updateAll($updateRecords, $newCondArr);
                        $this->Flash->success(__('Vendor Cash Received!'));
                        $this->request->session()->delete('vendorcashFilter');
                        return $this->redirect(['action' => 'workdetails', $id]);
                    } else {
                        $this->Flash->error(__('Vendor Cash Not saved!'));
                        $this->request->session()->delete('vendorcashFilter');
                        return $this->redirect(['action' => 'workdetails', $id]);
                    }
                }
            } else {
                $this->Flash->error(__('Vendor Cash Not Found!'));
                $this->request->session()->delete('vendorcashFilter');
                return $this->redirect(['action' => 'workdetails', $id]);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

}
