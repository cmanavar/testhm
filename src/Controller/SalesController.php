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

class SalesController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['delete']);
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
        $usersTable = TableRegistry::get('Users');
        $vendors = $usersTable->find('all')->where(['user_type' => 'SALES'])->order(['id' => 'ASC'])->hydrate(false)->toArray();
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
        $this->loadModel('Users');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $this->request->data['password'] = $usersController->randomPassword();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
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
                    $userData['password'] = $password;
                    $userData['city'] = (isset($requestArr['city']) && $requestArr['city'] != '') ? $requestArr['city'] : '';
                    $userData['signup_with'] = 'SELF';
                    $userData['user_type'] = 'SALES';
                    $userData['ip_address'] = $this->get_client_ip();
                    $userData['email_newsletters'] = (isset($requestArr['email_newsletters']) && $requestArr['email_newsletters'] != '') ? $requestArr['email_newsletters'] : 'N';
                    $userData['phone_verified'] = 'Y';
                    $userData['email_verified'] = 'Y';
                    $userData['active'] = 'Y';
                    $user = $this->Users->patchEntity($user, $userData);
                    // SEND SMS
                    $msgT = "Dear $name, Your credentials email:$email, password:$password . Regards, H-Men";
                    $sendMsg = $this->sendOtp($phone_no, $msgT);
                    if ($sendMsg['status'] == 'fail') {
                        $this->Flash->error($sendMsg['msg']);
                    }
                    // SEND EMAIL
                    $this->sentSalesEmails($name, $email, $password);
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
                            'user_type' => 'SALES',
                            'mapping_key' => 'api_key',
                            'mapping_value' => $api_key
                        );
                        $userMapping = $this->UserMapping->patchEntity($userMapping, $map_data);
                        $userMapping->created = date("Y-m-d H:i:s");
                        if ($this->UserMapping->save($userMapping)) {
                            $this->Flash->success(__('THE SALES HAS BEEN SAVED.'));
                            return $this->redirect(['action' => 'index']);
                        } else {
                            $this->Flash->error(__('UNABLE TO ADD THE SALES.'));
                        }
                    } else {
                        $this->Flash->error(__('UNABLE TO ADD THE SALES.'));
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
        $this->loadModel('Users');
        $user = $this->Users->get($id);
        if (!empty($user)) {
            if ($this->request->is(['patch', 'post', 'put'])) {
                $validator = new UsersValidator();
                $usersController = new UsersController();
                $errors = $validator->errors($this->request->data());
                if (empty($errors)) {
                    $user = $this->Users->patchEntity($user, $this->request->data());
                    $user->modified_by = $this->request->session()->read('Auth.User.id');
                    $user->modified = date("Y-m-d H:i:s");
                    if ($this->Users->save($user)) {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->set('errors', $errors);
                }
            }
            $this->set('user', $user);
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
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
            $user = [];
            $this->loadModel('Users');
            $user = $this->Users->find('all')->where(['user_type' => 'SALES', 'id' => $id])->order(['id' => 'DESC'])->hydrate(false)->first();
            if (empty($user)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
            $this->set('vendor', $user);
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function sentSalesEmails($name, $email, $password) {
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
            'subject' => 'SALES EMAIL',
            'from_name' => EMAIL_FROM_NAME,
            'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
        );
        $this->sendemails($fields);
        return;
    }

}
