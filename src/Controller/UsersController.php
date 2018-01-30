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
use Cake\Event\Event;
use Cake\Auth\DefaultPasswordHasher;
use App\Model\Validation\UsersValidator;
use Cake\ORM\TableRegistry;
use Cake\Utility\Security;
use Cake\Network\Email\Email;
use Cake\Controller\Component\CookieComponent;
use App\Controller\View;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : UsersController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class UsersController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['logout', 'addappusers', 'appuserslogin', 'verifiedEmail', 'verifiedPhone', 'resendOtp', 'resendActivationLinks', 'updateProfile', 'forgorPassword', 'resetPasswords']);
    }

    //***********************************************************************************************//
    // * Function     :  login
    // * Parameter    :  
    // * Description  :  This function used to Login in to system set authdata
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function login() {
        $this->layout = 'login';
        if ($this->request->is('post')) {
            $validator = new UsersValidator();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $user = $this->Auth->identify();
                if ($user) {
                    if (in_array($user['user_type'], ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
                        $this->Auth->setUser($user);
                        $users = $this->Users->get($user['id']);
                        $users->last_login = date('Y-m-d H:i:s');
                        if ($this->Users->save($users)) {
                            return $this->redirect(['controller' => 'Dashboard', 'action' => 'index']);
                        }
                    } else {
                        $this->Flash->error(__('Your account does not have access!'));
                    }
                } else {
                    $this->Flash->error(__('Invalid email or password, try again'));
                }
            }
        }
    }

    //***********************************************************************************************//
    // * Function     :  logout
    // * Parameter    :  
    // * Description  :  This function used to Logout from system and delete auth data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//



    public function logout() {
        $this->Cookie->config('domain', $_SERVER['HTTP_HOST']);
        $this->Cookie->delete('remember_me'); # delete invalid cookie
        $this->Flash->success('LOG OUT SUCCESSFULLY!');
        $this->request->session()->delete('Auth'); // delete auth session
        return $this->redirect($this->Auth->logout());
        exit;
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to Find All Users and add new user
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function index() {
        $user_type = 'hmen';
        $users = $this->Users->getuserlisting($user_type)->toArray(); //LISTING USERDATA
        $this->set('user_type', $user_type);
        $this->set('users', $users);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to Find All Users and add new user
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function adduser() {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            $validator = new UsersValidator();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $username = $this->Users->getuservalidation($this->request->data); // USER VALIDATION BY EMAIL
                if (empty($username)) {
                    $user = $this->Users->patchEntity($user, $this->request->data);
                    $user->created = date("Y-m-d H:i:s");
                    $user->created_by = $this->request->session()->read('Auth.User.id');
                    if ($this->Users->save($user)) {
                        $this->Flash->success(__('THE USER HAS BEEN SAVED.'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(__('UNABLE TO ADD THE USER.'));
                    }
                } else {
                    $this->Flash->error('EMAIL ID IS ALREAY EXIST. PLEASE, TRY AGAIN LATER!');
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $this->set('user', $user);
    }

    public function appuser() {
        $user_type = 'app';
        $users = $this->Users->getuserlisting($user_type)->toArray(); //LISTING USERDATA
        $this->set('user_type', $user_type);
        $this->set('users', $users);
    }

    //***********************************************************************************************//
    // * Function     :  edituser
    // * Parameter    :  $id
    // * Description  :  This function used to  Find All Users and Update  user entry
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function edituser($id = null) {
        //CHECK IF THE RECORD EXISTS OR NOT 
        $query = $this->Users->getuservalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $user = $this->Users->getuserId($id); //LISTING USERDATA
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new UsersValidator();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $user = $this->Users->patchEntity($user, $this->request->data());
                $user->modified_by = $this->request->session()->read('Auth.User.id');
                $user->modified = date("Y-m-d H:i:s");
                if ($this->Users->save($user)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index', '#' => 'scroll']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $user = $this->Users->getuserId($id); //LISTING USERDATA
        $this->set('user', $user);
        $username = $this->Users->getuserlisting()->toArray(); //LISTING USERDATA
        $this->set('users', $username);
    }

    //***********************************************************************************************//
    // * Function     :  edituser
    // * Parameter    :  $id
    // * Description  :  This function used to  Find All Users and Update  user entry
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function changepassword($id = NULL) {
        $query = $this->Users->getuservalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $users = $this->Users->getuserId($id); //LISTING USERDATA
        if ($this->request->is('post')) {
            $users = $this->Users->patchEntity($users, $this->request->data);
            $users->modified = date("Y-m-d H:i:s");
            $users->modified_by = $this->request->session()->read('Auth.User.id');
            if ($this->Users->save($users)) {
                $this->Flash->success(__('PASSWORD CHANGED SUCCESSFULLY'));
                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $username = $this->Users->getuserlisting()->toArray(); //LISTING USERDATA
        $this->set('users', $username);
    }

    //***********************************************************************************************//
    // * Function     :  deleteuser
    // * Parameter    :  $id
    // * Description  :  This function used to  Delete user entry
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function deleteuser($id = null) {
        $userid = $this->request->session()->read('Auth.User.id');
        $user = $this->Users->getuserId($this->request->data['value']); // GET USER DATA FROM ID
        if ($userid != $id) {
            if ($this->Users->delete($user)) {
                $this->Flash->success(Configure::read('Settings.DELETE'));
                $this->redirect(array('action' => 'index', '#' => 'scroll'));
                exit;
            } else {
                $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
            }
        } else {
            $this->Flash->error('THIS USER CAN NOT DELETE!');
        }
        return $this->redirect(['action' => 'index']);
    }

    //***********************************************************************************************//
    // * Function     :  addappusers
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function addappusers() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $user = $this->Users->newEntity();
        $requestArr = $this->getInputArr();
        //pr($requestArr); exit;
        $requiredFields = array(
            'Full name' => (isset($requestArr['full_name']) && $requestArr['full_name'] != '') ? $requestArr['full_name'] : '',
            'Email' => (isset($requestArr['email']) && $requestArr['email'] != '') ? $requestArr['email'] : '',
            'Phone no' => (isset($requestArr['phone_no']) && $requestArr['phone_no'] != '') ? $requestArr['phone_no'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        } else {
            $name = $requestArr['full_name'];
            $email = $requestArr['email'];
            $phone_no = $requestArr['phone_no'];
            if (trim($phone_no) != "" && strlen($phone_no) != 10) {
                $this->wrong('Phone number must be valid Mobile number');
            }
            $userExists = $this->Users->uniqueEmailOrPhone($email, $phone_no);
            if (isset($userExists['status']) && $userExists['status'] == 'fail') {
                $this->wrong($userExists['msg']);
            } else {
                $flagAff = false;
                $password = $this->randomPassword();
                $userData = [];
                $userData['name'] = $name;
                $userData['email'] = $email;
                $userData['phone_no'] = $phone_no;
                $userData['password'] = $password;
                $userData['city'] = (isset($requestArr['city']) && $requestArr['city'] != '') ? $requestArr['city'] : '';
                $userData['signup_with'] = 'SELF';
                $userData['user_type'] = 'CUSTOMER';
                $userData['profile_pic'] = '';
                $userData['ip_address'] = $this->get_client_ip();
                $userData['email_newsletters'] = (isset($requestArr['email_newsletters']) && $requestArr['email_newsletters'] != '') ? $requestArr['email_newsletters'] : 'N';
                $userData['device'] = json_encode($requestArr['device_detail']);
                $userData['phone_verified'] = 'N';
                $userData['email_verified'] = 'N';
                $userData['active'] = 'N';
                $userData['refer_key'] = $this->getReferKey($name, $phone_no);
                $userData['referral_id'] = 0;
                if (isset($requestArr['referral_code']) && $requestArr['referral_code'] != '') {
                    $affiliateUsers = $this->Users->find('all')->where(['refer_key' => $requestArr['referral_code']])->hydrate(false)->first();
                    if (isset($affiliateUsers) && !empty($affiliateUsers)) {
                        $flagAff = true;
                        $userData['referral_id'] = $vW['user_id'] = $affiliateUsers['id'];
                    }
                }
                $user = $this->Users->patchEntity($user, $userData);
                $user->created_by = 0;
                $user->modified_by = 0;
                $user->created = date("Y-m-d H:i:s");
                $user->modified = date("Y-m-d H:i:s");
                $otpT = rand(199999, 999999);
                // SEND OTP
                $msgT = "Dear $name, Your OTP code is $otpT, Further you needs to provide this code to OTP screen. Regards, H-Men";
                $sendMsg = $this->sendOtp($phone_no, $msgT);
                if ($sendMsg['status'] == 'fail') {
                    $this->wrong($sendMsg['msg']);
                }
                // SEND EMAIL
                $mailData = [];
                $mailData['name'] = $name;
                $senderEmail = str_replace("@", "<span>@</span>", $email);
                $senderEmail = str_replace(".", "<span>.</span>", $email);
                $mailData['email'] = $senderEmail;
                $mailData['password'] = $password;
                $mailData['activation_link'] = APP_PATH . '/webservices/email/activate/' . base64_encode($email);
                $this->set('mailData', $mailData);
                $view_output = $this->render('/Element/signup_self');
                //echo $view_output; exit;
                $fields = array(
                    'msg' => $view_output,
                    'tomail' => $email,
                    'subject' => 'Welcome To H-Men! Confirm Your Email',
                    'from_name' => EMAIL_FROM_NAME,
                    'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
                );
                $this->sendemails($fields);
                $saveUsers = $this->Users->save($user);
                if ($saveUsers) {
                    if ($flagAff == true) {
                        $this->loadModel('Wallets');
                        $vW['amount'] = REFERRAL_COMISSION;
                        $vW['wallet_type'] = 'CREDIT';
                        $vW['purpose'] = 'REFERRAL';
                        $vW['purpose_id'] = $saveUsers['id'];
                        $walletId = $this->addWalletAmount($vW['user_id'], $vW['amount'], $vW['wallet_type'], $vW['purpose'], $vW['purpose_id']);
                        if ($walletId) {
                            $this->newMsg($vW['user_id'], MSG_TITLE_REFERRAL, MSG_TYPE_REFERRAL, 'Rs. 100 Rewarded for referring to ' . $name);
                        }
                    }
                    //generate api key
                    $api_key = $this->Users->generateAPIkey();
                    $mappingData = [];
                    $this->loadModel('UserMapping');
                    $userMapping = $this->UserMapping->newEntity();
                    $map_data = array(
                        'user_id' => $saveUsers['id'],
                        'user_type' => 'CUSTOMER',
                        'mapping_key' => 'api_key',
                        'mapping_value' => $api_key
                    );
                    $userMapping = $this->UserMapping->patchEntity($userMapping, $map_data);
                    $userMapping->created = date("Y-m-d H:i:s");
                    $userMapping->modified = date("Y-m-d H:i:s");
                    if ($this->UserMapping->save($userMapping)) {
                        $this->loadModel('Otps');
                        $otp = $this->Otps->newEntity();
                        $dateTimes = date("Y-m-d H:i:s");
                        $otpData = array(
                            'user_id' => $saveUsers['id'],
                            'otp_number' => $otpT,
                        );
                        $otp = $this->Otps->patchEntity($otp, $otpData);
                        $otp->created = date("Y-m-d H:i:s");
                        $otp->expired = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($dateTimes)));
                        // USER SEND EMAIL CODE HERE
                        if ($this->Otps->save($otp)) {
                            $this->success('You are Signed up successfully, Please check your E-mail for Account credentials', ['otp' => $otpT, 'id' => $saveUsers['id']]);
                        } else {
                            $this->wrong('UNABLE TO ADD THE USER OTP DATA.');
                        }
                    } else {
                        $this->wrong('UNABLE TO ADD THE USER MAPPING.');
                    }
                } else {
                    $this->wrong('UNABLE TO ADD THE USER.');
                }
            }
        }
    }

    //***********************************************************************************************//
    // * Function     :  addappusers
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function appuserslogin() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $this->loadModel('Users');
        $this->loadModel('UserMapping');
        $user = $this->Users->newEntity();
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'Email Address' => (isset($requestArr['user']) && $requestArr['user'] != '') ? $requestArr['user'] : '',
            'Login Method' => (isset($requestArr['login_method']) && $requestArr['login_method'] != '') ? $requestArr['login_method'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        } else {
            $loginMethod = $requestArr['login_method'];
            if ($loginMethod == 'HMEN') {
                if (isset($requestArr['password']) && $requestArr['password'] != '') {
                    $password = $requestArr['password'];
                } else {
                    $this->wrong('Password is missing!');
                }
                $this->request->data = [];
                $this->request->data['email'] = $requestArr['user'];
                $this->request->data['password'] = $requestArr['password'];
                $user = $this->Auth->identify();
                //print_r($user); exit;
                if ($user) {
                    if (isset($user['active']) && $user['active'] == 'Y') {
                        if (isset($user['user_type']) && ($user['user_type'] == 'CUSTOMER' || $user['user_type'] == 'MEMBERSHIP')) {
                            $rslt = [];
                            $rslt['api_key'] = $this->Users->getApiKey($user['id']);
                            $rslt['id'] = $user['id'];
                            $rslt['name'] = $user['name'];
                            $rslt['email'] = $user['email'];
                            $rslt['phone_no'] = $user['phone_no'];
                            $rslt['profile_pic'] = ($user['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $user['profile_pic'] : IMAGE_URL_PATH . 'users/user.png';
                            $rslt['address'] = $user['address'];
                            $rslt['city'] = $user['city'];
                            $rslt['email_verified'] = $user['email_verified'];
                            $rslt['phone_verified'] = $user['phone_verified'];
                            $rslt['wallet_amount'] = $this->walletAmount($user['id']);
                            $rslt['referral'] = ($user['referral_id'] != 0) ? 'YES' : 'NO';
                            $users = $this->Users->get($user['id']);
                            $users->last_login = date('Y-m-d H:i:s');
                            $this->Users->save($users);
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
            } elseif ($loginMethod == 'TP') {
                // Check Account is Exist Or Not
                $conditionArr = [];
                $conditionArr['active'] = 'Y';
                $conditionArr['user_type'] = 'CUSTOMER';
                $emailAddress = (isset($requestArr['user']) && $requestArr['user'] != '') ? $requestArr['user'] : '';
                $tpa_token = (isset($requestArr['token']) && $requestArr['token'] != '') ? $requestArr['token'] : '';
                $phoneNumber = (isset($requestArr['phone_no']) && $requestArr['phone_no'] != '') ? $requestArr['phone_no'] : '';
                if ($emailAddress != '' && $phoneNumber != '') {
                    $conditionArr['OR'] = ['email' => $emailAddress, 'phone_no' => $phoneNumber, 'tpa_token' => $tpa_token];
                } else {
                    $conditionArr['email'] = $emailAddress;
                    $conditionArr['tpa_token'] = $tpa_token;
                }
                $userExist = '';
                $userExist = $this->Users->find('all')->select(['id'])->where($conditionArr)->hydrate(false)->first();
                if ($userExist) {
                    $userId = $userExist['id'];
                    $userDetails = $this->Users->get($userId);
                    if (!empty($userDetails)) {
                        if (isset($userDetails['active']) && $userDetails['active'] == 'Y') {
                            if (isset($userDetails['user_type']) && $userDetails['user_type'] == 'CUSTOMER') {
                                $rslt = [];
                                $rslt['api_key'] = $this->Users->getApiKey($userDetails['id']);
                                $rslt['id'] = $userDetails['id'];
                                $rslt['name'] = $userDetails['name'];
                                $rslt['email'] = $userDetails['email'];
                                $rslt['phone_no'] = $userDetails['phone_no'];
                                $rslt['profile_pic'] = ($userDetails['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $userDetails['profile_pic'] : IMAGE_URL_PATH . 'users/user.png';
                                $rslt['address'] = $userDetails['address'];
                                $rslt['city'] = $userDetails['city'];
                                $rslt['email_verified'] = $userDetails['email_verified'];
                                $rslt['phone_verified'] = $userDetails['phone_verified'];
                                $rslt['wallet_amount'] = $this->walletAmount($userDetails['id']);
                                $rslt['referral'] = ($userDetails['referral_id'] != 0) ? 'YES' : 'NO';
                                $users = $this->Users->get($userDetails['id']);
                                $users->last_login = date('Y-m-d H:i:s');
                                $this->Users->save($users);
                                $this->success('LOGIN!', $rslt);
                            } else {
                                $this->wrong('Sorry, Your account is not exists!');
                            }
                        } else {
                            $this->wrong('Sorry, Your account is not activated!');
                        }
                    } else {
                        $this->wrong('User Details not found!');
                    }
                } else {
                    $profile_pic = '';
                    $profile_picture = isset($requestArr['tpa_image']) && $requestArr['tpa_image'] != '' ? $requestArr['tpa_image'] : '';
                    if ($profile_picture != '') {
                        $url = $profile_picture;
                        $profile_pic = date('Ymdhis') . rand(11, 99) . '.jpg';
                        $img = WWW_ROOT . 'img/users/' . $profile_pic;
                        file_put_contents($img, file_get_contents($url));
                    }
                    $password = $this->randomPassword();
                    $userData = [];
                    $userData['name'] = $requestArr['full_name'];
                    $userData['email'] = $emailAddress;
                    $userData['phone_no'] = $phoneNumber;
                    $userData['password'] = $password;
                    $userData['tpa_token'] = $tpa_token;
                    $userData['city'] = (isset($requestArr['city']) && $requestArr['city'] != '') ? $requestArr['city'] : '';
                    $userData['signup_with'] = $requestArr['signup_tag'];
                    $userData['user_type'] = 'CUSTOMER';
                    $userData['profile_pic'] = $profile_pic;
                    $userData['ip_address'] = $this->get_client_ip();
                    $userData['email_newsletters'] = (isset($requestArr['email_newsletters']) && $requestArr['email_newsletters'] != '') ? $requestArr['email_newsletters'] : 'N';
                    $userData['device'] = json_encode($requestArr['device_detail']);
                    $userData['phone_verified'] = 'N';
                    $userData['email_verified'] = 'N';
                    $userData['active'] = 'Y';
                    $userData['refer_key'] = $this->getReferKey($requestArr['full_name'], $phoneNumber);
                    $userData['tmplog'] = json_encode($requestArr);
                    $user = $this->Users->patchEntity($user, $userData);
                    $user->created = date("Y-m-d H:i:s");
                    if ($emailAddress != "") {
                        $user->email_verified = 'Y';
                    }
                    if ($phoneNumber != "") {
                        $user->phone_verified = 'Y';
                    }
                    // SEND EMAIL
                    $mailData = [];
                    $mailData['name'] = $requestArr['full_name'];
                    $senderEmail = str_replace("@", "<span>@</span>", $emailAddress);
                    $senderEmail = str_replace(".", "<span>.</span>", $senderEmail);
                    $mailData['email'] = $senderEmail;
                    $mailData['password'] = $password;
                    //$mailData['activation_link'] = APP_PATH . '/webservices/email/activate/' . base64_encode($emailAddress);
                    $this->set('mailData', $mailData);
                    $view_output = $this->render('/Element/signup_tpa');
                    $fields = array(
                        'msg' => $view_output,
                        'tomail' => $emailAddress,
                        'subject' => 'Welcome To H-Men!',
                        'from_name' => EMAIL_FROM_NAME,
                        'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
                    );
                    $this->sendemails($fields);
                    $saveUsers = $this->Users->save($user);
                    //print_r($saveUsers); exit;
                    if ($saveUsers) {
                        //generate api key
                        $api_key = $this->Users->generateAPIkey();
                        $mappingData = [];
                        $userMapping = $this->UserMapping->newEntity();
                        $map_data = array(
                            'user_id' => $saveUsers['id'],
                            'user_type' => 'CUSTOMER',
                            'mapping_key' => 'api_key',
                            'mapping_value' => $api_key
                        );
                        $userMapping = $this->UserMapping->patchEntity($userMapping, $map_data);
                        $userMapping->created = date("Y-m-d H:i:s");
                        $userMapping->modified = date("Y-m-d H:i:s");
                        //pr($userMapping); exit; modified
                        if ($this->UserMapping->save($userMapping)) {
                            $userDetails = $this->Users->get($saveUsers['id']);
                            if (!empty($userDetails)) {
                                if (isset($userDetails['active']) && $userDetails['active'] == 'Y') {
                                    if (isset($userDetails['user_type']) && $userDetails['user_type'] == 'CUSTOMER') {
                                        $rslt = [];
                                        $rslt['api_key'] = $this->Users->getApiKey($userDetails['id']);
                                        $rslt['id'] = $userDetails['id'];
                                        $rslt['name'] = $userDetails['name'];
                                        $rslt['email'] = $userDetails['email'];
                                        $rslt['phone_no'] = $userDetails['phone_no'];
                                        $rslt['profile_pic'] = ($userDetails['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $userDetails['profile_pic'] : IMAGE_URL_PATH . 'users/user.png';
                                        $rslt['address'] = $userDetails['address'];
                                        $rslt['city'] = $userDetails['city'];
                                        $rslt['email_verified'] = $userDetails['email_verified'];
                                        $rslt['phone_verified'] = $userDetails['phone_verified'];
                                        $rslt['wallet_amount'] = 0;
                                        $this->success('LOGIN!', $rslt);
                                    } else {
                                        $this->wrong('Sorry, Your account is not exists!');
                                    }
                                } else {
                                    $this->wrong('Sorry, Your account is not activated!');
                                }
                            } else {
                                $this->wrong('User Details not found!');
                            }
                        } else {
                            $this->wrong('UNABLE TO ADD THE USER MAPPING.');
                        }
                    } else {
                        $this->wrong('UNABLE TO ADD THE USER.');
                    }
                }
            } else {
                $this->wrong('Invalid login method!');
            }
        }
    }

    //***********************************************************************************************//
    // * Function     :  addappusers
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function randomPassword() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < 10; $i++) {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    //***********************************************************************************************//
    // * Function     :  addappusers
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function verifiedemail($key) {
        $this->layout = 'resetpasswords';
        $this->loadModel('Users');
        $email = (isset($key) && $key != '') ? base64_decode($key) : '';
        $requiredFields = array(
            'Activation Key' => $email
        );
        $status = '';
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            //$this->wrong($validate);
            $this->Flash->error($validate);
            $status = 'error';
        } else {
            $userExist = $this->Users->find()->select(['id'])->where(['email' => $email, 'user_type' => 'CUSTOMER'])->hydrate(false)->first();
            if (isset($userExist['id']) && $userExist['id'] != '') {
                $userId = $userExist['id'];
                $userData = $this->Users->getuserId($userId);
                $updateData = ['active' => 'Y', 'email_verified' => 'Y'];
                $users = $this->Users->patchEntity($userData, $updateData);
                $users->modified = date("Y-m-d H:i:s");
                $users->modified_by = $userId;
                if ($this->Users->save($users)) {
                    //$this->success('Your email address is verified!');
                    $this->set('msg', 'Your email address is verified!');
                    $status = 'success';
                    $this->Flash->success('Your email address is verified!');
                } else {
                    $status = 'error';
//                    $this->wrong(Configure::read('Settings.FAIL'));
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $status = 'error';
                //$this->wrong('Sorry, Userdata is not found!');
                $this->Flash->error('Sorry, Userdata is not found!');
            }
        }
        $this->set('status', $status);
    }

    //***********************************************************************************************//
    // * Function     :  addappusers
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function verifiedPhone() {
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'User Id' => (isset($requestArr['user_id']) && $requestArr['user_id'] != '') ? $requestArr['user_id'] : '',
            'OTP' => (isset($requestArr['otp']) && $requestArr['otp'] != '') ? $requestArr['otp'] : '',
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        } else {
            $userId = $requestArr['user_id'];
            $otp = $requestArr['otp'];
            $userExist = $this->Users->find()->select(['id'])->where(['id' => $userId, 'user_type' => 'CUSTOMER'])->hydrate(false)->first();
            if (isset($userExist['id']) && $userExist['id'] != '') {
                $otpVerifiedResponse = $this->Users->isOtpTrue($userId, $otp);
                if (isset($otpVerifiedResponse['status']) && $otpVerifiedResponse['status'] == 'success') {
                    $userId = $userExist['id'];
                    $userData = $this->Users->getuserId($userId);
                    $updateData = ['active' => 'Y', 'phone_verified' => 'Y'];
                    $users = $this->Users->patchEntity($userData, $updateData);
                    $users->modified = date("Y-m-d H:i:s");
                    $users->modified_by = $userId;
                    if ($this->Users->save($users)) {
                        $this->loadModel('Otps');
                        $this->Otps->deleteAll(['user_id' => $userId]);
                        $this->success('Your phone number is verified!');
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->wrong($otpVerifiedResponse['msg']);
                }
            } else {
                $this->wrong('Sorry, Userdata is not found!');
            }
        }
    }

    //***********************************************************************************************//
    // * Function     :  resendOtp
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function resendOtp() {
        $this->loadModel('Otps');
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'User Id' => (isset($requestArr['user_id']) && $requestArr['user_id'] != '') ? $requestArr['user_id'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        }
        $userArr = $this->Users->get($requestArr['user_id']);
        if (!empty($userArr)) {
            $already_verified = $userArr['phone_verified'];
            if ($already_verified == 'Y') {
                $this->success('Mobile number already verified');
            } else {
                $name = $userArr['name'];
                $otpT = rand(199999, 999999);
                $phone_no = $userArr['phone_no'];
                // SEND OTP
                $msgT = "Dear $name, Your OTP code is $otpT, Further you needs to provide this code to OTP screen. Regards, H-Men";
                $sendMsg = $this->sendOtp($phone_no, $msgT);
                if ($sendMsg['status'] == 'fail') {
                    $this->wrong($sendMsg['msg']);
                }
                $this->Otps->deleteAll(['user_id' => $userArr['id']]);
                $otp = $this->Otps->newEntity();
                $dateTimes = date("Y-m-d H:i:s");
                $otpData = array(
                    'user_id' => $userArr['id'],
                    'otp_number' => $otpT,
                );
                $otp = $this->Otps->patchEntity($otp, $otpData);
                $otp->created = date("Y-m-d H:i:s");
                $otp->expired = date('Y-m-d H:i:s', strtotime("+15 minutes", strtotime($dateTimes)));
                // USER SEND EMAIL CODE HERE
                if ($this->Otps->save($otp)) {
                    $this->success('Otp resend successfully', ['otp' => $otpT]);
                } else {
                    $this->wrong('UNABLE TO ADD THE USER OTP DATA.');
                }
            }
        } else {
            $this->wrong('Sorry, Userdata is not found!');
        }
    }

    //***********************************************************************************************//
    // * Function     :  resendActivationLinks
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//


    public function resendActivationLinks() {
        $this->layout = 'ajax';
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'Email Id' => (isset($requestArr['email']) && $requestArr['email'] != '') ? $requestArr['email'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        }
        $userArr = $this->Users->find('all')->where(['email' => $requestArr['email']])->hydrate(false)->first();
        if (!empty($userArr)) {
            $already_verified = $userArr['email_verified'];
            if ($already_verified == 'Y') {
                $this->success('Email Address is already verified');
            } else {
                $mailData = [];
                //$mailData['name'] = $userArr['name'];
                //$mailData['email'] = $userArr['email'];
                $mailData['activation_link'] = APP_PATH . 'webservices/email/activate/' . base64_encode($userArr['email']);
                $this->set('mailData', $mailData);
                $view_output = $this->render('/Element/signup_self');
                //pr($view_output); exit;
                $fields = array(
                    'msg' => $view_output,
                    'tomail' => 'chiragce1992@gmail.com',
                    'subject' => 'Resend Activations Links',
                    'from_name' => EMAIL_FROM_NAME,
                    'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
                );
                //pr($fields); exit;
                if ($rslt = $this->sendemails($fields)) {
                    $this->success('Mail Send!');
                } else {
                    $this->wrong('Sorry, Something wrong!');
                }
            }
        } else {
            $this->wrong('Sorry, Userdata is not found!');
        }
    }

    //***********************************************************************************************//
    // * Function     :  updateProfile
    // * Parameter    :  $id
    // * Description  :  This function used to  make excel of list Users
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function updateProfile() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $query = $this->Users->getuservalidationID($user_id); //LISTING USERDATA
            //pr($query); exit;
            if ($query->isEmpty()) {
                $this->wrong(__('RECORD DOES NOT EXIST'));
            }
            $user = $this->Users->getuserId($user_id); //LISTING USERDATA
//            pr($user); exit;
            $imageFlag = 0;
            $requestArr = $this->getInputArr();
            if (isset($requestArr['image']) && $requestArr['image'] != '') {
                $data = $requestArr['image'];
                $uri = substr($data, strpos($data, ",") + 1);
                $profile_pic = date('Ymdhis') . rand(11, 99) . '.jpg';
                $img = WWW_ROOT . 'img/users/' . $profile_pic;
                file_put_contents($img, base64_decode($uri));
                unset($requestArr['image']);
                $requestArr['profile_pic'] = $profile_pic;
                $imageFlag = 1;
            }
            if (isset($requestArr['referral_code']) && $requestArr['referral_code'] != '') {
                $affiliateUsers = $this->Users->find('all')->where(['refer_key' => $requestArr['referral_code']])->hydrate(false)->first();
                //pr($affiliateUsers); exit;
                if (isset($affiliateUsers) && !empty($affiliateUsers)) {
                    $flagAff = true;
                    $requestArr['referral_id'] = $affiliateUsers['id'];
                } else {
                    $this->wrong("Sorry, Referral code is invalid!");
                }
                unset($requestArr['referral_code']);
            }
            $user = $this->Users->patchEntity($user, $requestArr);
            $user->modified_by = $this->request->session()->read('Auth.User.id');
            $user->modified = date("Y-m-d H:i:s");
            if ($this->Users->save($user)) {
                $users = $this->Users->getuserId($user_id); //LISTING USERDATA
                $rslt = [];
                if ($imageFlag == 1) {
                    $rslt['profile_pic'] = ($users['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $users['profile_pic'] : IMAGE_URL_PATH . 'users/user.png';
                }
                $this->success(Configure::read('Settings.SAVE'), $rslt);
            } else {
                $this->wrong(Configure::read('Settings.FAIL'));
            }
        } else {
            $this->wrong('Invalid API key!');
        }
    }

    public function resetpasswords($token) {
        $this->request->session()->destroy('Flash');
        $this->layout = 'resetpasswords';
        if (isset($token) && $token != '') {
            $email = base64_decode($token);
            $userArr = $this->Users->find('all')->where(['email' => $email])->hydrate(false)->first();
            if (isset($userArr) && !empty($userArr)) {
                $users = $this->Users->get($userArr['id']);
                if ($this->request->is('post')) {
                    //pr($this->request->data); exit;
                    $users = $this->Users->patchEntity($users, $this->request->data);
                    $users->modified = date("Y-m-d H:i:s");
                    $users->modified_by = $this->request->session()->read('Auth.User.id');
                    if ($this->Users->save($users)) {
                        $this->Flash->success(__('Password reset successfully!'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                        return $this->redirect(['action' => 'resetpasswords', $token]);
                    }
                }
            } else {
                $this->Flash->error('Sorry, Userdata not found!');
            }
        } else {
            $this->Flash->error('Sorry, Token is missing!');
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
