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

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;

header('Access-Control-Allow-Origin: *');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     */
    public $components = array(
        //'Session',
        'Auth',
        'Cookie'
    );

    public function initialize() {
        parent::initialize();
        $this->loadComponent('Flash');

        $this->loadComponent('Auth', [
            'loginRedirect' => [
                'controller' => 'Index',
                'action' => 'dashboard'
            ],
            'loginaction' => [
                'controller' => 'Users',
                'action' => 'login'
            ],
            'logoutRedirect' => [
                'controller' => 'Users',
                'action' => 'login',
            ],
            'authenticate' => [
                'Form' => [
                    'fields' => ['username' => 'email', 'password' => 'password'],
                ],
            ]
        ]);
    }

    public function getInputArr() {
        if (file_get_contents('php://input') != '') {
            $requestData = get_object_vars(json_decode(file_get_contents('php://input')));
            if (isset($requestData) && !empty($requestData)) {
                return $requestData;
            } else {
                AppController::wrong('No inputs found for login user');
            }
        } else {
            AppController::wrong('No inputs found for login user');
        }
    }

    public function checkRequiredFields($arr) {
        $str = "";
        if (!empty($arr)) {
            foreach ($arr as $key => $value) {
                if (isset($key) && $value == "") {
                    $msgs_arr[] = ucfirst(str_replace("_", " ", $key)) . " is required";
                }
            }
        }
        if (!empty($msgs_arr)) {
            $str = implode(", ", $msgs_arr) . ".";
        }
        return $str;
    }

    public function wrong($input) {
        $message = "";
        if (is_string($input)) {
            $message = $input;
        }
        if (is_array($input) && !empty($input)) {
            $message = implode(", ", $input);
        }
        echo json_encode(['status' => 'fail', 'msg' => $message]);
        exit;
    }

    public function success($message = "", $data = array()) {
        echo json_encode(['status' => 'success', 'msg' => $message, 'data' => $data]);
        exit;
    }

    // Function to get the client IP address
    public function get_client_ip() {
        $ipaddress = '';
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public function sendOtp($phonenumber = '', $msg = '') {
        $rslt = [];
        if ($msg == '') {
            $rslt = ['status' => 'fail', 'msg' => 'Sorry Msg is not found!'];
        }
        if ($phonenumber == '') {
            $rslt = ['status' => 'fail', 'msg' => 'Sorry Phonenumber is not found!'];
        }
        //$msg = "Dear $name, Your OTP code is $otp, Further you needs to provide this code to OTP screen. Regards, H-Men";
        $fields = array(
            'mobile' => '7096460460',
            'pass' => 'hmen@1234',
            'senderid' => 'HMENah',
            'to' => $phonenumber,
            'msg' => $msg
        );
        //print_r($rslt); exit;
        $handle = curl_init('http://t.saurustechnology.com/SmsStatuswithId.aspx');
        curl_setopt($handle, CURLOPT_POST, true);
        curl_setopt($handle, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($handle, CURLOPT_RETURNTRANSFER, TRUE);
        curl_exec($handle);
        if (curl_error($handle)) {
            $msgError = "cURL Error #:" . $err;
            $rslt = ['status' => 'fail', 'msg' => $msgError];
        } else {
            $rslt = ['status' => 'success', 'msg' => 'Send!'];
        }
        //print_r($rslt); exit;
        //curl_close($handle);
        return $rslt;
    }

    //***********************************************************************************************//
    // * Function     :  sendemails
    // * Parameter    :  
    // * Description  :  THIS FUNCTION IS USED TO SEND THE EMAILS THROUGH SENDGRID
    // * Author       :  Namrata Dubey
    // * Date         :  17-JUN-2017
    //***********************************************************************************************//

    public function sendemails($fields = NULL) {
        $fields_string = '';
        foreach ($fields as $key => $value) {
            $fields_string .= $key . '=' . $value . '&';
        }
        $fields_string = rtrim($fields_string, '&');
        $url = APP_PATH . '/sendgrid/sendmail.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        curl_close($ch);
    }

    public function getAPIKey() {
        $requestArr = $this->getInputArr();
        return trim($requestArr['api_key']);
    }

    public function checkVerifyApiKey($user_type) {
        $this->loadModel('UserMapping');
        $api_key = $this->getAPIKey();
        $conditionArr = ['user_type' => $user_type, 'mapping_key' => 'api_key', 'mapping_value' => $api_key];
        $userMapping = $this->UserMapping->find('all')->where($conditionArr)->hydrate(false)->first();
        if (!empty($userMapping)) {
            return $userMapping['user_id'];
        } else {
            $this->wrong('Invalid API Key!');
        }
    }

    public function getCategoryId($service_id) {
        $serviceTable = TableRegistry::get('Services');
        $categories = $serviceTable->find()->select(['category_id'])->where(['id' => $service_id])->hydrate(false)->first();
        return $categories['category_id'];
    }

    public function getServiceName($service_id) {
        $serviceTable = TableRegistry::get('Services');
        $categories = $serviceTable->find()->select(['service_name'])->where(['id' => $service_id])->hydrate(false)->first();
        return (isset($categories['service_name']) && $categories['service_name'] != '') ? $categories['service_name'] : '-';
    }

    public function getCategoryName($category_id) {
        $serviceCategoryTable = TableRegistry::get('ServiceCategory');
        $categories = $serviceCategoryTable->find()->select(['name'])->where(['id' => $category_id])->hydrate(false)->first();
        return (isset($categories['name']) && $categories['name'] != '') ? $categories['name'] : '-';
    }

    public function orderIdCreate() {
        $orderTable = TableRegistry::get('Orders');
        $tmpId = date('YmdHis') . rand(1111, 9999);
        $orderIdExist = $orderTable->find()->select(['id'])->where(['order_id' => $tmpId])->hydrate(false)->first();
        if ($orderIdExist) {
            $this->orderIdCreate();
        } else {
            return $tmpId;
        }
    }

    public function newMsg($user_id, $title, $msgtype, $details) {
        $msgTable = TableRegistry::get('Messages');
        $msg = $msgTable->newEntity();
        $msgArr['user_id'] = $user_id;
        $msgArr['message_title'] = $title;
        $msgArr['msg_type'] = $msgtype;
        $msgArr['message_detail'] = $details;
        $msgArr['seen'] = 'N';
        $msgArr['created_by'] = $user_id;
        $msgArr['modified_by'] = $user_id;
        $msg = $msgTable->patchEntity($msg, $msgArr);
        $msg->created_at = date('Y-m-d H:i:s');
        $msg->modified_at = date('Y-m-d H:i:s');
        if ($msgTable->save($msg)) {
            return true;
        } else {
            return false;
        }
    }

    public function addWalletAmount($user_id, $amount, $wallet_type, $purpose, $purpose_id) {
        $walletTable = TableRegistry::get('Wallets');
        $wallet = $walletTable->newEntity();
        $walletArr['user_id'] = $user_id;
        $walletArr['amount'] = $amount;
        $walletArr['wallet_type'] = $wallet_type;
        $walletArr['purpose'] = $purpose;
        $walletArr['purpose_id'] = $purpose_id;
        $wallet = $walletTable->patchEntity($wallet, $walletArr);
        $wallet->created = date('Y-m-d H:i:s');
        $wallet->modified = date('Y-m-d H:i:s');
        if ($walletSave = $walletTable->save($wallet)) {
            return $walletSave['id'];
        } else {
            return false;
        }
    }

    public function walletAmount($userId) {
        $this->loadModel('Wallets');
        $result = [];
        $credits = $debits = 0;
        $totCredit = $this->Wallets->find('all')->select(["credits" => "SUM(amount)"])->where(['user_id' => $userId, 'wallet_type' => 'CREDIT'])->hydrate(false)->first();
        $credits = (isset($totCredit['credits']) && $totCredit['credits'] != '') ? $totCredit['credits'] : 0.00;
        $totDebit = $this->Wallets->find('all')->select(["debits" => "SUM(amount)"])->where(['user_id' => $userId, 'wallet_type' => 'DEBIT'])->hydrate(false)->first();
        $debits = (isset($totDebit['debits']) && $totDebit['debits'] != '') ? $totDebit['debits'] : 0.00;
        $balance = $credits - $debits;
        return $balance;
    }

    public function getUserName($userId) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find()->select(['name'])->where(['id' => $userId])->hydrate(false)->first();
        return $user['name'];
    }

    public function getEmail($userId) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find()->select(['email'])->where(['id' => $userId])->hydrate(false)->first();
        return $user['email'];
    }

    public function getPhone($userId) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find()->select(['phone_no'])->where(['id' => $userId])->hydrate(false)->first();
        return (isset($user['phone_no']) && ($user['phone_no'] != '')) ? $user['phone_no'] : '-';
    }

    public function getOrderId($id) {
        $orderTable = TableRegistry::get('Orders');
        $order = $orderTable->find()->select(['order_id'])->where(['id' => $id])->hydrate(false)->first();
        return $order['order_id'];
    }

    public function sendMessage($title, $msg, $url = '', $user = 'All') {
        $headings = array(
            "en" => $title
        );
        $content = array(
            "en" => $msg
        );
        $tmpUser = '';
        $fields = array(
            'app_id' => "a3b50701-b4be-4f79-a69b-55e5505347f4",
            'data' => array("foo" => "bar"),
            'contents' => $content
        );
        if(isset($user) && $user != '' && $user != 'All') {
            $fields['include_player_ids'] = array($user);
        } else {
            $fields['included_segments'] = array('All');
        }
        $fields = json_encode($fields);
        print("\nJSON sent:\n");
        print($fields);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic YmRmM2YwOTYtZGIxZS00ZmQ5LWIxNGMtM2M0MjBiNjI5OTZh'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

}
