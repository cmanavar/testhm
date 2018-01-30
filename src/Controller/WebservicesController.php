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
use Cake\Auth\DefaultPasswordHasher;
use Cake\Event\Event;
use Cake\I18n\Date;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : ServicecategoryController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class WebservicesController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
        $this->Auth->allow(['homepage', 'categoryDetails', 'categoryList', 'serviceDetails', 'getServicesSubQuestions', 'helpDetails',
            'createCart', 'addCartProduct', 'cartDetails', 'cartClear', 'removeCartProduct', 'counteunreadmsg', 'msgList', 'msgView',
            'cartOrderPlaced', 'forgorPassword', 'changePassword', 'applyCouponCode', 'walletDetails', 'getCartId', 'orderDetails',
            'orderLists', 'orderQuery', 'orderSummary', 'storeReview', 'updateOrder', 'serviceReviews', 'getquestionArr', 'surverysubmit', 'surverylists',
            'serviceLists', 'addMembership', 'planLists', 'referenceUsers', 'listMembership', 'appoinmentLists', 'appoinmentDetails',
            'appoinmentCompleted', 'appoinmentDeclined', 'testNotifications']);
    }

    public function counteunreadmsg() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Messages');
            $counts = $this->Messages->find('all')->where(['user_id' => $user_id, 'seen' => 'N'])->hydrate(false)->count();
            $this->success('Messages fetched successfully', ['unseen_count' => $counts]);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function msgList() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $requestArr = $this->getInputArr();
            if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
                $page_no = $requestArr['page_no'];
            } else {
                $page_no = 1;
            }
            $this->loadModel('Messages');
            $unseenCount = 0;
            $unseenCount = $this->Messages->find('all')->where(['user_id' => $user_id, 'seen' => 'N'])->hydrate(false)->count();
            $msgList = $this->Messages->find('all')->select(['id', 'user_id', 'message_title', 'msg_type', 'message_detail', 'seen', 'created_at', 'modified_at'])->where(['user_id' => $user_id])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            if (isset($msgList) && !empty($msgList)) {
                $msg = [];
                foreach ($msgList as $key => $message) {
                    $tmp = [];
                    $tmp['id'] = $message['id'];
                    $tmp['user_id'] = $message['user_id'];
                    $tmp['message_title'] = $message['message_title'];
                    $tmp['type'] = $message['msg_type'];
                    if ($message['msg_type'] == 'OFFER') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-offer.png';
                    } else if ($message['msg_type'] == 'REFERRAL') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-referral.png';
                    } else if ($message['msg_type'] == 'CASHBACK') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-cashback.png';
                    } else {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-other.png';
                    }
                    $tmp['message_detail'] = $message['message_detail'];
                    $tmp['seen'] = $message['seen'];
                    $tmp['date'] = $message['created_at']->format('d-M-Y');
                    $tmp['datetime'] = $message['created_at']->format('d-M-Y h:i A');
                    $msg[] = $tmp;
                }
                $nextPageReviews = $this->Messages->find('all')->where(['user_id' => $user_id])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
                $next_page = (!empty($nextPageReviews)) ? true : false;
                $resp_data = ['unseen_count' => $unseenCount, 'messages' => $msg, 'next_page' => $next_page];
                $this->success('Messages fetched successfully.', $resp_data);
            } else {
                $resp_data = ['unseen_count' => $unseen_count, 'messages' => $msgList];
                $this->success('Messages fetched successfully.', $resp_data);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function msgView() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Messages');
            $requestArr = $this->getInputArr();
            //pr($requestArr); exit;
            if (isset($requestArr['message_ids']) && !empty($requestArr['message_ids'])) {
                $msgIdArr = $requestArr['message_ids'];
                $msgarr = [];
                foreach ($msgIdArr as $id) {
                    $msg = [];
                    $msg = $this->Messages->get($id); //LISTING USERDATA
                    //pr($msg); exit;
                    $updateFields = ['seen' => 'Y'];
                    $msg = $this->Messages->patchEntity($msg, $updateFields);
                    $msg->modified_by = $user_id;
                    $msg->modified = date("Y-m-d H:i:s");
                    if ($this->Messages->save($msg)) {
                        
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                    //pr($msg); exit;
                }
                $this->success('Messages seen successfully.');
            } else {
                $this->wrong('Sorry, Message id is missing!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function forgorPassword() {
        $this->layout = 'ajax';
        $this->autoRender = false;
        $this->loadModel('Users');
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
            $mailData = [];
            $mailData['name'] = $userArr['name'];
            $mailData['email'] = $userArr['email'];
            $mailData['activation_link'] = APP_PATH . 'reset/password/' . base64_encode($userArr['email']);
            $this->set('mailData', $mailData);
            $view_output = $this->render('/Element/forgot_pass_email');
            $fields = array(
                'msg' => $view_output,
                'tomail' => 'chiragce1992@gmail.com',
                'subject' => 'Reset Password',
                'from_name' => EMAIL_FROM_NAME,
                'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
            );
            //if ($this->sendemails($fields)) {
            $this->success('Mail Send!');
//            } else {
//                $this->wrong('Sorry, Something wrong!');
//            }
        } else {
            $this->wrong('Sorry, Userdata is not found!');
        }
    }

    public function changePassword() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Users');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Old Password' => (isset($requestArr['old_password']) && $requestArr['old_password'] != '') ? $requestArr['old_password'] : '',
                'New Password' => (isset($requestArr['new_password']) && $requestArr['new_password'] != '') ? $requestArr['new_password'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $user = $this->Users->get($user_id);
            if (!empty($user)) {
                $password = $user->password;
                $obj = new DefaultPasswordHasher;
                $postpassword = $obj->check($requestArr['old_password'], $password);
                if ($postpassword) {
                    $updatedRecords = ['password' => $requestArr['new_password']];
                    $user = $this->Users->patchEntity($user, $updatedRecords);
                    $user->modified = date('Y-m-d H:i:s');
                    $user->modified_by = $user_id;
                    if ($this->Users->save($user)) {
                        $this->wrong('Your Password is updated successfully!');
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->wrong('Sorry, Please enter valid old password!');
                }
            } else {
                $this->wrong('Sorry, Userdata is not found!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    //***********************************************************************************************//
    // * Function     :  homepage
    // * Parameter    :  
    // * Description  :  This function used to app home page data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function homepage() {
        $this->loadModel('Banners');
        $this->loadModel('ServiceCategory');
        $this->loadModel('Services');
        $rsltArr = [];
        // Get Banner Images - Start
        $bannerImages = [];
        $images = $this->Banners->find('all')->select(['banner_images'])->where(['status' => 'ACTIVE'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($images as $val) {
            if (isset($val['banner_images']) && $val['banner_images'] != '') {
                $bannerImages[] = IMAGE_URL_PATH . 'banners/' . $val['banner_images'];
            }
        }
        // Get Banner Images - End
        // Get Category Icon - Start
        $categoryIcon = [];
        $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'icon_image'])->where(['status' => 'ACTIVE', 'display_app' => 'YES'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($category as $val) {
            $tmp = [];
            if (isset($val) && !empty($val)) {
                $tmp['id'] = $val['id'];
                $tmp['name'] = $val['name'];
                $tmp['icon_image'] = IMAGE_URL_PATH . 'categories/icon/' . $val['icon_image'];
                $categoryIcon[] = $tmp;
            }
        }
        // Get Category Icon - End
        // Get Service Details - Start
        $serviceDetails = [];
        $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'banner_image'])->where(['status' => 'ACTIVE', 'display_app' => 'YES'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($category as $val) {
            $tmp = $tmpS = [];
            if (isset($val) && !empty($val)) {
                $tServices = $this->Services->find('all')->select(['id', 'service_name', 'service_description', 'service_specification', 'visit_charge', 'minimum_charge', 'banner_image'])->where(['status' => 'ACTIVE', 'category_id' => $val['id']])->order(['id' => 'ASC'])->hydrate(false)->toArray();
                if (!empty($tServices)) {
                    $tmp['category_id'] = $val['id'];
                    $tmp['category_name'] = $val['name'];
                    $tmp['banner_image'] = IMAGE_URL_PATH . 'categories/icon/' . $val['banner_image'];
                    foreach ($tServices as $v) {
                        $tmpS['service_id'] = $v['id'];
                        $tmpS['service_name'] = $v['service_name'];
                        $tmpS['service_description'] = $v['service_description'];
                        $tmpS['visit_charge'] = $v['visit_charge'];
                        $tmpS['minimum_charge'] = $v['minimum_charge'];
                        $tmpS['banner_image'] = IMAGE_URL_PATH . 'services/banner/' . $v['banner_image'];
                        $tmp['services'][] = $tmpS;
                    }
                    $serviceDetails[] = $tmp;
                }
            }
        }
        // Get Service Details - End
        $rsltArr['banner_images'] = $bannerImages;
        $rsltArr['categories_icon'] = $categoryIcon;
        $rsltArr['services'] = $serviceDetails;
        $this->success('Homepage Data Fateched!', $rsltArr);
    }

    //***********************************************************************************************//
    // * Function     :  homepage
    // * Parameter    :  
    // * Description  :  This function used to app home page data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function categoryDetails($id = '') {
        if (isset($id) && $id != '') {
            $this->loadModel('ServiceCategory');
            $this->loadModel('Services');
            $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'banner_image'])->where(['id' => $id, 'status' => 'ACTIVE'])->order(['order_id' => 'ASC'])->hydrate(false)->first();
            if (isset($category) && !empty($category)) {
                $tmp = $tmpS = [];
                $tServices = $this->Services->find('all')->select(['id', 'service_name', 'service_description', 'service_specification', 'visit_charge', 'minimum_charge', 'banner_image'])->where(['status' => 'ACTIVE', 'category_id' => $id])->order(['id' => 'ASC'])->hydrate(false)->toArray();
                $rslt['id'] = $category['id'];
                $rslt['name'] = $category['name'];
                $rslt['banner_image'] = IMAGE_URL_PATH . 'categories/banner/' . $category['banner_image'];
                foreach ($tServices as $v) {
                    $tmp = [];
                    $tmp['service_id'] = $v['id'];
                    $tmp['service_name'] = $v['service_name'];
                    $tmp['service_description'] = $v['service_description'];
                    $tmp['visit_charge'] = $v['visit_charge'];
                    $tmp['minimum_charge'] = $v['minimum_charge'];
                    $tmp['banner_image'] = IMAGE_URL_PATH . 'services/banner/' . $v['banner_image'];
                    $tmpS[] = $tmp;
                }
                $rslt['services'] = $tmpS;
                //pr($rslt); exit;
                if ($rslt) {
                    $this->success('Service Categories Fetched Successfully!', $rslt);
                } else {
                    $this->wrong('Service Category data not found!');
                }
            } else {
                $this->wrong('Service Category data not found!');
            }
        } else {
            $this->wrong('Service Category Id is missing!');
        }
    }

    //***********************************************************************************************//
    // * Function     :  homepage
    // * Parameter    :  
    // * Description  :  This function used to app home page data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function categoryList() {
        $this->loadModel('ServiceCategory');
        // Get Category Icon - Start
        $categoryIcon = [];
        $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'banner_image'])->where(['status' => 'ACTIVE'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($category as $val) {
            $tmp = [];
            if (isset($val) && !empty($val)) {
                $tmp['category_id'] = $val['id'];
                $tmp['category_name'] = $val['name'];
                $tmp['banner_image'] = IMAGE_URL_PATH . 'categories/banner/' . $val['banner_image'];
                $categoryIcon[] = $tmp;
            }
        }
        // Get Category Icon - End
        $this->success('Homepage Data Fateched!', $categoryIcon);
    }

    //***********************************************************************************************//
    // * Function     :  serviceDetails
    // * Parameter    :  
    // * Description  :  This function used to app home page data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function serviceDetails($id = '') {
        $this->loadModel('Users');
        $this->loadModel('Services');
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        $this->loadModel('ServiceReviews');
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        if (isset($id) && $id != '') {
            $rslt = [];
            $sDetails = $this->Services->find('all')->where(['status' => 'ACTIVE', 'id' => $id])->order(['id' => 'ASC'])->hydrate(false)->first();
            if (isset($sDetails) && !empty($sDetails)) {
                $rslt['id'] = $sDetails['id'];
                $rslt['category_id'] = $sDetails['category_id'];
                $rslt['category_name'] = $this->Services->getCategoryName($sDetails['category_id']);
                $rslt['service_name'] = $sDetails['service_name'];
                $rslt['service_details'] = html_entity_decode($sDetails['service_description']);
                $rslt['visit_charge'] = $sDetails['visit_charge'];
                $rslt['minimum_charge'] = $sDetails['minimum_charge'];
                $rslt['banner_image'] = IMAGE_URL_PATH . 'services/banner/' . $sDetails['banner_image'];
                $rslt['service_description'] = [];
                if ($sDetails['icon_1'] != '' && $sDetails['desc_heading_1'] != '' && $sDetails['desc_text_1'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_1'];
                    $tmpD['heading'] = $sDetails['desc_heading_1'];
                    $tmpD['text'] = $sDetails['desc_text_1'];
                    $rslt['service_description'][] = $tmpD;
                }
                if ($sDetails['icon_2'] != '' && $sDetails['desc_heading_2'] != '' && $sDetails['desc_text_2'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_2'];
                    $tmpD['heading'] = $sDetails['desc_heading_2'];
                    $tmpD['text'] = $sDetails['desc_text_2'];
                    $rslt['service_description'][] = $tmpD;
                }
                if ($sDetails['icon_3'] != '' && $sDetails['desc_heading_3'] != '' && $sDetails['desc_text_3'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_3'];
                    $tmpD['heading'] = $sDetails['desc_heading_3'];
                    $tmpD['text'] = $sDetails['desc_text_3'];
                    $rslt['service_description'][] = $tmpD;
                }
                if ($sDetails['icon_4'] != '' && $sDetails['desc_heading_4'] != '' && $sDetails['desc_text_4'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_4'];
                    $tmpD['heading'] = $sDetails['desc_heading_4'];
                    $tmpD['text'] = $sDetails['desc_text_4'];
                    $rslt['service_description'][] = $tmpD;
                }
                if ($sDetails['icon_5'] != '' && $sDetails['desc_heading_5'] != '' && $sDetails['desc_text_5'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_5'];
                    $tmpD['heading'] = $sDetails['desc_heading_5'];
                    $tmpD['text'] = $sDetails['desc_text_5'];
                    $rslt['service_description'][] = $tmpD;
                }
                if ($sDetails['icon_6'] != '' && $sDetails['desc_heading_6'] != '' && $sDetails['desc_text_6'] != '') {
                    $tmpD = [];
                    $tmpD['icon'] = IMAGE_URL_PATH . 'services/icons/' . $sDetails['icon_6'];
                    $tmpD['heading'] = $sDetails['desc_heading_6'];
                    $tmpD['text'] = $sDetails['desc_text_6'];
                    $rslt['service_description'][] = $tmpD;
                }
                // Ratecard - Start
                $rateArr = [];
                $rateCards = $this->ServiceRatecards->find('all')->where(['service_id' => $sDetails['id']])->hydrate(false)->toArray();
                if (!empty($rateCards)) {
                    foreach ($rateCards as $ratecard) {
                        //pr($ratecard); exit;
                        $tmp = [];
                        $tmp['id'] = $ratecard['id'];
                        $tmp['service_id'] = $ratecard['service_id'];
                        $tmp['title'] = $ratecard['title'];
                        $tmp['title2'] = '';
                        if ($ratecard['qunatity'] == 'YES') {
                            $rates = $this->ServiceRatecardRates->find('all')->where(['ratecards_id' => $ratecard['id']])->hydrate(false)->toArray();
                            foreach ($rates as $rate) {
                                $tmp['price'][] = ['quantity' => $rate['qunatity_title'], 'amount' => number_format($rate['rate'], 2)];
                            }
                        } else {
                            $tmp['price'][] = ['quantity' => $ratecard['qunatity'], 'amount' => number_format($ratecard['price'], 2)];
                        }
                        $rateArr[] = $tmp;
                    }
                }
                $rslt['service_ratecard'] = $rateArr;
                // Ratecard - End
                $this->success('Service Details Fetched Successfully', $rslt);
            } else {
                $this->wrong('Service data not found!');
            }
        } else {
            $this->wrong('Service Id is missing!');
        }
    }

    public function getquestionArr($id) {
        $this->loadModel('Services');
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        if (isset($id) && $id != '') {
            $sDetails = $this->Services->find('all')->where(['status' => 'ACTIVE', 'id' => $id])->order(['id' => 'ASC'])->hydrate(false)->first();
            if (isset($sDetails) && !empty($sDetails)) {
                $questionArr = [];
                $quesArr = $this->ServiceQuestions->find('all')->where(['category_id' => $sDetails['category_id'], 'service_id' => $sDetails['id'], 'questions_type' => 'parent'])->hydrate(false)->toArray();
                if (!empty($quesArr)) {
                    foreach ($quesArr as $key1 => $val1) {
                        $service_questions_answers1 = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $val1['id']])->hydrate(false)->toArray();
                        if (isset($service_questions_answers1) && !empty($service_questions_answers1)) {
                            foreach ($service_questions_answers1 as $key2 => $val2) {
                                $tmp = [];
                                $step = $key1 + 1;
                                $tmp['option_step'] = $step;
                                $tmp['option_name'] = $val2['label'];
                                $tmp['option_id'] = $val2['id'];
                                $tmp['icon_imgs'] = ($val2['icon_img'] != '') ? QUETIONS_ICON_URL_PATH . $val2['icon_img'] : '';
                                $tmp['option_quantity'] = substr($val2['quantity'], 0, 1);
                                $checkChildQuestions = ($this->checkChildQuestionsExist($val1['id'], $val2['id'])) ? 'Yes' : 'No';
                                if ($checkChildQuestions == 'Yes') {
                                    $qID = $val1['id'];
                                    $aID = $val2['id'];
                                    $tmpsq = $this->getsubquestionArr($qID, $aID);
                                    //pr($tmpsq); exit;
                                    if (isset($tmpsq) && !empty($tmpsq)) {
                                        foreach ($tmpsq as $key3 => $val3) {
                                            $tmpS = [];
                                            $steps = $step + 1;
                                            $tmpS['option_step'] = $steps;
                                            $tmpS['option_name'] = $val3['option_name'];
                                            $tmpS['option_id'] = $val3['option_id'];
                                            $tmpS['icon_imgs'] = ($val3['icon_img'] != '') ? QUETIONS_ICON_URL_PATH . $val3['icon_img'] : '';
                                            $tmpS['option_quantity'] = substr($val3['option_quantity'], 0, 1);
                                            $checkChildQuestions1 = ($this->checkChildQuestionsExist($val3['question_id'], $val3['option_id'])) ? 'Yes' : 'No';
                                            if ($checkChildQuestions1 == 'Yes') {
                                                $qID = $val3['question_id'];
                                                $aID = $val3['option_id'];
                                                $tmpssq = $this->getsubquestionArr($qID, $aID);
                                                //pr($tmpsq); exit;
                                                if (isset($tmpssq) && !empty($tmpssq)) {
                                                    foreach ($tmpssq as $key4 => $val4) {
                                                        $tmpSS = [];
                                                        $stepss = $steps + 1;
                                                        $tmpSS['option_step'] = $stepss;
                                                        $tmpSS['option_name'] = $val4['option_name'];
                                                        $tmpSS['option_id'] = $val4['option_id'];
                                                        $tmpSS['icon_imgs'] = ($val4['icon_img'] != '') ? QUETIONS_ICON_URL_PATH . $val4['icon_img'] : '';
                                                        $tmpSS['option_quantity'] = substr($val4['option_quantity'], 0, 1);
                                                        $checkChildQuestions2 = ($this->checkChildQuestionsExist($val4['question_id'], $val4['option_id'])) ? 'Yes' : 'No';
                                                        if ($checkChildQuestions2 == 'Yes') {
                                                            $qID = $val4['question_id'];
                                                            $aID = $val4['option_id'];
                                                            $tmpsssq = $this->getsubquestionArr($qID, $aID);
                                                            // pr($tmpsssq); exit;
                                                            if (isset($tmpsssq) && !empty($tmpsssq)) {
                                                                foreach ($tmpsssq as $key5 => $val5) {
                                                                    $tmpSSS = [];
                                                                    $stepsss = $stepss + 1;
                                                                    $tmpSSS['option_step'] = $stepsss;
                                                                    $tmpSSS['option_name'] = $val5['option_name'];
                                                                    $tmpSSS['option_id'] = $val5['option_id'];
                                                                    $tmpSSS['icon_imgs'] = ($val5['icon_img'] != '') ? QUETIONS_ICON_URL_PATH . $val5['icon_img'] : '';
                                                                    $tmpSSS['option_quantity'] = substr($val5['option_quantity'], 0, 1);
                                                                    $tmpSS['sub'][] = $tmpSSS;
                                                                }
                                                            }
                                                        } else {
                                                            $tmpSS['sub'] = '';
                                                        }
                                                        $tmpS['sub'][] = $tmpSS;
                                                    }
                                                }
                                            } else {
                                                $tmpS['sub'] = '';
                                            }
                                            $tmp['sub'][] = $tmpS;
                                        }
                                    }
                                } else {
                                    $tmp['sub'] = '';
                                }
                                $questionArr[] = $tmp;
                            }
                        }
                    }
                }
                $this->success('Service Questions Fetched Successfully', $questionArr);
            } else {
                $this->wrong('Service data not found!');
            }
        } else {
            $this->wrong('Service Id is missing!');
        }
    }

    public function getsubquestionArr($qID, $aID) {
        $childQuestionID = $this->ServiceQuestions->find('all')->where(['questions_type' => 'child', 'parent_question_id' => $qID, 'parent_answer_id' => $aID])->hydrate(false)->toArray();
        $rslt = [];
        if (!empty($childQuestionID)) {
            foreach ($childQuestionID as $key => $val) {
                $service_questions_answers = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $val['id']])->hydrate(false)->toArray();
                if (isset($service_questions_answers) && !empty($service_questions_answers)) {
                    foreach ($service_questions_answers as $k => $v) {
                        $tmpS = [];
                        $tmpS['question_id'] = $v['question_id'];
                        $tmpS['option_name'] = $v['label'];
                        $tmpS['option_id'] = $v['id'];
                        $tmpS['icon_img'] = $v['icon_img'];
                        $tmpS['option_quantity'] = substr($v['quantity'], 0, 1);
                        $rslt[] = $tmpS;
                    }
                }
            }
        }
        return $rslt;
    }

    public function serviceReviews($id = '') {
        $this->layout = 'ajax';
        $requestArr = $this->getInputArr();
        if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
            $page_no = $requestArr['page_no'];
        } else {
            $page_no = 1;
        }
        $this->loadModel('Users');
        $this->loadModel('Services');
        $this->loadModel('ServiceReviews');
        if (isset($id) && $id != '') {
            $rslt = [];
            $sDetails = $this->Services->find('all')->where(['status' => 'ACTIVE', 'id' => $id])->order(['id' => 'ASC'])->hydrate(false)->first();
            if (isset($sDetails) && !empty($sDetails)) {
                // Review - Start
                $reviewsArr = [];
                $reviews = $this->ServiceReviews->find('all')->where(['service_id' => $sDetails['id']])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
                foreach ($reviews as $review) {
                    $tmpArr = $userData = [];
                    $tmpArr['review_title'] = $review['review_title'];
                    $tmpArr['review_description'] = $review['review_description'];
                    $tmpArr['review_rates'] = $review['review_rates'];
                    $userData = $this->Users->getuserId($review['user_id'])->toArray();
                    $tmpArr['service_name'] = $this->Services->getServiceName($id);
                    $tmpArr['user_name'] = $userData['name'];
                    $tmpArr['user_pic'] = ($userData['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $userData['profile_pic'] : '';
                    $reviewsArr[] = $tmpArr;
                }
                $nextPageReviews = $this->ServiceReviews->find('all')->where(['service_id' => $sDetails['id']])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
                $rslt['service_reveiws'] = $reviewsArr;
                $rslt['next_page'] = (!empty($nextPageReviews)) ? true : false;
                // Review - End
                $this->success("Service Reviews Fetched!", $rslt);
            } else {
                $this->wrong('Service reviews not found!');
            }
        } else {
            $this->wrong('Service Id is missing!');
        }
    }

    public function checkChildQuestionsExist($question_id, $answer_id) {
        $cond_arr = ['parent_question_id' => $question_id, 'parent_answer_id' => $answer_id, 'questions_type' => 'child'];
        $counts = $this->ServiceQuestions->find('all')->where($cond_arr)->hydrate(false)->count();
        if (isset($counts) && ($counts != 0)) {
            return true;
        } else {
            return false;
        }
    }

    public function nextStepQuestions($question_id, $answer_id, $serviceId) {
        $cond_arr = ['parent_question_id' => $question_id, 'parent_answer_id' => $answer_id, 'service_id' => $serviceId];
        $questions = $this->ServiceQuestions->find('all')->select(['que_type'])->where($cond_arr)->hydrate(false)->first();
        if (isset($questions) && !empty($questions)) {
            return $questions['que_type'];
        } else {
            return false;
        }
    }

    public function getServicesSubQuestions() {
        $this->layout = 'ajax';
        $requestArr = $this->getInputArr();
        $requiredFields = array(
            'Question Id' => (isset($requestArr['question_id']) && $requestArr['question_id'] != '') ? $requestArr['question_id'] : '',
            'Answer Id' => (isset($requestArr['answer_id']) && $requestArr['answer_id'] != '') ? $requestArr['answer_id'] : ''
        );
        $validate = $this->checkRequiredFields($requiredFields);
        if ($validate != "") {
            $this->wrong($validate);
        } else {
            $this->loadModel('ServiceQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $question_id = $requestArr['question_id'];
            $answer_id = $requestArr['answer_id'];
            $condArr = ['parent_question_id' => $question_id, 'parent_answer_id' => $answer_id, 'questions_type' => 'child'];
            $questionArr = [];
            $quesArr = $this->ServiceQuestions->find('all')->where($condArr)->hydrate(false)->toArray();
            if (!empty($quesArr)) {
                foreach ($quesArr as $key => $val) {
                    //pr($val); exit;
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['question'] = $val['question_title'];
                    if (isset($val['answer_type']) && $val['answer_type'] == 't') {
                        $tmp['answer_type'] = 'text';
                    } else {
                        $tmp['answer_type'] = 'radio_button';
                    }
                    $answerArrs = [];
                    $service_questions_answers = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $val['id']])->hydrate(false)->toArray();
                    if (isset($service_questions_answers) && !empty($service_questions_answers)) {
                        foreach ($service_questions_answers as $v) {
                            //print_r($v); exit;
                            $tmpA = [];
                            $tmpA['id'] = $v['id'];
                            $tmpA['question_id'] = $v['question_id'];
                            $tmpA['label'] = $v['label'];
                            // $tmpA['quantity'] = (isset($v['quantity']) && $v['quantity'] == 'YES') ? 'Y' : 'N';
                            $tmpA['price'] = $v['price'];
                            //$tmpA['child_questions'] = ($this->checkChildQuestionsExist($val['id'], $v['id'])) ? 'Yes' : 'No';
                            $tmpA['nextstep'] = "-";
                            if ($v['quantity'] == 'YES') {
                                $tmpA['nextstep'] = "QUANTITY";
                            }
                            if ($v['quantity'] == 'NO') {
                                $tmpA['nextstep'] = ($this->nextStepQuestions($val['id'], $v['id'], $val['service_id'])) ? $this->nextStepQuestions($val['id'], $v['id'], $val['service_id']) : '-';
                            }
                            $answerArrs[] = $tmpA;
                        }
                    }
                    $tmp['answers'] = $answerArrs;
                    $questionArr[] = $tmp;
                }
            }
            if (!empty($questionArr)) {
                $this->success('Sub Question Fetched!', $questionArr);
            } else {
                $this->success('No Questions Found!');
            }
        }
    }

    public function helpDetails() {
        $this->layout = 'ajax';
        $this->loadModel('Faqs');
        $rslt = [];
        $rslt['contact_us'] = Configure::read('Help');
        $faqsArr = [];
        $faqsArr = $this->Faqs->find('all')->select(['id', 'question', 'answer'])->hydrate(false)->toArray();
        $rslt['faq'] = $faqsArr;
        $this->success('Info Fetched Successfully', $rslt);
    }

    public function getCartId() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $checkArrs = $this->Carts->find('all')->where(['user_id' => $user_id, 'status' => 'PROCESS'])->hydrate(false)->first();
            if (!empty($checkArrs)) {
                //pr($checkArrs); exit;
                $this->success('Cart Found!', ['id' => $checkArrs['id']]);
            } else {
                $this->wrong('Sorry, no cart availble');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function createCart() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $this->loadModel('Services');
            $requestArr = $this->getInputArr();
            // Check Cart is already Exist or not
            $checkArrs = $this->Carts->find('all')->where(['user_id' => $user_id, 'status' => 'PROCESS'])->hydrate(false)->first();
            if (empty($checkArrs)) {
                $checkArr = $this->Carts->find('all')->where(['user_id' => $user_id, 'status' => 'PROCESS'])->hydrate(false)->first();
                if (empty($checkArr)) {
                    $carts = $this->Carts->newEntity();
                    $cartArr = ['user_id' => $user_id, 'status' => 'PROCESS'];
                    $carts = $this->Carts->patchEntity($carts, $cartArr);
                    $carts->created = date("Y-m-d H:i:s");
                    $carts->modified = date("Y-m-d H:i:s");
                    $rslt = $this->Carts->save($carts);
                    if ($rslt->id) {
                        $this->success('Cart created!', ['id' => $rslt->id]);
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    echo json_encode(['status' => 'fail', 'msg' => 'Cart already Exist!', 'data' => ['id' => $checkArr['id']]]);
                    exit;
                    //$this->success('Cart already Exist!', ['id' => $checkArr['id']]);
                }
            } else {
                echo json_encode(['status' => 'fail', 'msg' => 'Sorry, Your cart is already in process!', 'data' => ['id' => $checkArrs['id']]]);
                exit;
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function addCartProduct() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $requestArr = $this->getInputArr();
            $cartId = isset($requestArr['cart_id']) ? $requestArr['cart_id'] : '';
            $serviceId = isset($requestArr['service']) ? $requestArr['service'] : '';
            $categoryId = isset($requestArr['category_id']) ? $requestArr['category_id'] : '';
            $requiredFields = [
                'Cart Id' => $cartId,
                'Category Id' => $categoryId,
                'Service Id' => $serviceId
            ];
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            } else {
                $this->loadModel('Carts');
                $this->loadModel('CartOrders');
                $this->loadModel('CartOrderQuestions');
                $this->loadModel('Services');
                $this->loadModel('ServiceQuestions');
                $this->loadModel('ServiceQuestionAnswers');
                // Check Cart is exist or not
                $checkCart = $this->Carts->find('all')->where(['user_id' => $user_id, 'id' => $cartId, 'status' => 'PROCESS'])->hydrate(false)->first();
                if ($checkCart) {
                    $serviceDetails = $this->Services->find('all')->where(['id' => $serviceId])->hydrate(false)->first();
                    if (!isset($serviceDetails) || empty($serviceDetails)) {
                        $this->wrong("Sorry, Service Details not found!");
                    }
                    // Check Cart is already Exist or not
                    $on_inspection = 'N';
                    $checkCart = $this->Carts->find('all')->where(['id' => $requestArr['cart_id'], 'status' => 'PROCESS'])->hydrate(false)->first();
                    if (!empty($checkCart)) {
                        $questionsData = isset($requestArr['questions_data']) ? $requestArr['questions_data'] : array();
                        // pr($questionsData); exit;
                        if (empty($questionsData)) {
                            $this->wrong("Sorry, Questions data is not found!");
                        } else {
                            $total_price = 0;
                            foreach ($questionsData as $questions) {
                                $questionDetails = $questionStoreDetails = [];
                                $question_id = $questions->option_step;
                                $answer_id = $questions->option_id;
                                if (isset($questions->option_quantity) && $questions->option_quantity != '') {
                                    //echo $question_id . " " . $answer_id;
                                    $AnswersArr = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $question_id, 'id' => $answer_id])->hydrate(false)->first();
                                    //pr($AnswersArr); exit;
                                    if ($AnswersArr['quantity'] == 'YES') {
                                        $total_price = $questions->option_quantity * $AnswersArr['price'];
                                    } else {
                                        $questionsArr = $this->ServiceQuestions->find('all')->where(['parent_question_id' => $question_id, 'parent_answer_id' => $answer_id])->hydrate(false)->first();
                                        if (isset($questionsArr['id']) && $questionsArr['id'] != '') {
                                            $queId = $questionsArr['id'];
                                            $ansArr = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $queId])->hydrate(false)->toArray();
                                            foreach ($ansArr as $ans) {
                                                if ($ans['quantity'] == 'YES') {
                                                    if (strpos($ans['label'], '-') !== false) {
                                                        $explodeArr = explode('-', $ans['label']);
                                                        $min_quantity = $explodeArr[0];
                                                        $max_quantity = $explodeArr[1];
                                                        if ($questions->option_quantity >= $min_quantity && $questions->option_quantity <= $max_quantity) {
                                                            $total_price = $ans['price'] * $questions->option_quantity;
                                                        }
                                                    } else if (strpos($ans['label'], '+') !== false) {
                                                        $explodeArr = explode('+', $ans['label']);
                                                        $min_quantity = $explodeArr[0];
                                                        if ($min_quantity <= $questions->option_quantity) {
                                                            $total_price = $ans['price'] * $questions->option_quantity;
                                                        }
                                                    } else {
                                                        $total_price = $ans['price'] * $questions->option_quantity;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $questionsArr = $this->ServiceQuestionAnswers->find('all')->where(['question_id' => $question_id, 'id' => $answer_id])->hydrate(false)->first();
                                    //pr($questionsArr); exit;
                                    continue;
                                    if (isset($questionsArr['quantity']) && $questionsArr['quantity'] == 'NO') {
                                        if ($questionsArr['price'] != 0) {
                                            $total_price = $questionsArr['price'];
                                        }
                                    } else {
                                        $on_inspection = 'Y';
                                        $total_price = '0';
                                    }
                                }
                            }
                        }
                        $cartOrders = $this->CartOrders->newEntity();
                        $order_data = array(
                            'user_id' => $user_id,
                            'cart_id' => $cartId,
                            'category_id' => $categoryId,
                            'service_id' => $serviceId,
                            'on_inspections' => $on_inspection,
                            'total_amount' => $total_price,
                            'created_by' => $user_id,
                            'modified_by' => $user_id
                        );
                        //pr($order_data); exit;
                        $cartOrders = $this->CartOrders->patchEntity($cartOrders, $order_data);
                        $cartOrders->order_id = 0;
                        $cartOrders->created_at = date('Y-m-d H:i:s');
                        $cartOrders->modified_at = date('Y-m-d H:i:s');
                        $cartOrderSave = $this->CartOrders->save($cartOrders);
                        //pr($cartOrderSave); exit;
                        if ($cartOrderSave) {
                            $cartOrderId = $cartOrderSave['id'];
                            if (!empty($questionsData)) {
                                //print_R($questionsData); exit;
                                $flag = false;
                                foreach ($questionsData as $queData) {
                                    $queAnsData = $this->CartOrderQuestions->newEntity();
                                    $question_id = $queData->option_step;
                                    $answer_id = $queData->option_id;
                                    $question_quantity = isset($queData->option_quantity) && $queData->option_quantity != '' ? $queData->option_quantity : '-';
                                    $question_text_ans = isset($queData->option_description) && $queData->option_description ? $queData->option_description : '-';
                                    $qa_data = array(
                                        'user_id' => $user_id,
                                        'cart_id' => $cartId,
                                        'cart_order_id' => $cartOrderId,
                                        'question_id' => $question_id,
                                        'answer_id' => $answer_id,
                                        'question_quantity' => ($question_quantity != '') ? $question_quantity : '0',
                                        'question_text_ans' => $question_text_ans
                                    );
                                    $queAnsData = $this->CartOrderQuestions->patchEntity($queAnsData, $qa_data);
                                    $queAnsData->created_at = date('Y-m-d H:i:s');
                                    $queAnsData->modified_at = date('Y-m-d H:i:s');
                                    if ($this->CartOrderQuestions->save($queAnsData)) {
                                        $flag = true;
                                    } else {
                                        $this->wrong('UNABLE TO ADD THE CART ORDER QUESTIONS.');
                                    }
                                }
                                if ($flag) {
                                    $cartPriceDetails = $this->totalCartPrice($cartId);
                                    $this->success('Product Added in Cart!', $cartPriceDetails);
                                } else {
                                    $this->wrong('UNABLE TO ADD THE CART ORDER QUESTIONS.');
                                }
                            }
                        } else {
                            $this->wrong('UNABLE TO ADD THE SERVICE.');
                        }
                    } else {
                        $this->wrong('Sorry, Cart is not exists!');
                    }
                } else {
                    $checkCart1 = $this->Carts->find('all')->where(['user_id' => $user_id, 'id' => $cartId, 'status' => 'PROCESS'])->hydrate(false)->first();
                    if ($checkCart1) {
                        $this->wrong("Sorry, You cannot add this service in this cart!");
                    }
                    $this->wrong("Sorry, Cart not found!");
                }
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function cartDetails() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $requestArr = $this->getInputArr();
            $cartId = isset($requestArr['cart_id']) ? $requestArr['cart_id'] : $this->wrong('Sorry, Cart id missing');
            $checkCart = $this->Carts->find('all')->where(['user_id' => $user_id, 'id' => $cartId, 'status' => 'PROCESS'])->hydrate(false)->first();
            //pr($checkCart); exit;
            if (isset($checkCart) && !empty($checkCart)) {
                $cartPriceDetails = $this->totalCartPrice($cartId);
                //pr($cartPriceDetails); exit;
                if (!empty($cartPriceDetails['services'])) {
                    $this->success("Cart Order Details Fetched!", $cartPriceDetails);
                } else {
                    $this->wrong("Sorry, Cart is empty!");
                }
            } else {
                $this->wrong("Sorry, Cart not found!");
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function cartClear() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $requestArr = $this->getInputArr();
            $cartId = isset($requestArr['cart_id']) ? $requestArr['cart_id'] : $this->wrong('Sorry, Cart id missing');
            $checkCart = $this->Carts->find('all')->where(['user_id' => $user_id, 'id' => $cartId, 'status' => 'PROCESS'])->hydrate(false)->first();
            if (isset($checkCart) && !empty($checkCart)) {
                $cart = $this->Carts->get($cartId);
                $update = ['status' => 'CANCELLED'];
                $cart = $this->Carts->patchEntity($cart, $update);
                $cart->modified_by = $this->request->session()->read('Auth.User.id');
                $cart->modified_at = date("Y-m-d H:i:s");
                if ($this->Carts->save($cart)) {
                    $this->success('Your cart is cancelled');
                } else {
                    $this->wrong(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->wrong("Sorry, Cart not found!");
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function removeCartProduct() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $requestArr = $this->getInputArr();
            $cartOrderId = isset($requestArr['cart_order_id']) ? $requestArr['cart_order_id'] : $this->wrong('Sorry, Cart Order id missing');
            $checkCartOrderExist = $this->CartOrders->find('all')->where(['id' => $cartOrderId])->hydrate(false)->first();
            //pr($checkCartOrderExist['cart_id']); exit;
            if (isset($checkCartOrderExist) && !empty($checkCartOrderExist)) {
                $cartId = $checkCartOrderExist['cart_id'];
                $cartProduct = $this->CartOrders->get($cartOrderId);
                if ($this->CartOrderQuestions->deleteAll(['cart_order_id' => $cartOrderId])) {
                    if ($this->CartOrders->delete($cartProduct)) {
                        $cartPriceDetails = $this->totalCartPrice($cartId);
                        $this->success('SERVICE REMOVED FROM CART!', $cartPriceDetails);
                    } else {
                        $this->wrong('UNABLE TO DELETE THE SERVICE FROM CART!');
                    }
                } else {
                    $this->wrong('UNABLE TO DELETE THE SERVICE QUESTIONS FROM CART!');
                }
            } else {
                $this->wrong('Cart Product is not available');
            }
            //cart_order_id
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function totalCartPrice($cartID) {
        //echo $cartID; exit;
        if (isset($cartID) && $cartID != '') {
            $this->loadModel('Carts');
            $this->loadModel('Categories');
            $this->loadModel('Services');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $condArr = ['cart_id' => $cartID];
            $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
            $ordersDetails = [];
            //pr($cartOrders); exit;
            foreach ($cartOrders as $order) {
                if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                    if ($order['total_amount'] == 0) {
                        continue;
                    }
                }
                $tmp = [];
                $tmp['cart_order_id'] = $order['id'];
                $tmp['category_id'] = $order['category_id'];
                $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                $tmp['service_id'] = $order['service_id'];
                $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                $tmp['banner_img'] = $this->Services->getServiceImagePAth($order['service_id']);
                //$tmp['banner_img'] = $this->Services->getServiceName($order['service_id']);
                $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $order['id']])->hydrate(false)->toArray();
                foreach ($tmpDetails as $orderQues) {
                    $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
                    //pr($questArr); //exit;
                    if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                        $tmp['serviceDescription'] = (isset($questArr['answer']) && $questArr['answer'] != '') ? $questArr['answer'] : '';
                        $tmp['quantity'] = $orderQues['question_quantity'];
                        $tmp['total_amount'] = $order['total_amount'];
                        if ($tmp['quantity'] == 0) {
                            $tmp['amount'] = 0;
                            $tmp['total_amount'] = $order['total_amount'];
                        } else {
                            $tmp['amount'] = $order['total_amount'] / $tmp['quantity'];
                            $tmp['total_amount'] = $order['total_amount'];
                        }
                        $tmp['on_inspection'] = 'N';
                    } else {
                        $tmp['serviceDescription'] = (isset($questArr['answer']) && $questArr['answer'] != '') ? $questArr['answer'] : '';
                        $tmp['quantity'] = $orderQues['question_quantity'];
                        $tmp['on_inspection'] = 'Y';
                        $tmp['amount'] = 0;
                        $tmp['total_amount'] = $order['total_amount'];
                    }
                }

                $ordersDetails[$order['category_id']]['category'] = $this->Services->getCategoryName($order['category_id']);
                $ordersDetails[$order['category_id']]['services'][] = $tmp;
                //pr($ordersDetails); exit;
            }
            $finalOrderDetails = [];
            if (!empty($ordersDetails)) {
                foreach ($ordersDetails as $key => $val) {
                    $finalOrderDetails[] = $val;
                }
            }

            $total['on_inspection'] = 'N';
            $total['order_amount'] = 0.00;
            $total['tax'] = 0.00;
            $total['total_amount'] = 0.00;
            $order_amount = 0.00;

            foreach ($ordersDetails as $od) {
                //pr($od); exit;
                foreach ($od['services'] as $val) {
                    if (isset($val['on_inspection']) && $val['on_inspection'] == 'Y') {
                        $total['on_inspection'] = 'Y';
                    }
                    $totAmount = isset($val['total_amount']) && $val['total_amount'] != '' ? $val['total_amount'] : 0;
                    $order_amount += $totAmount;
                }
            }
            $tax = $order_amount * GST_TAX / 100;
            $totals = $order_amount + $tax;
            $total['order_amount'] = number_format($order_amount, 2);
            $total['tax'] = number_format($tax, 2);
            $total['total_amount'] = number_format($totals, 2);
            //pr($total); exit;
            return ['services' => $finalOrderDetails, 'total' => $total];
        } else {
            $this->wrong('Cart Id is missing!');
        }
    }

    public function totalCartPrice2($cartID) {
        //echo $cartID; exit;
        if (isset($cartID) && $cartID != '') {
            $this->loadModel('Carts');
            $this->loadModel('Categories');
            $this->loadModel('Services');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $condArr = ['cart_id' => $cartID];
            $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
            $ordersDetails = [];
            //pr($cartOrders); exit;
            foreach ($cartOrders as $order) {
                $tmp = [];
                $tmp['cart_order_id'] = $order['id'];
                $tmp['category_id'] = $order['category_id'];
                $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                $tmp['service_id'] = $order['service_id'];
                $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                $tmp['banner_img'] = $this->Services->getServiceImagePAth($order['service_id']);
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
                $ordersDetails[] = $tmp;
            }
            $total['on_inspection'] = 'N';
            $total['order_amount'] = 0.00;
            $total['tax'] = 0.00;
            $total['total_amount'] = 0.00;
            $order_amount = 0.00;
            //pr($ordersDetails); exit;
            foreach ($ordersDetails as $od) {
                if (isset($od['on_inspection']) && $od['on_inspection'] == 'Y') {
                    $total['on_inspection'] = 'Y';
                }
                $totAmount = isset($od['total_amount']) && $od['total_amount'] != '' ? $od['total_amount'] : 0;
                $order_amount += $totAmount;
            }
            $tax = $order_amount * GST_TAX / 100;
            $totals = $order_amount + $tax;
            $total['order_amount'] = number_format($order_amount, 2);
            $total['tax'] = number_format($tax, 2);
            $total['total_amount'] = number_format($totals, 2);
            return ['services' => $ordersDetails, 'total' => $total];
        } else {
            $this->wrong('Cart Id is missing!');
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

    public function applyCouponCode() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $this->loadModel('Coupons');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Cart Id' => (isset($requestArr['cart_id']) && $requestArr['cart_id'] != '') ? $requestArr['cart_id'] : '',
                'Coupon Code' => (isset($requestArr['coupon_code']) && $requestArr['coupon_code'] != '') ? $requestArr['coupon_code'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $cart_id = $requestArr['cart_id'];
            $coupon_code = strtoupper($requestArr['coupon_code']);
            $cartExist = $this->Carts->find('all')->where(['id' => $requestArr['cart_id'], 'status' => 'PROCESS'])->hydrate(false)->first();
            if ($cartExist) {
                $cartDetails = $this->totalCartPrice($cart_id);
                $couponCodeDetails = $this->Coupons->find('all')->where(['code' => $coupon_code])->hydrate(false)->first();
                if ($couponCodeDetails) {
                    $todayDate = date('Y-m-d');
                    $validTo = $couponCodeDetails['valid_to']->format('Y-m-d');
                    $validFrom = $couponCodeDetails['valid_from']->format('Y-m-d');
                    $curDate = strtotime($todayDate);
                    if ($curDate < strtotime($validTo) || $curDate > strtotime($validFrom)) {
                        echo json_encode(['status' => 'fail', 'msg' => 'Sorry, Discount coupon was expired!', 'data' => $cartDetails]);
                        exit;
                    } else {
                        //echo ' valid';
                        $discount = 0.00;
                        if (isset($couponCodeDetails['discount_type']) && $couponCodeDetails['discount_type'] == 'PRICE') {
                            $discount = $couponCodeDetails['amount'];
                            $cartDetails["total"]["discount"] = "-" . number_format($discount, 2);
                            $cartDetails["total"]["payable_amount"] = number_format($cartDetails["total"]["total_amount"] - $discount, 2);
                        } else {
                            $discountPercentage = $couponCodeDetails['amount'];
                            if ($cartDetails["total"]["total_amount"] != 0.00) {
                                $discount = ($couponCodeDetails['amount'] * $cartDetails["total"]["total_amount"]) / 100;
                                $cartDetails["total"]["discount"] = "-" . number_format($discount, 2);
                                $cartDetails["total"]["payable_amount"] = number_format($cartDetails["total"]["total_amount"] - $discount, 2);
                            } else {
                                $cartDetails["total"]["discount"] = $couponCodeDetails['amount'] . "%";
                            }
                        }
                        echo json_encode(['status' => 'success', 'msg' => 'Coupon code Applied!', 'data' => $cartDetails]);
                        exit;
                    }
                } else {
                    echo json_encode(['status' => 'fail', 'msg' => 'Sorry. No Discount coupon is invalid!', 'data' => $cartDetails]);
                    exit;
                }
            } else {
                $this->wrong('Sorry, Cart is not Exist!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function cartOrderPlaced() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Orders');
            $this->loadModel('Carts');
            $this->loadModel('Coupons');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Cart Id' => (isset($requestArr['cart_id']) && $requestArr['cart_id'] != '') ? $requestArr['cart_id'] : '',
                'User address' => (isset($requestArr['user_address']) && $requestArr['user_address'] != '') ? $requestArr['user_address'] : '',
                'Schedule Date' => (isset($requestArr['schedule_date']) && $requestArr['schedule_date'] != '') ? $requestArr['schedule_date'] : '',
                'Schedule Time' => (isset($requestArr['schedule_time']) && $requestArr['schedule_time'] != '') ? $requestArr['schedule_time'] : '',
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $cart_id = $requestArr['cart_id'];
            $coupon_code = isset($requestArr['coupon_code']) && $requestArr['coupon_code'] != '' ? strtoupper($requestArr['coupon_code']) : '';
            $wallet_amount = isset($requestArr['wallet_amount']) && $requestArr['wallet_amount'] != '' ? $requestArr['wallet_amount'] : '';
            // Check Cart Order Placed
            $cartExist = $this->Carts->find('all')->where(['id' => $requestArr['cart_id'], 'status' => 'PROCESS'])->hydrate(false)->first();
            if ($cartExist) {
                //pr($cartExist); exit;
                $order = $this->Orders->newEntity();
                $orderData = [];
                $orderData['user_id'] = $user_id;
                $orderData['cart_id'] = $cart_id;
                $orderData['order_id'] = $this->orderIdCreate();
                $orderData['user_address'] = $requestArr['user_address'];
                //$orderData['schedule_date'] = date('Y-m-d', strtotime($requestArr['schedule_date']));
                $orderData['schedule_time'] = $requestArr['schedule_time'];
                $orderData['on_inspections'] = '';
                $orderData['is_minimum_charge'] = 'N';
                $orderData['is_visiting_charge'] = 'N';
                $orderData['is_coupon_applied'] = 'N';
                $orderData['coupon_code'] = '';
                $orderData['wallet_amount'] = 0.00;
                $orderData['amount'] = 0.00;
                $orderData['on_inspections_cost'] = 0.00;
                $orderData['tax'] = 0.00;
                $orderData['total_amount'] = 0.00;
                $cartDetails = $this->totalCartPrice($cart_id);
                $orderData['on_inspections'] = (isset($cartDetails['on_inspection']) && $cartDetails['on_inspection'] != '') ? $cartDetails['on_inspection'] : 'N';
                $orderData['amount'] = str_replace(",", "", $cartDetails['total']['order_amount']);
                $orderData['tax'] = str_replace(",", "", $cartDetails['total']['tax']);
                $orderData['total_amount'] = str_replace(",", "", $cartDetails['total']['total_amount']);
                $orderData['discount'] = 0.00;
                $orderData['payable_amount'] = 0.00;
                $orderData['payment_method'] = '';
                $orderData['status'] = 'PENDING';
                $orderData['reason_order_cancelled'] = '';
                $orderData['payment_status'] = 'PENDING';
                $orderData['cart_product'] = json_encode($cartDetails);
                $orderData['created_by'] = $orderData['modified_by'] = $user_id;
                $order = $this->Orders->patchEntity($order, $orderData);
                $order->schedule_date = date('Y-m-d', strtotime($requestArr['schedule_date']));
                $order->created_at = date('Y-m-d H:i:s');
                $order->modified_at = date('Y-m-d H:i:s');
                //pr($order); exit;
                if ($this->Orders->save($order)) {
                    $cartArr = $this->Carts->get($cart_id);
                    $cartUpdate['status'] = 'PLACED';
                    $cartArr = $this->Carts->patchEntity($cartArr, $cartUpdate);
                    if ($this->Carts->save($cartArr)) {
                        $this->success('Order Placed Successfully!', ['order_id' => $orderData['order_id']]);
                    } else {
                        $this->wrong('Sorry, Order is not placed!');
                    }
                } else {
                    $this->wrong('Sorry, Order is not placed!');
                }
            } else {
                $this->wrong('Sorry, Cart is not Exist!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function orderDetails() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            //echo $userId; exit;
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $this->loadModel('Coupons');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $order = $this->Orders->find('all')->where(['order_id' => $order_id])->hydrate(false)->first();
            if (!empty($order)) {
                //pr($orderExist); exit;
                $orderDetails = [];
                $orderDetails['user_id'] = $order['user_id'];
                $orderDetails['order_id'] = $order['order_id'];
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
                $orderDetails['payment_status'] = $order['payment_status'];
                $orderDetails['images'] = '';
                $orderDetails['services'] = [];
                $orderDetails['total'] = [
                    'amount' => number_format($order['amount'], 2),
                    'tax' => number_format($order['tax'], 2),
                    'discount' => (is_string($order['discount'])) ? $order['discount'] : number_format($order['discount'], 2),
                    'wallet_amount' => number_format($order['wallet_amount'], 2),
                    'total_amount' => number_format($order['total_amount'], 2)
                ];
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




                $this->success('order detail fetched successfully', $orderDetails);
            } else {
                $this->wrong('Sorry, Order not found!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function walletDetails() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $requestArr = $this->getInputArr();
            if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
                $page_no = $requestArr['page_no'];
            } else {
                $page_no = 1;
            }
            $this->loadModel('Wallets');
            $walletHistory = $this->Wallets->find('all')->where(['user_id' => $userId])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            $rslt = [];
            if (isset($walletHistory) && !empty($walletHistory)) {
                foreach ($walletHistory as $history) {
                    $tmp = [];
                    $tmp['id'] = $history['id'];
                    $tmp['user_id'] = $history['user_id'];
                    $tmp['amount'] = $history['amount'];
                    $tmp['wallet_type'] = $history['wallet_type'];
                    if ($history['wallet_type'] == 'CREDIT') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/wallet-credit.png';
                    } else {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/wallet-debit.png';
                    }
                    $tmp['created'] = $history['created']->format('d-M-Y h:i A');
                    if ($history['purpose'] == 'REFERRAL') {
                        $userName = $this->getUserName($history['purpose_id']);
                        $tmp['details'] = 'Received Rewarded for refer to ' . $userName;
                    } else if ($history['purpose'] == 'ORDER') {
                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Paid for Order. id #' . $orderId;
                    } else if ($history['purpose'] == 'CASHBACK') {
                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Received Cashback for Order. id #' . $orderId;
                    } else if ($history['purpose'] == 'MEMBERSHIP_CASHBACK') {
                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Received Cashback for Membership Plan Signup';
                    }
                    $rslt['data'][] = $tmp;
                }
            }
            $rslt['total'] = ['current_balance' => $this->walletAmount($userId)];
            $nextPageHistory = $this->Wallets->find('all')->where(['user_id' => $userId])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
            $rslt['next_page'] = (!empty($nextPageHistory)) ? true : false;
            $this->success('Wallet Data Fatched!', $rslt);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function orderLists() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('CartOrders');
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
                $page_no = $requestArr['page_no'];
            } else {
                $page_no = 1;
            }
            $filter_key = (isset($requestArr['filter_key']) && $requestArr['filter_key'] != '') ? $requestArr['filter_key'] : '';
            $filter_vals = (isset($requestArr['filter_vals']) && $requestArr['filter_vals'] != '') ? $requestArr['filter_vals'] : '';
            $filter_status = (isset($requestArr['filter_status']) && $requestArr['filter_status'] != '') ? $requestArr['filter_status'] : '';
            $to_date = date('Y-m-d');
            if (isset($filter_key) && ($filter_key == 'days')) {
                $from_date = date('Y-m-d', strtotime('-' . $filter_vals . '  days', strtotime($to_date)));
                $filter_type = 'date';
                $filter_val = ['from_date' => $from_date, 'to_date' => $to_date, 'order_status' => (isset($filter_status) && $filter_status != '') ? $filter_status : ''];
                $filter_titles = "Last " . $filter_vals . " days ago";
            } else if (isset($filter_key) && ($filter_key == 'months')) {
                $from_date = date('Y-m-d', strtotime('-' . $filter_vals . '  month', strtotime($to_date)));
                $filter_type = 'date';
                $filter_val = ['from_date' => $from_date, 'to_date' => $to_date, 'order_status' => (isset($filter_status) && $filter_status != '') ? $filter_status : ''];
                $filter_titles = "Last " . $filter_vals . " months ago";
            } else if (isset($filter_key) && ($filter_key == 'years')) {
                $from_date = date('Y-01-01', strtotime($filter_vals . '-01-01'));
                $filter_type = 'year';
                $filter_val = ['year' => $filter_vals, 'order_status' => (isset($filter_status) && $filter_status != '') ? $filter_status : ''];
                $filter_titles = "Last " . $filter_vals . " years";
            } else {
                $filter_type = 'date';
                $from_date = date('Y-m-d', strtotime('-6 month', strtotime($to_date)));
                $filter_val = ['from_date' => $from_date, 'to_date' => $to_date, 'order_status' => (isset($filter_status) && $filter_status != '') ? $filter_status : ''];
                $filter_titles = "Last 6 months ago";
            }
            $condArr = [];
            $condArr["user_id"] = $userId;
            if ($filter_type == 'date') {
                $condArr["DATE_FORMAT(created_at,'%Y-%m-%d') >="] = $filter_val['from_date'];
                $condArr["DATE_FORMAT(created_at,'%Y-%m-%d') <="] = $filter_val['to_date'];
            } else if ($filter_type == 'year') {
                $condArr["DATE_FORMAT(created_at,'%Y')"] = $filter_val['year'];
            }
            //pr($filter_val['order_status']); exit;
            if (isset($filter_val['order_status']) && $filter_val['order_status'] != '') {
                $condArr["status"] = $filter_val['order_status'];
            }
            $orderLists = [];
            $orders = $this->Orders->find('all')->select(['cart_id', 'status', 'order_id', 'created_at'])->where($condArr)->order(['created_at' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            if (!empty($orders)) {
                foreach ($orders as $val) {
                    $tmp = [];
                    $serviceArr = $this->CartOrders->find('all')->select(['service_id'])->where(['cart_id' => $val['cart_id']])->hydrate(false)->first();
                    $service_id = $serviceArr['service_id'];
                    $tmp['name'] = $this->Services->getServiceName($service_id);
                    $tmp['images'] = $this->Services->getServiceImagePath($service_id);
                    $tmp['status'] = $val['status'];
                    $tmp['order_id'] = $val['order_id'];
                    $tmp['date'] = $val['created_at']->format('d-M-Y');
                    $orderLists[] = $tmp;
                }
            }
            $nextPageOrders = $this->Orders->find('all')->select(['cart_id', 'status', 'order_id', 'created_at'])->where($condArr)->order(['created_at' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
            $next_page = (!empty($nextPageOrders)) ? true : false;
            $this->success('orders fetched successfully', ["filter_name" => $filter_titles, "orders" => $orderLists, "next_page" => $next_page]);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function orderQuery() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Orders');
            $this->loadModel('OrderQueries');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
                'Query' => (isset($requestArr['query']) && $requestArr['query'] != '') ? $requestArr['query'] : '',
                'vendor_on_time' => (isset($requestArr['vendor_on_time']) && $requestArr['vendor_on_time'] != '') ? $requestArr['vendor_on_time'] : '',
                'vendor_with_uniform_id' => (isset($requestArr['vendor_with_uniform_id']) && $requestArr['vendor_with_uniform_id'] != '') ? $requestArr['vendor_with_uniform_id'] : '',
                'vendor_work_knowledge' => (isset($requestArr['vendor_work_knowledge']) && $requestArr['vendor_work_knowledge'] != '') ? $requestArr['vendor_work_knowledge'] : '',
                'work_cleanliness' => (isset($requestArr['work_cleanliness']) && $requestArr['work_cleanliness'] != '') ? $requestArr['work_cleanliness'] : '',
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $query_txt = $requestArr['query'];
            $vendor_on_time = $requestArr['vendor_on_time'];
            $vendor_with_uniform_id = $requestArr['vendor_with_uniform_id'];
            $vendor_work_knowledge = $requestArr['vendor_work_knowledge'];
            $work_cleanliness = $requestArr['work_cleanliness'];
            $checkOrderExist = $this->Orders->find('all')->select(['id', 'service_id'])->where(['order_id' => $order_id])->hydrate(false)->first();
            if ($checkOrderExist) {
                $orders_id = $checkOrderExist['id'];
                $service_id = $checkOrderExist['service_id'];
                $query = $this->OrderQueries->newEntity();
                $queryData['user_id'] = $userId;
                $queryData['order_id'] = $orders_id;
                $queryData['vendor_on_time'] = $vendor_on_time;
                $queryData['vendor_with_uniform_id'] = $vendor_with_uniform_id;
                $queryData['vendor_work_knowledge'] = $vendor_work_knowledge;
                $queryData['work_cleanliness'] = $work_cleanliness;
                $queryData['queries'] = $query_txt;
                $query = $this->OrderQueries->patchEntity($query, $queryData);
                $query->created = date('Y-m-d H:i:s');
                $query->modified = date('Y-m-d H:i:s');
                if ($this->OrderQueries->save($query)) {
                    $this->wrong('Query is Submitted Successfully!');
                } else {
                    $this->wrong('Query is not Submitted!');
                }
            } else {
                $this->wrong('Order id is invalid.');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function orderSummary() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Orders');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $onGoingOrdercount = 0;
            $cartProductCount = 0;
            // Ongoing Orders - Start
            $onGoingOrdercount = $this->Orders->find()->where(['user_id' => $userId, 'status' => 'SCHEDULE'])->hydrate(false)->count();
            // Ongoing Orders - End
            // Cart Product - Start
            $checkArrs = $this->Carts->find('all')->select(['id'])->where(['user_id' => $userId, 'status' => 'PROCESS'])->hydrate(false)->first();
            //pr($checkArrs); exit;
            if (isset($checkArrs) && !empty($checkArrs)) {
                $cartId = $checkArrs['id'];
                $cartProductCount = $this->CartOrders->find()->where(['user_id' => $userId, 'cart_id' => $cartId])->hydrate(false)->count();
            }
            // Cart Product - End
            $this->success('Order Summary Fatched!', ['totOngoingOrder' => $onGoingOrdercount, 'totCart' => $cartProductCount]);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function storeReview() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('ServiceReviews');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Service Id' => (isset($requestArr['service_id']) && $requestArr['service_id'] != '') ? $requestArr['service_id'] : '',
                'Review Title' => (isset($requestArr['review_title']) && $requestArr['review_title'] != '') ? $requestArr['review_title'] : '',
                'Review Description' => (isset($requestArr['review_description']) && $requestArr['review_description'] != '') ? $requestArr['review_description'] : '',
                'Review Rattings' => (isset($requestArr['review_rates']) && $requestArr['review_rates'] != '') ? $requestArr['review_rates'] : '',
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $serviceReview = $this->ServiceReviews->newEntity();
            $review = [];
            $review['user_id'] = $userId;
            $review['service_id'] = $requestArr['service_id'];
            $review['review_title'] = $requestArr['review_title'];
            $review['review_description'] = $requestArr['review_description'];
            $review['review_rates'] = $requestArr['review_rates'];
            $serviceReview = $this->ServiceReviews->patchEntity($serviceReview, $review);
            $serviceReview->created = date('Y-m-d H:i:s');
            if ($this->ServiceReviews->save($serviceReview)) {
                $this->success('Review Submited!');
            } else {
                $this->wrong('Review not Submited!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function updateOrder() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Orders');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $orderExist = $this->Orders->find('all')->where(['order_id' => $order_id, 'user_id' => $userId])->hydrate(false)->first();
            if (isset($orderExist) && !empty($orderExist)) {
                $order = $this->Orders->get($orderExist['id']);
                $updatedData['status'] = (isset($requestArr['status']) && $requestArr['status'] != '') ? $requestArr['status'] : '';
                $updatedData['reason_order_cancelled'] = (isset($requestArr['reason']) && $requestArr['reason'] != '') ? $requestArr['reason'] : '';
                $updatedData['user_id'] = $userId;
                $order = $this->Orders->patchEntity($order, $updatedData);
                $order->modified_at = date('Y-m-d H:i:s');
                if ($this->Orders->save($order)) {
                    $this->success('Order Updated!');
                } else {
                    $this->wrong('Order Updated Faild!');
                }
            } else {
                $this->wrong('Sorry Order is not Exist!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    //public function testNotifications($title, $msg, $url = '', $user = 'All') {
    public function testNotifications() {
        $title = 'Title 1';
        //$msg = 'Lorem lipsum is dummy text';
        $msg = 'Message Aviyo?';
        //$url = 'http://localhost/www/#/tab/message';
        $url = '';
        $user = '806e3158-f53b-46d0-85a1-3b257c25610d';
        $response = $this->sendMessage($title, $msg, $url, $user);
        pr($response);
        exit;
        $return["allresponses"] = $response;
        $return = json_encode($return);

        print("\n\nJSON received:\n");
        print($return);
        print("\n");
    }

    public function surverysubmit() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $requestArr = $this->getInputArr();
            $serveyArrs = $this->getNewSurveyIds();
            if (!empty($serveyArrs)) {
                $surveys = $this->Surveys->newEntity();
                $appoinment_date = $requestArr['appoinment_date'];
                unset($requestArr['appoinment_date']);
                $surveys = $this->Surveys->patchEntity($surveys, $requestArr);
                $surveys->ids = $serveyArrs['ids'];
                $surveys->survey_id = $serveyArrs['survey_id'];
                $surveys->appoinment_date = date("Y-m-d", strtotime($appoinment_date));
                $surveys->created_by = $user_id;
                $surveys->created = date("Y-m-d H:i:s");
                $rslt = $this->Surveys->save($surveys);
                if ($rslt->id) {
                    $this->success('Survey Added!', ['id' => $rslt->id]);
                } else {
                    $this->wrong(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->wrong(Configure::read('Settings.FAIL'));
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function surverylists() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $surveyLists = $this->Surveys->find('all')->select(['person_name', 'user_type'])->where(['created_by' => $user_id, "DATE_FORMAT(created,'%Y-%m-%d')" => date('Y-m-d')])->order(['id' => 'DESC'])->hydrate(false)->toArray();
            //pr($surveyLists); exit;
            if (!empty($surveyLists)) {
                $this->success('Survey List!', $surveyLists);
            } else {
                $this->success('Survey List Empty!', []);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function serviceLists() {
        $this->loadModel('Services');
        $services = $this->getSurveyServiceLists();
        if (!empty($services)) {
            $this->success('Services List!', $services);
        } else {
            $this->success('Services List Empty!', []);
        }
    }

    public function planLists() {
        $this->loadModel('Plans');
        $plans = $this->Plans->find('all')->select(['id', 'name', 'price'])->hydrate(false)->toArray();
        if (!empty($plans)) {
            foreach ($plans as $key => $val) {
                $tax = '';
                $tax = $val['price'] * GST_TAX / 100;
                $plans[$key]['tax'] = $tax;
                $plans[$key]['total'] = $val['price'] + $tax;
            }
            $this->success('Plan List!', $plans);
        } else {
            $this->success('Plan List Empty!', []);
        }
    }

    public function referenceUsers() {
        $this->loadModel('Users');
        $referUsers = $this->Users->find('all')->select(['id', 'name', 'phone_no'])->where(['user_type' => 'MEMBERSHIP'])->hydrate(false)->toArray();
        $referLists = [];
        if (!empty($referUsers)) {
            foreach ($referUsers as $tuser) {
                $tmp = [];
                $tmp['id'] = $tuser['id'];
                $tmp['name'] = $tuser['name'] . " | " . $tuser['phone_no'];
                $referLists[] = $tmp;
            }
        }
        if (!empty($referLists)) {
            $this->success('Reference List!', $referLists);
        } else {
            $this->success('Reference List Empty!', []);
        }
    }

    public function addMembership() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Users');
            $this->loadModel('UserDetails');
            $this->loadModel('Wallets');
            $this->loadModel('Plans');
            $user = $this->Users->newEntity();
            $requestArr = $this->getInputArr();
            //pr($requestArr); exit;
            $myfile = fopen("newfileMember.txt", "w") or die("Unable to open file!");
            $txt = "Array\n";
            fwrite($myfile, $txt);
            fwrite($myfile, file_get_contents('php://input'));
            fwrite($myfile, "Array\n");
            fwrite($myfile, print_r($requestArr, TRUE));
            fclose($myfile);
            if (!isset($requestArr['plan_id']) || $requestArr['plan_id'] == '') {
                $this->wrong('Sorry, Please select plan.');
            }
            $validator = new UsersValidator();
            $usersController = new UsersController();
            $requestArr['password'] = $usersController->randomPassword();
            $errors = $validator->errors($requestArr);
            if (empty($errors)) {
                $memberIdArrs = $this->getNewMemberIds();
                if (!empty($memberIdArrs)) {
                    $name = $requestArr['name'];
                    $email = $requestArr['email'];
                    $phone_no = $requestArr['phone_no'];
                    //echo $email. " ".$phone_no; exit;
                    $userExists = $this->uniqueEmailOrPhone($email, $phone_no);
                    if (isset($userExists['status']) && $userExists['status'] == 'fail') {
                        $this->wrong($userExists['msg']);
                    } else {
                        $uController = new UsersController();
                        $password = $uController->randomPassword();
                        $userData = [];
                        $userData['name'] = $name;
                        $userData['email'] = $email;
                        $userData['phone_no'] = $phone_no;
                        $userData['password'] = $password;
                        $userData['address'] = (isset($requestArr['address']) && $requestArr['address'] != '') ? $requestArr['address'] : '';
                        $userData['city'] = (isset($requestArr['city']) && $requestArr['city'] != '') ? $requestArr['city'] : '';
                        $userData['signup_with'] = 'SELF';
                        $userData['user_type'] = 'MEMBERSHIP';
                        $userData['plan_id'] = $plan_id = (isset($requestArr['plan_id']) && $requestArr['plan_id'] != '') ? $requestArr['plan_id'] : '';
                        $userData['ip_address'] = (isset($requestArr['ip_address']) && $requestArr['ip_address'] != '') ? $requestArr['ip_address'] : '';
                        $userData['refer_key'] = $this->getReferKey($name, $phone_no);
                        $userData['referral_id'] = (isset($requestArr['referral_id']) && $requestArr['referral_id'] != '') ? $requestArr['referral_id'] : 0;
                        $userData['email_newsletters'] = 'Y';
                        $userData['phone_verified'] = 'Y';
                        $userData['email_verified'] = 'Y';
                        $userData['active'] = (isset($requestArr['active']) && $requestArr['active'] != '') ? $requestArr['active'] : 'N';
                        $user = $this->Users->patchEntity($user, $userData);
                        $user->ids = $memberIdArrs['ids'];
                        $user->membership_id = $memberIdArrs['membership_id'];
                        $paymentType = (isset($requestArr['payment_type']) && $requestArr['payment_type'] != '') ? $requestArr['payment_type'] : '';
                        $bankName = (isset($requestArr['bank_name']) && $requestArr['bank_name'] != '') ? $requestArr['bank_name'] : '';
                        $chequeNo = (isset($requestArr['cheque_no']) && $requestArr['cheque_no'] != '') ? $requestArr['cheque_no'] : '';
                        $transcationId = (isset($requestArr['transcation_id']) && $requestArr['transcation_id'] != '') ? $requestArr['transcation_id'] : '';
                        $otherDetails = (isset($requestArr['other_details']) && $requestArr['other_details'] != '') ? $requestArr['other_details'] : '';
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
                        if ($paymentType == 'OTHER') {
                            $msgT = '';
                            $sendMsg = [];
                            $msgT = "Congratulation, your payment in process. We will update you within 3-4 working days once it is clear. Membership ID: " . $memberIdArrs['membership_id'] . ". Hmen Services.";
                            $sendMsg = $this->sendOtp($phone_no, $msgT);
                            if ($sendMsg['status'] == 'fail') {
                                $this->wrong($sendMsg['msg']);
                            }
                        }
                        // SEND SMS 
                        $msgT = '';
                        $sendMsg = [];
                        $msgT = "Dear $name, Your Hmen Account access Email address: $email and  Password: $password You can login after your payment clearance. Regards, Hmen Service.";
                        $sendMsg = $this->sendOtp($phone_no, $msgT);
                        if ($sendMsg['status'] == 'fail') {
                            $this->wrong($sendMsg['msg']);
                        }
                        // SEND EMAIL
                        $this->sentEmails($name, $email, $password);
                        if (isset($requestArr['birthdate']) && $requestArr['birthdate'] != '') {
                            $user->birthdate = date('Y-m-d', strtotime($requestArr['birthdate']));
                        }
                        if (isset($requestArr['aniversary_date']) && $requestArr['aniversary_date'] != '') {
                            $user->aniversary_date = date('Y-m-d', strtotime($requestArr['aniversary_date']));
                        }
                        $user->created = date("Y-m-d H:i:s");
                        $user->created_by = $user_id;
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
                                $userData['occupation'] = (isset($requestArr['occupation']) && $requestArr['occupation'] != '') ? $requestArr['occupation'] : '';
                                $userData['company_name'] = (isset($requestArr['company_name']) && $requestArr['company_name'] != '') ? $requestArr['company_name'] : '';
                                $userData['company_website'] = (isset($requestArr['company_website']) && $requestArr['company_website'] != '') ? $requestArr['company_website'] : '';
                                $userData['payment_type'] = $paymentType;
                                $userData['bank_name'] = $bankName;
                                $userData['cheque_no'] = $chequeNo;
                                $userData['transcation_id'] = $transcationId;
                                $userData['other_details'] = $otherDetails;
                                $person = [];
                                $birthdate = [];
                                $tindex = 1;
                                foreach ($requestArr['persons'] as $key => $val) {
                                    if ($tindex < 6) {
                                        $person[$tindex] = $val->person;
                                        $birthdate[$tindex] = $val->birthdate;
                                    }
                                    $tindex++;
                                }
                                $userData['person_1'] = (isset($person[1]) && $person[1] != '') ? $person[1] : '';
                                $userData['person_2'] = (isset($person[2]) && $person[2] != '') ? $person[2] : '';
                                $userData['person_3'] = (isset($person[3]) && $person[3] != '') ? $person[3] : '';
                                $userData['person_4'] = (isset($person[4]) && $person[4] != '') ? $person[4] : '';
                                $userData['person_5'] = (isset($person[5]) && $person[5] != '') ? $person[5] : '';
                                $users = $this->UserDetails->patchEntity($users, $userData);
                                $users->birthdate_1 = (isset($birthdate[1]) && $birthdate[1] != '') ? date('Y-m-d', strtotime($birthdate[1])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_2 = (isset($birthdate[2]) && $birthdate[2] != '') ? date('Y-m-d', strtotime($birthdate[2])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_3 = (isset($birthdate[3]) && $birthdate[3] != '') ? date('Y-m-d', strtotime($birthdate[3])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_4 = (isset($birthdate[4]) && $birthdate[4] != '') ? date('Y-m-d', strtotime($birthdate[4])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->birthdate_5 = (isset($birthdate[5]) && $birthdate[5] != '') ? date('Y-m-d', strtotime($birthdate[5])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->cheque_date = (isset($requestArr['cheque_date']) && $requestArr['cheque_date'] != '') ? date('Y-m-d', strtotime($requestArr['cheque_date'])) : date('Y-m-d', strtotime('1980-01-01'));
                                $users->created = date("Y-m-d H:i:s");
                                $users->created_by = $user_id;
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
                                    $this->success(__('THE MEMBER HAS BEEN SAVED.'));
                                } else {
                                    $this->wrong(__('UNABLE TO ADD THE MEMBER.'));
                                }
                            } else {
                                $this->wrong(__('UNABLE TO ADD THE MEMBER.'));
                            }
                        }
                    }
                }
            } else {
                $this->wrong('Validation Error!', $errors);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

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

    public function uniqueEmailOrPhone($email, $phone) {
        $userTable = TableRegistry::get('Users');
        if ($email != "") {
            $user = $userTable->find('all')->where(['email' => $email])->hydrate(false)->first();
            if (isset($user) && !empty($user)) {
                $msg = 'Account with Email ID ' . $email . ' already exist!';
                if ($user['active'] == 'N') {
                    $msg = 'Account with Email ID ' . $email . ' already created but account is inactive!';
                }
                return ['status' => 'fail', 'msg' => $msg];
            }
        }
        if ($phone != "") {
            $user = $userTable->find('all')->where(['phone_no' => $phone])->hydrate(false)->first();
            if (isset($user) && !empty($user)) {
                $msg = 'Account with phone number ' . $phone . ' already exist!';
                if ($user['active'] == 'N') {
                    $msg = 'Account with phone number ' . $phone . ' already created but account is inactive!';
                }
                return ['status' => 'fail', 'msg' => $msg];
            }
        }
        return true;
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

    public function listMembership() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Users');
            $userLists = $this->Users->find('all')->select(['name', 'plan_id'])->where(['created_by' => $user_id, 'user_type' => 'MEMBERSHIP', "DATE_FORMAT(created,'%Y-%m-%d')" => date('Y-m-d')])->order(['id' => 'DESC'])->hydrate(false)->toArray();
            if (!empty($userLists)) {
                $users = [];
                foreach ($userLists as $key => $val) {
                    $tmp = [];
                    $tmp['name'] = $val['name'];
                    $tmp['plan'] = $this->getPlanName($val['plan_id']);
                    $users[] = $tmp;
                }
                $this->success('User List!', $users);
            } else {
                $this->success('User List Empty!', []);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function getPlanName($plan_id) {
        $plansTable = TableRegistry::get('Plans');
        $planDetails = $plansTable->find()->select(['name'])->where(['id' => $plan_id])->hydrate(false)->first();
        return (isset($planDetails['name']) && $planDetails['name'] != '') ? ucfirst(strtolower($planDetails['name'])) : '-';
    }

    public function appoinmentLists() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $appoinmentLists = $this->Surveys->find('all')->select(['id', 'person_name', 'appoinment_date', 'appoinment_time'])->where(['created_by' => $user_id, 'appoinment_status' => 'PENDING', "DATE_FORMAT(created,'%Y-%m-%d') <=" => date('Y-m-d')])->hydrate(false)->toArray();
            if (!empty($appoinmentLists)) {
                $appoinments = [];
                foreach ($appoinmentLists as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['name'] = $val['person_name'];
                    $tmp['time'] = $val['appoinment_date']->format('Y-m-d') . " " . $val['appoinment_time'];
                    $appoinments[] = $tmp;
                }
                $this->success('Appoinmnet List!', $appoinments);
            } else {
                $this->success('Appoinmnet List Empty!', []);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function appoinmentDetails() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['survey_id']) && $requestArr['survey_id'] != '') {
                $appoinmentDetails = $this->Surveys->find('all')->select(['id', 'person_name', 'address', 'contact_number', 'appoinment_date', 'appoinment_time'])->where(['id' => $requestArr['survey_id']])->hydrate(false)->first();
                if (is_array($appoinmentDetails) && !empty($appoinmentDetails)) {
                    $appoinmentDetails['time'] = $appoinmentDetails['appoinment_date']->format('d-m-Y') . " " . $appoinmentDetails['appoinment_time'];
                    unset($appoinmentDetails['appoinment_date']);
                    unset($appoinmentDetails['appoinment_time']);
                    $this->success('Survey Data Found!', $appoinmentDetails);
                } else {
                    $this->wrong(__('SURVEY DATA IS NOT FOUND'));
                }
            } else {
                $this->wrong(__('UNABLE TO VIEW THE APPOINMENT.'));
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function appoinmentCompleted() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['survey_id']) && $requestArr['survey_id'] != '') {
                $appoinmentDetails = $this->Surveys->get($requestArr['survey_id']);
                if (!empty($appoinmentDetails)) {
                    $updateValue['appoinment_status'] = 'ACCEPTED';
                    $appoinmentDetails = $this->Surveys->patchEntity($appoinmentDetails, $updateValue);
                    $appoinmentDetails->modified = date("Y-m-d H:i:s");
                    $appoinmentDetails->modified_by = $user_id;
                    if ($this->Surveys->save($appoinmentDetails)) {
                        $this->success('APPOINMENT IS COMPLETED');
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->wrong(__('UNABLE TO FOUND THE APPOINMENT.'));
                }
            } else {
                $this->wrong(__('UNABLE TO COMPLETE THE APPOINMENT.'));
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function appoinmentDeclined() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['survey_id']) && $requestArr['survey_id'] != '') {
                $appoinmentDetails = $this->Surveys->get($requestArr['survey_id']);
                if (!empty($appoinmentDetails)) {
                    $updateValue['appoinment_status'] = 'DECLINED';
                    $appoinmentDetails = $this->Surveys->patchEntity($appoinmentDetails, $updateValue);
                    $appoinmentDetails->modified = date("Y-m-d H:i:s");
                    $appoinmentDetails->modified_by = $user_id;
                    if ($this->Surveys->save($appoinmentDetails)) {
                        $this->success('APPOINMENT IS DECLINED');
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->wrong(__('UNABLE TO FOUND THE APPOINMENT.'));
                }
            } else {
                $this->wrong(__('UNABLE TO COMPLETE THE APPOINMENT.'));
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

}
