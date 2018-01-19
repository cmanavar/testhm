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
        $this->Auth->allow(['delete', 'login']);
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
        $this->loadModel('HmenPlan');
        $this->loadModel('Users');
        $this->loadModel('UserDetails');
        $planLists = $this->HmenPlan->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->hydrate(false)->toArray();
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
            //pr($this->request->data);
            //exit;
            if (empty($errors)) {
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
                    $userData['plan_id'] = (isset($this->request->data['plan_id']) && $this->request->data['plan_id'] != '') ? $this->request->data['plan_id'] : '';
                    $userData['ip_address'] = $this->get_client_ip();
                    $userData['refer_key'] = $this->getReferKey($name, $phone_no);
                    $userData['referral_id'] = (isset($this->request->data['refer_id']) && $this->request->data['refer_id'] != '') ? $this->request->data['refer_id'] : 0;
                    $userData['email_newsletters'] = 'Y';
                    $userData['phone_verified'] = 'Y';
                    $userData['email_verified'] = 'Y';
                    $userData['active'] = 'N';
                    $user = $this->Users->patchEntity($user, $userData);
                    // SEND SMS
                    $msgT = "Dear $name, Your Membership account credentials: email:$email, password:$password You can login after your payment clearance. Regards, H-Men";
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
                            $userData['payment_type'] = (isset($this->request->data['payment_type']) && $this->request->data['payment_type'] != '') ? $this->request->data['payment_type'] : '';
                            $userData['bank_name'] = (isset($this->request->data['bank_name']) && $this->request->data['bank_name'] != '') ? $this->request->data['bank_name'] : '';
                            $userData['cheque_no'] = (isset($this->request->data['cheque_no']) && $this->request->data['cheque_no'] != '') ? $this->request->data['cheque_no'] : '';
                            $userData['transcation_id'] = (isset($this->request->data['transcation_id']) && $this->request->data['transcation_id'] != '') ? $this->request->data['transcation_id'] : '';
                            $userData['other_details'] = (isset($this->request->data['other_details']) && $this->request->data['other_details'] != '') ? $this->request->data['other_details'] : '';
                            $users = $this->UserDetails->patchEntity($users, $userData);
                            $users->birthdate_1 = (isset($this->request->data['birthdate_1']) && $this->request->data['birthdate_1'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_1'])) : '';
                            $users->birthdate_2 = (isset($this->request->data['birthdate_2']) && $this->request->data['birthdate_2'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_2'])) : '';
                            $users->birthdate_3 = (isset($this->request->data['birthdate_3']) && $this->request->data['birthdate_3'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_3'])) : '';
                            $users->birthdate_4 = (isset($this->request->data['birthdate_4']) && $this->request->data['birthdate_4'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_4'])) : '';
                            $users->birthdate_5 = (isset($this->request->data['birthdate_5']) && $this->request->data['birthdate_5'] != '') ? date('Y-m-d', strtotime($this->request->data['birthdate_5'])) : '';
                            $users->cheque_date = (isset($this->request->data['cheque_date']) && $this->request->data['cheque_date'] != '') ? date('Y-m-d', strtotime($this->request->data['cheque_date'])) : '';
                            $users->created = date("Y-m-d H:i:s");
                            $users->created_by = $this->request->session()->read('Auth.User.id');
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
        //CHECK IF THE RECORD EXISTS OR NOT 
        $query = $this->Vendors->vendorExists($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $category = $this->Vendors->getVendorId($id); //LISTING CATEGORYDATA
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new ServiceCategoryValidator();
            $errors = $validator->errors($this->request->data());
            $order_id = $this->request->data()['order_id'];
            $isExist = $this->ServiceCategory->find('all')->where(['id !=' => $id, 'order_id' => $order_id])->hydrate(false)->first();
            if ($isExist) {
                $errors['order_id']['_unique'] = 'Order id is already assignd';
            } else {
                unset($errors['order_id']['_unique']);
            }
            if (empty($errors)) {
                $category = $this->ServiceCategory->patchEntity($category, $this->request->data);
                if (isset($this->request->data['icon']['name']) && $this->request->data['icon']['name'] != '') {
                    $file = $this->request->data['icon']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['icon']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $filename);
                    $category['icon_image'] = $filename;
                }
                if (isset($this->request->data['banner']['name']) && $this->request->data['banner']['name'] != '') {
                    $file = $this->request->data['banner']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['banner']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $filename);
                    $category['banner_image'] = $filename;
                }
                if (isset($this->request->data['square']['name']) && $this->request->data['square']['name'] != '') {
                    $file = $this->request->data['square']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['square']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $filename);
                    $category['square_image'] = $filename;
                }
                $category->modified = date("Y-m-d H:i:s");
                $category->modified_by = $this->request->session()->read('Auth.User.id');
                if ($this->ServiceCategory->save($category)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $category = $this->ServiceCategory->getcategoryId($id); //LISTING CATEGORYDATA
        $this->set('category', $category);
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
        $vendor = [];
        $vendor = $this->Vendors->getVendorId($id);
        if (empty($vendor)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('vendor', $vendor);
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

}
