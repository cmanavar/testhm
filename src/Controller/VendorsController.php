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
        $this->Auth->allow(['delete','login']);
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
            if (!isset($this->request->data['service_id']) || $this->request->data['service_id'] == '') {
                $this->Flash->error('Sorry, Please select service id');
            }
//            pr($this->request->data); exit;
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $this->request->data['password'] = $usersController->randomPassword();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $name = $this->request->data['name'];
                $email = $this->request->data['email'];
                $phone_no = $this->request->data['phone_no'];
                $phone_no_2 = $this->request->data['phone_number_2'];
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
                    $userData['city'] = (isset($requestArr['city']) && $requestArr['city'] != '') ? $requestArr['city'] : '';
                    $userData['signup_with'] = 'SELF';
                    $userData['user_type'] = 'VENDOR';
                    $userData['ip_address'] = $this->get_client_ip();
                    $userData['email_newsletters'] = (isset($requestArr['email_newsletters']) && $requestArr['email_newsletters'] != '') ? $requestArr['email_newsletters'] : 'N';
                    $userData['phone_verified'] = 'Y';
                    $userData['email_verified'] = 'Y';
                    $userData['active'] = 'Y';
                    if (isset($this->request->data['profile_picture']['name']) && $this->request->data['profile_picture']['name'] != '') {
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
                                $file = $this->request->data['agreement']['name'];
                                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                                move_uploaded_file($this->request->data['agreement']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_AGREEMENT_PATH . $filename);
                                $vendorData['agreement'] = $filename;
                            }
                            if (isset($this->request->data['id_proof']['name']) && $this->request->data['id_proof']['name'] != '') {
                                $file = $this->request->data['id_proof']['name'];
                                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                                move_uploaded_file($this->request->data['id_proof']['tmp_name'], WWW_ROOT . 'img/' . VENDOR_IDPROOF_PATH . $filename);
                                $vendorData['id_proof'] = $filename;
                            }
                            $vendor = $this->VendorDetails->patchEntity($vendor, $vendorData);
                            $vendor->service_id = $this->request->data['service_id'];
                            $vendor->shift_start = $this->request->data['shift_start'];
                            $vendor->shift_end = $this->request->data['shift_end'];
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
        $view_output = $this->render('/Element/signup_self');
        $fields = array(
            'msg' => $view_output,
            'tomail' => 'chiragce1992@gmail.com',
            //'cc_email' => $patient['email'],
            'subject' => 'VENDOR EMAIL',
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

}
