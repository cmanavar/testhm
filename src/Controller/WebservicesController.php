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
            'cartOrderPlaced', 'forgorPassword', 'changePassword', 'changeVandorPassword', 'walletDetails', 'getCartId', 'orderDetails',
            'orderLists', 'orderQuery', 'orderSummary', 'storeReview', 'updateOrder', 'serviceReviews', 'getquestionArr', 'surverysubmit', 'surverylists',
            'serviceLists', 'addMembership', 'planLists', 'referenceUsers', 'listMembership', 'appoinmentLists', 'appoinmentDetails',
            'appoinmentCompleted', 'appoinmentDeclined', 'appoinmentInterested', 'assignedorders', 'assignorderdetails', 'orderRequest',
            'vendorOrderUpdate', 'vendorJobCounts', 'vendorJobLists', 'vendorJobDetails', 'vendorReviewsDetails', 'vendorOrderLists', 'packageServiceBook',
            'creditOrderHistory', 'membershipDetails', 'getServiceLists', 'testNotifications']);
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
                    } else if ($message['msg_type'] == 'ORDER') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-order.svg';
                    } else if ($message['msg_type'] == 'REFERRAL') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-referral.png';
                    } else if ($message['msg_type'] == 'CASHBACK') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-cashback.png';
                    } else if ($message['msg_type'] == 'GREENCASH') {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-cashback.png';
                    } else {
                        $tmp['image'] = IMAGE_URL_PATH . 'icons/msg-other.svg';
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
                $resp_data = ['unseen_count' => $unseenCount, 'messages' => $msgList];
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
            if (isset($requestArr['message_ids']) && !empty($requestArr['message_ids'])) {
                $msgIdArr = $requestArr['message_ids'];
                $msgarr = [];
                foreach ($msgIdArr as $id) {
                    $msg = [];
                    $msg = $this->Messages->get($id); //LISTING USERDATA
                    $updateFields = ['seen' => 'Y'];
                    $msg = $this->Messages->patchEntity($msg, $updateFields);
                    $msg->modified_by = $user_id;
                    $msg->modified = date("Y-m-d H:i:s");
                    if ($this->Messages->save($msg)) {
                        
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
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
        //pr($userArr); exit;
        if (!empty($userArr)) {
            $mailData = [];
            $mailData['name'] = $userArr['name'];
            $mailData['email'] = $userArr['email'];
            $mailData['activation_link'] = APP_PATH . 'reset/password/' . base64_encode($userArr['email']);
            //pr($mailData); exit;
            $this->set('mailData', $mailData);
            $view_output = $this->render('/Element/forgot_pass_email');
            $fields = array(
                'msg' => $view_output,
                'tomail' => $userArr['email'],
                'subject' => 'Reset Password',
                'from_name' => EMAIL_FROM_NAME,
                'from_mail' => EMAIL_FROM_EMAIL_ADDRESS,
            );
            $rslt = $this->sendemails($fields);
            $this->success('Mail Send!');
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
                        $this->success('Your Password is updated successfully!');
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

    public function changeVandorPassword() {
        $user_id = $this->checkVerifyApiKey('SALES_VENDOR');
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
                        $this->success('Your Password is updated successfully!');
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
                $tServices = $this->Services->find('all')->select(['id', 'service_name', 'service_description', 'service_specification', 'visit_charge', 'minimum_charge', 'square_image'])->where(['status' => 'ACTIVE', 'category_id' => $val['id']])->order(['id' => 'ASC'])->hydrate(false)->toArray();
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
                        $tmpS['banner_image'] = IMAGE_URL_PATH . 'services/square/' . $v['square_image'];
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
                $tServices = $this->Services->find('all')->select(['id', 'service_name', 'service_description', 'service_specification', 'visit_charge', 'minimum_charge', 'banner_image', 'square_image'])->where(['status' => 'ACTIVE', 'category_id' => $id])->order(['id' => 'ASC'])->hydrate(false)->toArray();
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
                    $tmp['banner_image'] = IMAGE_URL_PATH . 'services/square/' . $v['square_image'];
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
        //$requestArr = $this->getInputArr();
        if (file_get_contents('php://input') != '') {
            $requestArr = get_object_vars(json_decode(file_get_contents('php://input')));
            if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
                $page_no = $requestArr['page_no'];
            } else {
                $page_no = 1;
            }
        } else {
            $page_no = 1;
        }
        $this->loadModel('Users');
        $this->loadModel('Services');
        $this->loadModel('ServiceReviews');
        $avgReviews = 0;
        if (isset($id) && $id != '') {

            $rslt = [];
            $sDetails = $this->Services->find('all')->where(['status' => 'ACTIVE', 'id' => $id])->order(['id' => 'ASC'])->hydrate(false)->first();
            //pr($sDetails); exit;
            if (isset($sDetails) && !empty($sDetails)) {
                // Count Average Rating - Start
                $sumReviews = $this->ServiceReviews->find('all')->select(['review_rates'])->where(['service_id' => $sDetails['id']])->hydrate(false)->toArray();
                $countReviews = count($sumReviews);
                $totReviews = 0;
                foreach ($sumReviews as $reviews) {
                    $totReviews += $reviews['review_rates'];
                }
                if ($countReviews != 0) {
                    $avgReviews = $totReviews / $countReviews;
                } else {
                    $avgReviews = 0.00;
                }
                //pr(number_format($avgReviews, 2));
                //exit;
                // Count Average Rating - End
                // Review - Start
                $reviewsArr = [];
                $reviews = $this->ServiceReviews->find('all')->where(['service_id' => $sDetails['id']])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
                //pr($reviews); exit;
                foreach ($reviews as $review) {
                    //pr($review['user_id']); exit;
                    $tmpArr = $userData = [];
                    $tmpArr['review_title'] = $review['review_title'];
                    $tmpArr['review_description'] = $review['review_description'];
                    $tmpArr['review_rates'] = $review['review_rates'];
                    //$userData = $this->Users->getuserId($review['user_id'])->toArray();
                    //pr($userData); exit;
                    $tmpArr['service_name'] = $this->Services->getServiceName($id);
                    $tmpArr['user_name'] = $this->getUserName($review['user_id']);
                    $tmpArr['user_pic'] = $this->getUserProfilePicture($review['user_id']);
                    $reviewsArr[] = $tmpArr;
                }
                //pr($reviewsArr); exit;
                $nextPageReviews = $this->ServiceReviews->find('all')->where(['service_id' => $sDetails['id']])->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
                $rslt['service_reveiws'] = $reviewsArr;
                $rslt['average_reviews'] = number_format($avgReviews, 2);
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

//    public function createCart() {
//        $user_id = $this->checkVerifyApiKey('CUSTOMER');
//        if ($user_id) {
//            $this->loadModel('Carts');
//            $this->loadModel('Services');
//            $requestArr = $this->getInputArr();
//            // Check Cart is already Exist or not
//            $checkArrs = $this->Carts->find('all')->where(['user_id' => $user_id, 'status' => 'PROCESS'])->hydrate(false)->first();
//            if (empty($checkArrs)) {
//                $checkArr = $this->Carts->find('all')->where(['user_id' => $user_id, 'status' => 'PROCESS'])->hydrate(false)->first();
//                if (empty($checkArr)) {
//                    $carts = $this->Carts->newEntity();
//                    $cartArr = ['user_id' => $user_id, 'status' => 'PROCESS'];
//                    $carts = $this->Carts->patchEntity($carts, $cartArr);
//                    $carts->created = date("Y-m-d H:i:s");
//                    $carts->modified = date("Y-m-d H:i:s");
//                    $rslt = $this->Carts->save($carts);
//                    if ($rslt->id) {
//                        $this->success('Cart created!', ['id' => $rslt->id]);
//                    } else {
//                        $this->wrong(Configure::read('Settings.FAIL'));
//                    }
//                } else {
//                    echo json_encode(['status' => 'fail', 'msg' => 'Cart already Exist!', 'data' => ['id' => $checkArr['id']]]);
//                    exit;
//                    //$this->success('Cart already Exist!', ['id' => $checkArr['id']]);
//                }
//            } else {
//                echo json_encode(['status' => 'fail', 'msg' => 'Sorry, Your cart is already in process!', 'data' => ['id' => $checkArrs['id']]]);
//                exit;
//            }
//        } else {
//            $this->wrong('Invalid API key.');
//        }
//    }

    public function createCart() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Carts');
            $this->loadModel('Services');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['service_id']) && $requestArr['service_id'] != '') {
                $service_id = $requestArr['service_id'];
            } else {
                $this->wrong('Sorry, Service id is missing!');
            }
// Check Cart is already Exist or not
            $checkArrs = $this->Carts->find('all')->where(['user_id' => $user_id, 'service_id !=' => $service_id, 'status' => 'PROCESS'])->hydrate(false)->first();
            if (empty($checkArrs)) {
                $checkArr = $this->Carts->find('all')->where(['user_id' => $user_id, 'service_id' => $service_id, 'status' => 'PROCESS'])->hydrate(false)->first();
                if (empty($checkArr)) {
                    $carts = $this->Carts->newEntity();
                    $category_id = $this->Services->getCategoryIdusingServiceId($service_id);
                    $cartArr = ['user_id' => $user_id, 'category_id' => $category_id, 'service_id' => $service_id, 'status' => 'PROCESS'];
                    $carts = $this->Carts->patchEntity($carts, $cartArr);
                    $carts->created = date("Y-m-d H:i:s");
                    $rslt = $this->Carts->save($carts);
                    if ($rslt->id) {
                        $this->success('Cart created!', ['id' => $rslt->id]);
                    } else {
                        $this->wrong(Configure::read('Settings.FAIL'));
                    }
                } else {
                    $this->success('Cart already Exist!', ['id' => $checkArr['id']]);
                }
            } else {
                $serviceName = $this->Services->getServiceName($checkArrs['service_id']);
                echo json_encode(['status' => 'fail', 'msg' => 'Sorry, Your cart is already in process, please cancelled it.', 'data' => ['cart_id' => $checkArrs['id'], 'service_id' => $checkArrs['service_id'], 'service_name' => $serviceName]]);
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
            $requiredFields = [
                'Cart Id' => $cartId,
                'Service Id' => $serviceId
            ];
            $categoryId = $this->getCategoryId($serviceId);
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
//pr($questionsData); exit;
                        if (empty($questionsData)) {
                            $this->wrong("Sorry, Questions data is not found!");
                        } else {
                            $total_price = 0;
                            foreach ($questionsData as $questions) {
                                $questionDetails = $questionStoreDetails = [];
                                $answer_id = $questions->option_id;
                                $getAnswerData = $this->ServiceQuestionAnswers->find('all')->where(['id' => $answer_id])->hydrate(false)->first();
//pr($questions); exit;
                                $question_id = $getAnswerData['question_id'];
//$answer_id = $questions->option_id;
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
//                                    continue;
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
            $category_id = '';
            foreach ($cartOrders as $order) {
                if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                    if ($order['total_amount'] == 0) {
                        continue;
                    }
                }
                $tmp = [];
                $tmp['cart_order_id'] = $order['id'];
                $tmp['category_id'] = $category_id = $order['category_id'];
                $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                $tmp['service_id'] = $order['service_id'];
                $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                $tmp['banner_img'] = $this->Services->getServiceImagePAth($order['service_id']);
                $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $order['id']])->hydrate(false)->toArray();
                $serviceDesc = '';
                foreach ($tmpDetails as $orderQues) {
                    $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
                    if (isset($order['on_inspections']) && $order['on_inspections'] == 'N') {
                        $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                        $tmp['serviceDescription'] = trim($serviceDesc);
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
                        $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                        $tmp['serviceDescription'] = trim($serviceDesc);
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
            $total['on_inspection'] = 'N';
            $total['minimum_charges'] = 'N';
            $total['order_amount'] = 0.00;
            $total['tax'] = 0.00;
            $total['bill_amount'] = 0.00;
            $total['total_amount'] = 0.00;
            $order_amount = 0.00;
            foreach ($ordersDetails as $od) {
                foreach ($od['services'] as $val) {
                    if (isset($val['on_inspection']) && $val['on_inspection'] == 'Y') {
                        $total['on_inspection'] = 'Y';
                    }
                    $totAmount = isset($val['total_amount']) && $val['total_amount'] != '' ? $val['total_amount'] : 0;
                    $order_amount += $totAmount;
                }
            }
            $minimum_charges = $this->Services->getMinimumServiceCharge($category_id);
            $tax = $order_amount * GST_TAX / 100;
            $totals = $order_amount + $tax;
            if (isset($total['on_inspection']) && $total['on_inspection'] != 'Y') {
                if ($totals < $minimum_charges) {
                    $total['order_amount'] = number_format($order_amount, 2);
                    $total['tax'] = number_format($tax, 2);
                    $total['minimum_charges'] = 'Y';
                    $total['bill_amount'] = number_format($totals, 2);
                    $mtax = $minimum_charges * GST_TAX / 100;
                    $mtotals = $minimum_charges + $mtax;
                    $total['total_amount'] = number_format($mtotals, 2);
                } else {
                    $total['order_amount'] = number_format($order_amount, 2);
                    $total['tax'] = number_format($tax, 2);
                    $total['bill_amount'] = number_format($totals, 2);
                    $total['total_amount'] = number_format($totals, 2);
                }
            }
            return ['services' => $finalOrderDetails, 'total' => $total];
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
        if (isset($service_questions['question_title']) && $service_questions['question_title'] != '') {
            $rslt['questions'] = $service_questions['question_title'];
            $condArrA = ['id' => $answer_id];
            $service_answers = $this->serviceQuestionAnswers->find('all')->where($condArrA)->hydrate(false)->first();
            if (isset($service_answers) && !empty($service_answers)) {
                $answerData = $service_answers;
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

    public function cartOrderPlaced() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('Orders');
            $this->loadModel('Carts');
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
            $wallet_amount = 0.00;
            $wallet_amount = isset($requestArr['wallet_amount']) && $requestArr['wallet_amount'] != '' ? $requestArr['wallet_amount'] : '';
            $cartExist = $this->Carts->find('all')->where(['id' => $requestArr['cart_id'], 'status' => 'PROCESS'])->hydrate(false)->first();
            if ($cartExist) {
                //pr($cartExist); exit;
                $order = $this->Orders->newEntity();
                $orderData = [];
                $orderData['user_id'] = $user_id;
                $orderData['category_id'] = $cartExist['category_id'];
                $orderData['service_id'] = $cartExist['service_id'];
                $usertype = $this->getUserType($user_id);
                //echo $usertype; exit;
                $orderData['cart_id'] = $cart_id;
                $orderData['order_id'] = $this->orderIdCreate();
                $orderData['user_address'] = $requestArr['user_address'];
                $orderData['schedule_time'] = $requestArr['schedule_time'];
                $orderData['on_inspections'] = '';
                $orderData['is_visiting_charge'] = 'N';
                $orderData['amount'] = 0.00;
                $orderData['on_inspections_cost'] = 0.00;
                $orderData['tax'] = 0.00;
                $orderData['total_amount'] = 0.00;
                $cartDetails = $this->totalCartPrice($cart_id);
                $orderData['is_minimum_charge'] = (isset($cartDetails['total']['minimum_charges']) && $cartDetails['total']['minimum_charges'] != 'N') ? $cartDetails['total']['minimum_charges'] : 'N';
                $orderData['on_inspections'] = (isset($cartDetails['total']['on_inspection']) && $cartDetails['total']['on_inspection'] != '') ? $cartDetails['total']['on_inspection'] : 'N';
                $orderData['amount'] = str_replace(",", "", $cartDetails['total']['order_amount']);
                $orderData['tax'] = str_replace(",", "", $cartDetails['total']['tax']);
                //pr($cartDetails); exit;
                $total_amounts = str_replace(",", "", $cartDetails['total']['total_amount']);
                //echo $usertype; exit;
                if ($usertype == "MEMBERSHIP") {
                    $available_walletcash = $this->walletAmount($user_id);
                    if ($available_walletcash != 0) {
                        $plan_id = $this->getPlanId($user_id);
                        $wallet_cash_per = 0;
                        if ($plan_id = RUBIES_PLAN_ID) {
                            $wallet_cash_per = RUBIES_DISCOUNT_RATE;
                        }
                        if ($plan_id = SAPPHIRES_PLAN_ID) {
                            $wallet_cash_per = SAPPHIRES_DISCOUNT_RATE;
                        }
                        if ($wallet_cash_per != 0) {
                            $walletCash = $total_amounts * ($wallet_cash_per / 100);
                            if ($available_walletcash > $walletCash) {
                                $wallet_amount = $walletCash;
                            } else {
                                $wallet_amount = $available_walletcash;
                            }
                        }
                    }
                    //echo $wallet_amount; exit;
                    $orderData['wallet_amount'] = $wallet_amount;
                } else {
                    $orderData['wallet_amount'] = $wallet_amount;
                }
                if ($wallet_amount != 0) {
                    $vW = [];
                    $vW['amount'] = $wallet_amount;
                    $vW['wallet_type'] = 'DEBIT';
                    $vW['purpose'] = 'ORDER';
                    $vW['purpose_id'] = $orderData['order_id'];
                    $walletId = $this->addWalletAmount($user_id, $vW['amount'], $vW['wallet_type'], $vW['purpose'], $vW['purpose_id']);
                    if (!$walletId) {
                        $this->wrong('Wallet Amount Add operation Failed!');
                    }
                }
                $total_amounts = $total_amounts - $wallet_amount;
                //pr($total_amounts); exit;
                $orderData['total_amount'] = str_replace(",", "", $total_amounts);
                $orderData['payable_amount'] = 0.00;
                $orderData['payment_method'] = '';
                if ($usertype == 'CUSTOMER') {
                    $orderData['credits_applied'] = 'N';
                } else {
                    $orderData['credits_applied'] = 'P';
                }
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
                        $this->newMsg($user_id, MSG_TITLE_ORDER_PLACED, MSG_TYPE_ORDER, 'Your Order is placed Successfully. Order Id is #' . $orderData['order_id']);
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
            $this->loadModel('ServiceReviews');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
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
                $orderDetails = [];
                $orderReview = [];
                $orderReview = $this->ServiceReviews->find('all')->where(['order_id' => $order_id])->hydrate(false)->first();
                //pr($orderReview); exit;
                $orderDetails['user_id'] = $order['user_id'];
                $orderDetails['order_id'] = $order['order_id'];
                $orderDetails['user_address'] = $order['user_address'];
                $orderDetails['created_at'] = $order['created_at']->format('d-M-Y h:i A');
                $orderDetails['schedule_date'] = $order['schedule_date']->format('d-M-Y');
                $orderDetails['schedule_time'] = $order['schedule_time'];
                $orderDetails['on_inspections'] = $order['on_inspections'];
                $orderDetails['is_minimum_charge'] = $order['is_minimum_charge'];
                $orderDetails['is_visiting_charge'] = $order['is_visiting_charge'];
                $orderDetails['wallet_amount'] = number_format($order['wallet_amount'], 2);
                $orderDetails['amount'] = number_format($order['amount'], 2);
                $orderDetails['on_inspections_cost'] = number_format($order['on_inspections_cost'], 2);
                $orderDetails['tax'] = number_format($order['tax'], 2);
                $orderDetails['total_amount'] = number_format($order['total_amount'], 2);
                $orderDetails['status'] = $order['status'];
                $orderDetails['payment_status'] = $order['payment_status'];
                $orderDetails['images'] = '';
                $orderDetails['reviews_pending'] = (empty($orderReview)) ? 'Y' : 'N';
                if (isset($order['vendors_id']) && $order['vendors_id'] != 0) {
                    $orderDetails['vendor']['name'] = $this->getUserName($order['vendors_id']);
                    $orderDetails['vendor']['image'] = $this->getUserProfilePicture($order['vendors_id']);
                    $orderDetails['vendor']['phone'] = $this->getPhone($order['vendors_id']);
                }
                $orderDetails['services'] = [];
                if (isset($order['status']) && !empty($order['status'])) {
                    if ($order['status'] == 'SCHEDULE') {
                        $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                    }
                    if ($order['status'] == 'ON_INSPECTION') {
                        $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                    }
                }
                $orderDetails['payment_status'] = $order['payment_status'];
                $orderDetails['images'] = '';
                $orderDetails['services'] = [];
                $orderDetails['total']['amount'] = number_format($order['amount'], 2);
                $orderDetails['total']['tax'] = number_format($order['tax'], 2);
                $orderDetails['total']['wallet_amount'] = number_format($order['wallet_amount'], 2);
                if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') {
                    $sum = $order['amount'] + $order['tax'];
                    $orderDetails['total']['bill_amount'] = number_format($sum, 2);
                }
                $orderDetails['total']['total_amount'] = number_format($order['total_amount'], 2);
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
//                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Paid for Order. id #' . $history['purpose_id'];
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
                if ($filter_val['order_status'] == 'SCHEDULE') {
                    $condArr["status IN"] = ['PENDING', 'PLACED', 'SCHEDULE'];
                } else {
                    $condArr["status"] = $filter_val['order_status'];
                }
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
                    $this->success('Query is Submitted Successfully!');
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
            $onGoingOrdercount = $this->Orders->find()->where(['user_id' => $userId, 'status IN' => ['PENDING', 'PLACED', 'SCHEDULE']])->hydrate(false)->count();
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
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
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
            $review['order_id'] = $requestArr['order_id'];
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
                $updatedData['payment_method'] = (isset($requestArr['payment_method']) && $requestArr['payment_method'] != '') ? $requestArr['payment_method'] : '';
                if (isset($requestArr['status']) && $requestArr['status'] != '') {
                    $updatedData['status'] = $requestArr['status'];
                }
                if (isset($requestArr['reason']) && $requestArr['reason'] != '') {
                    $updatedData['reason_order_cancelled'] = (isset($requestArr['reason']) && $requestArr['reason'] != '') ? $requestArr['reason'] : '';
                }
                $updatedData['user_id'] = $userId;
                $order = $this->Orders->patchEntity($order, $updatedData);
                $order->modified_by = $userId;
                $order->modified_at = date('Y-m-d H:i:s');
                if ($this->Orders->save($order)) {
                    $this->success('Order Update!');
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
                $appoinment_time = $requestArr['appoinment_time'];
                $serviceArr = $requestArr['what_service_or_repair_work_usually_you_perform_at_your_place'];
                unset($requestArr['appoinment_date']);
                unset($requestArr['appoinment_time']);
                unset($requestArr['what_service_or_repair_work_usually_you_perform_at_your_place']);
                $surveys = $this->Surveys->patchEntity($surveys, $requestArr);
                $surveys->what_service_or_repair_work_usually_you_perform_at_your_place = implode(",", $serviceArr);
                $surveys->ids = $serveyArrs['ids'];
                $surveys->survey_id = $serveyArrs['survey_id'];
                $surveys->appoinment_date = date("Y-m-d", strtotime($appoinment_date));
                $surveys->appoinment_time = date("H:i:s", strtotime($appoinment_time));
                $surveys->apooinment_for = $user_id;
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
                        $user['plain_pass'] = $password;
                        //pr($user); exit;
                        $this->sendPlanInvoiceEmails($plan_id, $user);
                        //$this->sentEmails($name, $email, $password);
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
                                $this->loadModel('PackageOrders');
                                if ($plan_id == RUBIES_PLAN_ID) {
                                    // INSERT BOOM AC ONE SERVICES
                                    for ($i = 1; $i <= 1; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = BOOM_AC_SERVICE_ID;
                                        $insertData['service_name'] = BOOM_AC_SERVICE_NAME;
                                        $insertData['service_image'] = BOOM_AC_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = '';
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                    // INSERT ZOOM RO ONE SERVICES
                                    for ($i = 1; $i <= 1; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = ZOOM_RO_SERVICE_ID;
                                        $insertData['service_name'] = ZOOM_RO_SERVICE_NAME;
                                        $insertData['service_image'] = ZOOM_RO_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = '';
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                }
                                if ($plan_id == SAPPHIRES_PLAN_ID) {
                                    // INSERT BOOM AC TWO SERVICES
                                    for ($i = 1; $i <= 2; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = BOOM_AC_SERVICE_ID;
                                        $insertData['service_name'] = BOOM_AC_SERVICE_NAME;
                                        $insertData['service_image'] = BOOM_AC_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = '';
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                    // INSERT ZOOM RO TWO SERVICES
                                    for ($i = 1; $i <= 2; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = ZOOM_RO_SERVICE_ID;
                                        $insertData['service_name'] = ZOOM_RO_SERVICE_NAME;
                                        $insertData['service_image'] = ZOOM_RO_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = '';
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                }
                                if ($plan_id == BOOM_AC_PLAN_ID) {
                                    $serviceDateArr = [];
                                    $service_date_1 = $service_date_2 = $service_date_3 = '';
                                    $service_date_1 = date('Y-m-d', strtotime("+7 day"));
                                    $service_day_1 = date('D', strtotime($service_date_1));
                                    if ($service_day_1 == 'Sun') {
                                        $service_date_1 = date('Y-m-d', strtotime($service_date_1 . "+1 day"));
                                    }
                                    $serviceDateArr['1'] = $service_date_1;
                                    $service_date_2 = date('Y-m-d', strtotime($service_date_1 . "+4 month"));
                                    $service_day_2 = date('D', strtotime($service_date_2));
                                    if ($service_day_2 == 'Sun') {
                                        $service_date_2 = date('Y-m-d', strtotime($service_date_2 . "+1 day"));
                                    }
                                    $serviceDateArr['2'] = $service_date_2;
                                    $service_date_3 = date('Y-m-d', strtotime($service_date_2 . "+4 month"));
                                    $service_day_3 = date('D', strtotime($service_date_3));
                                    if ($service_day_3 == 'Sun') {
                                        $service_date_3 = date('Y-m-d', strtotime($service_date_3 . "+1 day"));
                                    }
                                    $serviceDateArr['3'] = $service_date_3;
                                    for ($i = 1; $i <= 3; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = BOOM_AC_SERVICE_ID;
                                        $insertData['service_name'] = BOOM_AC_SERVICE_NAME;
                                        $insertData['service_image'] = BOOM_AC_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = $serviceDateArr[$i];
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                }
                                if ($plan_id == ZOOM_RO_PLAN_ID) {
                                    $serviceDateArr = [];
                                    $service_date_1 = $service_date_2 = $service_date_3 = '';
                                    $service_date_1 = date('Y-m-d', strtotime("+7 day"));
                                    $service_day_1 = date('D', strtotime($service_date_1));
                                    if ($service_day_1 == 'Sun') {
                                        $service_date_1 = date('Y-m-d', strtotime($service_date_1 . "+1 day"));
                                    }
                                    $serviceDateArr['1'] = $service_date_1;
                                    $service_date_2 = date('Y-m-d', strtotime($service_date_1 . "+4 month"));
                                    $service_day_2 = date('D', strtotime($service_date_2));
                                    if ($service_day_2 == 'Sun') {
                                        $service_date_2 = date('Y-m-d', strtotime($service_date_2 . "+1 day"));
                                    }
                                    $serviceDateArr['2'] = $service_date_2;
                                    $service_date_3 = date('Y-m-d', strtotime($service_date_2 . "+4 month"));
                                    $service_day_3 = date('D', strtotime($service_date_3));
                                    if ($service_day_3 == 'Sun') {
                                        $service_date_3 = date('Y-m-d', strtotime($service_date_3 . "+1 day"));
                                    }
                                    $serviceDateArr['3'] = $service_date_3;
                                    for ($i = 1; $i <= 3; $i++) {
                                        $insertData = $packageOrder = [];
                                        $packageOrder = $this->PackageOrders->newEntity();
                                        $insertData['user_id'] = $userId;
                                        $insertData['service_id'] = ZOOM_RO_SERVICE_ID;
                                        $insertData['service_name'] = ZOOM_RO_SERVICE_NAME;
                                        $insertData['service_image'] = ZOOM_RO_SERVICE_IMAGE;
                                        $insertData['service_status'] = 'PENDING';
                                        $packageOrder = $this->PackageOrders->patchEntity($packageOrder, $insertData);
                                        $packageOrder->service_date = $serviceDateArr[$i];
                                        $packageOrder->created_by = $userId;
                                        $packageOrder->created = date('Y-m-d H:i:s');
                                        $packageOrder->modified_by = $userId;
                                        $packageOrder->modified = date('Y-m-d H:i:s');
                                        if (!$this->PackageOrders->save($packageOrder)) {
                                            $this->Flash->error('Packeage Order Insertion Failed!');
                                        }
                                    }
                                }
                                $planDetails = $this->Plans->find('all')->where(['id' => $plan_id])->hydrate(false)->first();
                                if ((isset($referIds) && $referIds != '')) {
                                    $this->loadModel('GreenCashbacks');
                                    $greencash = $this->GreenCashbacks->newEntity();
                                    $greenCashArray = [];
                                    $greenCashArray['user_id'] = $referIds;
                                    $greenCashArray['amount'] = GREEN_CASH_REWERDS_AMOUNT;
                                    $greenCashArray['refer_membership_id'] = $userId;
                                    $greenCashArray['status'] = 'PENDING';
                                    $greenCashArray['created_by'] = $this->request->session()->read('Auth.User.id');
                                    $greencash = $this->GreenCashbacks->patchEntity($greencash, $greenCashArray);
                                    $greencash->created = date("Y-m-d H:i:s");
                                    $green_cash_id = $this->GreenCashbacks->save($greencash);
                                    if ($green_cash_id) {
                                        $this->newMsg($referIds, MSG_TITLE_REFER_MAMBERSHIP, MSG_TYPE_GREEN_CASH, 'Rs. ' . GREEN_CASH_REWERDS_AMOUNT . ' Cashback for Membership Reference');
                                    }
                                }
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
            'tomail' => $email,
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
            $appoinmentLists = $this->Surveys->find('all')->select(['id', 'person_name', 'appoinment_date', 'appoinment_time', 'appoinment_status'])->where(['apooinment_for' => $user_id, 'appoinment_status' => 'PENDING', "DATE_FORMAT(appoinment_date,'%Y-%m-%d') <=" => date('Y-m-d')])->hydrate(false)->toArray();
            if (!empty($appoinmentLists)) {
                $appoinments = [];
                foreach ($appoinmentLists as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['name'] = $val['person_name'];
                    $tmp['status'] = $val['appoinment_status'];
                    $tmp['time'] = $val['appoinment_date']->format('d-m-Y') . " " . date('h:i A', strtotime($val['appoinment_time']));
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
                $appoinmentDetails = $this->Surveys->find('all')->select(['id', 'person_name', 'address', 'contact_number', 'appoinment_date', 'appoinment_time', 'appoinment_status'])->where(['id' => $requestArr['survey_id']])->hydrate(false)->first();
                if (is_array($appoinmentDetails) && !empty($appoinmentDetails)) {
                    $appoinmentDetails['time'] = $appoinmentDetails['appoinment_date']->format('d-m-Y') . " " . date('h:i A', strtotime($appoinmentDetails['appoinment_time']));
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

    public function appoinmentInterested() {
        $user_id = $this->checkVerifyApiKey('SALES');
        if ($user_id) {
            $this->loadModel('Surveys');
            $requestArr = $this->getInputArr();
            if (isset($requestArr['survey_id']) && $requestArr['survey_id'] != '') {
                $appoinmentDetails = $this->Surveys->get($requestArr['survey_id']);
                if (!empty($appoinmentDetails)) {
                    $updateValue['appoinment_status'] = 'INTERESTED';
                    $appoinmentDetails = $this->Surveys->patchEntity($appoinmentDetails, $updateValue);
                    $appoinmentDetails->modified = date("Y-m-d H:i:s");
                    $appoinmentDetails->modified_by = $user_id;
                    if ($this->Surveys->save($appoinmentDetails)) {
                        $this->success('APPOINMENT IS INTERESTED');
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

    public function assignedorders() {
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $orders = [];
            $this->loadModel('Orders');
            $this->loadModel('PackageOrders');
            $orderLists = $this->Orders->find('all')->where(['vendors_id' => $user_id, 'status IN' => ['PLACED']])->order(['id' => 'DESC'])->hydrate(false)->toArray();
            if (is_array($orderLists) && !empty($orderLists)) {
                foreach ($orderLists as $order) {
                    $tmp = [];
                    $tmp['username'] = $this->getUserName($order['user_id']);
                    $tmp['userimage'] = $this->getUserProfilePicture($order['user_id']);
                    $tmp['usertype'] = $this->getUserType($order['user_id']);
                    $tmp['address'] = $order['user_address'];
                    $tmp['orderstatus'] = ucfirst(strtolower($order['status']));
                    $tmp['amount'] = $order['total_amount'];
                    $tmp['order_id'] = $order['order_id'];
                    $tmp['order_type'] = 'NORMAL';
                    $tmp['order_time'] = $order['created_at']->format('Y-m-d h:i A');
                    $orders[] = $tmp;
                }
            }
            $orderList = $this->PackageOrders->find('all')->where(['vendors_id' => $user_id, 'service_status IN' => ['PLACED']])->order(['id' => 'DESC'])->hydrate(false)->toArray();
            if (is_array($orderList) && !empty($orderList)) {
                foreach ($orderList as $order) {
                    $tmp = [];
                    $tmp['username'] = $this->getUserName($order['user_id']);
                    $tmp['userimage'] = $this->getUserProfilePicture($order['user_id']);
                    $tmp['usertype'] = $this->getUserType($order['user_id']);
                    $tmp['address'] = $this->getAddress($order['user_id']);
                    $tmp['orderstatus'] = ucfirst(strtolower($order['service_status']));
                    $tmp['amount'] = 0;
                    $tmp['order_id'] = $order['id'];
                    $tmp['order_type'] = 'PACKAGE';
                    $tmp['order_time'] = $order['service_date']->format('Y-m-d');
                    $orders[] = $tmp;
                }
            }
            //pr($orders); exit;
            $this->success('Order Data Found!', $orders);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function assignorderdetails() {
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $this->loadModel('Carts');
            $this->loadModel('CartOrders');
            $this->loadModel('CartOrderQuestions');
            $this->loadModel('ServiceQuestionAnswers');
            $this->loadModel('PackageOrders');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
                'Order Type' => (isset($requestArr['order_type']) && $requestArr['order_type'] != '') ? $requestArr['order_type'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $order_type = $requestArr['order_type'];
            $orders = [];
            if ($order_type == 'NORMAL') {
                $order = $this->Orders->find('all')->where(['order_id' => $order_id])->hydrate(false)->first();
                if (!empty($order)) {
                    $orderDetails = [];
                    $orderDetails['user_id'] = $order['user_id'];
                    $orderDetails['username'] = $this->getUserName($order['user_id']);
                    $orderDetails['userimage'] = $this->getUserProfilePicture($order['user_id']);
                    $orderDetails['usertype'] = $usertype = $this->getUserType($order['user_id']);
                    $orderDetails['available_service'] = 0;
                    $orderDetails['use_credits'] = 'NO';
                    if ($usertype == 'MEMBERSHIP') {
                        if (in_array($order['service_id'], ['1', '2', '3'])) {
                            $availableServices = $this->getMemberCredits($order['user_id']);
                            $orderDetails['available_service'] = $availableServices;
                            if ($availableServices > 0) {
                                $todayorder = $this->Orders->find('all')->where(['order_id !=' => $order_id, 'service_id' => $order['service_id'], 'payment_method' => 'CREDITS', "DATE_FORMAT(created_at,'%Y-%m-%d')" => date('Y-m-d')])->hydrate(false)->count();
                                if ($todayorder == 0) {
                                    $orderDetails['use_credits'] = 'YES';
                                }
                            }
                        }
                    }
                    $orderDetails['order_id'] = $order['order_id'];
                    $orderDetails['user_address'] = $order['user_address'];
                    $orderDetails['created_at'] = $order['created_at']->format('d-M-Y h:i A');
                    $orderDetails['schedule_date'] = $order['schedule_date']->format('d-M-Y');
                    $orderDetails['schedule_time'] = $order['schedule_time'];
                    $orderDetails['on_inspections'] = $order['on_inspections'];
                    $orderDetails['is_minimum_charge'] = $order['is_minimum_charge'];
                    $orderDetails['credits_applied'] = $order['credits_applied'];
                    $orderDetails['on_inspections_cost'] = number_format($order['on_inspections_cost'], 2);
                    $orderDetails['total_amount'] = number_format($order['total_amount'], 2);
                    $orderDetails['status'] = $order['status'];
                    $orderDetails['payment_status'] = $order['payment_status'];
                    $orderDetails['payment_method'] = $order['payment_method'];
                    $orderDetails['images'] = '';
                    $orderDetails['services'] = [];
                    if (isset($order['status']) && !empty($order['status'])) {
                        if ($order['status'] == 'SCHEDULE') {
                            $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                        }
                        if ($order['status'] == 'ON_INSPECTION') {
                            $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                        }
                    }
                    $orderDetails['payment_status'] = $order['payment_status'];
                    $orderDetails['images'] = '';
                    $orderDetails['order_type'] = 'NORMAL';
                    $orderDetails['services'] = [];
                    $orderDetails['total']['amount'] = number_format($order['amount'], 2);
                    $orderDetails['total']['tax'] = number_format($order['tax'], 2);
                    $orderDetails['total']['wallet_amount'] = number_format($order['wallet_amount'], 2);
                    if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') {
                        $sum = $order['amount'] + $order['tax'];
                        $orderDetails['total']['bill_amount'] = number_format($sum, 2);
                    }
                    $orderDetails['total']['total_amount'] = number_format($order['total_amount'], 2);
                    $condArr = ['cart_id' => $order['cart_id']];
                    $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
                    $ordersItems = [];
                    foreach ($cartOrders as $order) {
                        $tmp = [];
                        $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                        $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
                        $tmp['banner'] = $this->getServiceImagePAth($order['service_id']);
                        $orderDetails['images'] = $this->Services->getServiceImagePAth($order['service_id']);
                        $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $order['id']])->hydrate(false)->toArray();
                        foreach ($tmpDetails as $orderQues) {
                            $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
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
                    $this->wrong('Order data not found!');
                }
            } else {
                //echo 1; exit;
                $order = $this->PackageOrders->find('all')->where(['id' => $order_id])->hydrate(false)->first();
                if (!empty($order)) {
                    $orderDetails['user_id'] = $order['user_id'];
                    $orderDetails['username'] = $this->getUserName($order['user_id']);
                    $orderDetails['userimage'] = $this->getUserProfilePicture($order['user_id']);
                    $orderDetails['usertype'] = $usertype = $this->getUserType($order['user_id']);
                    $orderDetails['order_id'] = $order['id'];
                    $orderDetails['user_address'] = $this->getAddress($order['user_id']);
                    $orderDetails['created_at'] = $order['created']->format('d-M-Y h:i A');
                    $orderDetails['schedule_date'] = $order['service_date']->format('d-M-Y');
                    $orderDetails['on_inspections'] = 'N';
                    $orderDetails['is_minimum_charge'] = 'N';
                    $orderDetails['credits_applied'] = 'N';
                    $orderDetails['on_inspections_cost'] = 0.00;
                    $orderDetails['total_amount'] = 0.00;
                    $orderDetails['status'] = $order['service_status'];
                    $orderDetails['payment_status'] = 'FREE';
                    $orderDetails['images'] = '';
                    $orderDetails['order_type'] = 'PACKAGE';
                    $orderDetails['services'] = [];
                    if (isset($order['service_status']) && !empty($order['service_status'])) {
                        if ($order['service_status'] == 'SCHEDULE') {
                            $orderDetails['vandor_name'] = $this->getUserName($order['vendors_id']);
                        }
                    }
                    $category_id = $this->getCategoryId($order['service_id']);
                    $servicesArr['0']['category_name'] = $this->getCategoryName($category_id);
                    $servicesArr['0']['service_name'] = $order['service_name'];
                    $servicesArr['0']['banner'] = 'http://uncode.in/hmen/img/services/square/' . $order['service_image'];
                    $servicesArr['0']['serviceDescription'] = 'Service';
                    $servicesArr['0']['quantity'] = 0;
                    $servicesArr['0']['on_inspection'] = 'Y';
                    $servicesArr['0']['amount'] = 0;
                    $servicesArr['0']['total_amount'] = 0;
                    $serviceArr['category'] = $this->getCategoryName($category_id);
                    $serviceArr['services'] = $servicesArr;
                    $orderDetails['services'][] = $serviceArr;
                    $orderDetails['total']['amount'] = 0.00;
                    $orderDetails['total']['tax'] = 0.00;
                    $orderDetails['total']['wallet_amount'] = 0.00;
                    $orderDetails['total']['total_amount'] = 0.00;
                    $this->success('order detail fetched successfully', $orderDetails);
                } else {
                    $this->wrong('Order data not found!');
                }
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function orderRequest() {
        $this->loadModel('Orders');
        $this->loadModel('PackageOrders');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
                'Order Request Status' => (isset($requestArr['status']) && $requestArr['status'] != '') ? $requestArr['status'] : '',
                'Order Type' => (isset($requestArr['order_type']) && $requestArr['order_type'] != '') ? $requestArr['order_type'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $order_type = $requestArr['order_type'];
            $status = $requestArr['status'];
            $orders = [];
            if ($order_type == 'NORMAL') {
                $getOrderId = $this->Orders->find('all')->select(['id'])->where(['order_id' => $order_id])->hydrate(false)->first();
                if (isset($getOrderId['id']) && $getOrderId['id'] != '') {
                    $id = $getOrderId['id'];
                    $order = $this->Orders->get($id);
                    //pr($order['user_id']); exit;
                    $updateFields = [];
                    if ($status == 'ACCEPT') {
                        $updateFields['status'] = 'SCHEDULE';
                        $msg = "Order Accepted!";
                    } else {
                        $updateFields['status'] = 'PENDING';
                        $updateFields['vendors_id'] = 0;
                        $msg = "Order Rejected!";
                    }
                    $order = $this->Orders->patchEntity($order, $updateFields);
                    $order->modified_by = $user_id;
                    $order->modified_at = date('Y-m-d H:i:s');
                    if ($this->Orders->save($order)) {
                        if ($status == 'ACCEPT') {
                            $this->newMsg($order['user_id'], MSG_TITLE_ORDER_ASSIGN, MSG_TYPE_ORDER, 'Your Order is assigned to vendors Successfully. Order Id is #' . $order_id);
                        }
                        $this->success($msg);
                    } else {
                        $this->wrong('Order status update failed.');
                    }
                } else {
                    $this->wrong('Order data not found.');
                }
            } else {
                $order = $this->PackageOrders->get($order_id);
                $updateFields = [];
                if ($status == 'ACCEPT') {
                    $updateFields['service_status'] = 'SCHEDULE';
                    $msg = "Order Accepted!";
                } else {
                    $updateFields['service_status'] = 'PENDING';
                    $updateFields['vendors_id'] = 0;
                    $msg = "Order Rejected!";
                }
                $order = $this->PackageOrders->patchEntity($order, $updateFields);
                $order->modified_by = $user_id;
                $order->modified_at = date('Y-m-d H:i:s');
                if ($this->PackageOrders->save($order)) {
                    $this->success($msg);
                } else {
                    $this->wrong('Order status update failed.');
                }
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorOrderUpdate() {
        $this->loadModel('Orders');
        $this->loadModel('PackageOrders');
        $this->loadModel('Services');
        $this->loadModel('Carts');
        $this->loadModel('CartOrders');
        $this->loadModel('CartOrderQuestions');
        $this->loadModel('ServiceQuestionAnswers');

        $this->loadModel('Wallets');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
                'Order Type' => (isset($requestArr['order_type']) && $requestArr['order_type'] != '') ? $requestArr['order_type'] : '',
                'Order Action' => (isset($requestArr['order_action']) && $requestArr['order_action'] != '') ? $requestArr['order_action'] : '',
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_type = $requestArr['order_type'];
            $order_id = $requestArr['order_id'];
            $action = $requestArr['order_action'];
            if ($order_type == 'NORMAL') {
                $getOrderId = $this->Orders->find('all')->select(['id'])->where(['order_id' => $order_id])->hydrate(false)->first();
                if (isset($getOrderId['id']) && $getOrderId['id'] != '') {
                    $id = $getOrderId['id'];
                    $order = $this->Orders->get($id);
                    $updateFields = [];
                    $msg = 'Order Updated!';
                    if ($action == 'USE_CREDITS') {
                        $requiredFields = array(
                            'Status' => (isset($requestArr['status']) && $requestArr['status'] != '') ? $requestArr['status'] : '',
                        );
                        $validate = $this->checkRequiredFields($requiredFields);
                        if ($validate != "") {
                            $this->wrong($validate);
                        }
                        $status = $requestArr['status'];
                        if ($status == 'Y') {
                            $updateFields['credits_applied'] = 'Y';
                            $updateFields['payment_method'] = 'CREDITS';
                            $updateFields['payment_status'] = 'PAID';
                            $updateFields['wallet_amount'] = 0.00;
                            $updateFields['amount'] = 0.00;
                            $updateFields['on_inspections_cost'] = 0.00;
                            $updateFields['tax'] = 0.00;
                            $updateFields['total_amount'] = 0.00;
                            $this->Wallets->deleteAll(['Wallets.purpose_id' => $order_id]);
                        } else {
                            $updateFields['credits_applied'] = 'N';
                        }
                    }
                    if ($action == 'ON_INSPECTION') {
                        $requiredFields = array(
                            'On Inspection Cost' => (isset($requestArr['on_inspections_cost']) && $requestArr['on_inspections_cost'] != '') ? $requestArr['on_inspections_cost'] : '',
                        );
                        $validate = $this->checkRequiredFields($requiredFields);
                        if ($validate != "") {
                            $this->wrong($validate);
                        }
                        $updateFields['on_inspections_cost'] = $requestArr['on_inspections_cost'];
                        $total_price = $order['amount'] + $requestArr['on_inspections_cost'];
                        $tax = $total_price * GST_TAX / 100;
                        $tmptotAmount = $total_price + $tax;
                        $customer_id = $order['user_id'];
                        $usertype = $this->getUserType($customer_id);
                        if ($usertype == "MEMBERSHIP") {
                            $available_walletcash = $this->walletAmount($customer_id);
                            if ($available_walletcash != 0) {
                                $plan_id = $this->getPlanId($customer_id);
                                $wallet_cash_per = 0;
                                if ($plan_id = RUBIES_PLAN_ID) {
                                    $wallet_cash_per = RUBIES_DISCOUNT_RATE;
                                }
                                if ($plan_id = SAPPHIRES_PLAN_ID) {
                                    $wallet_cash_per = SAPPHIRES_DISCOUNT_RATE;
                                }
                                if ($wallet_cash_per != 0) {
                                    $walletCash = $tmptotAmount * ($wallet_cash_per / 100);
                                    if ($available_walletcash > $walletCash) {
                                        $wallet_amount = $walletCash;
                                    } else {
                                        $wallet_amount = $available_walletcash;
                                    }
                                }
                            }
                            $updateFields['wallet_amount'] = $wallet_amount;
                        } else {
                            $updateFields['wallet_amount'] = $wallet_amount;
                        }
                        if ($wallet_amount != 0) {
                            $vW = [];
                            $vW['amount'] = $wallet_amount;
                            $vW['wallet_type'] = 'DEBIT';
                            $vW['purpose'] = 'ORDER';
                            $vW['purpose_id'] = $order_id;
                            $walletId = $this->addWalletAmount($user_id, $vW['amount'], $vW['wallet_type'], $vW['purpose'], $vW['purpose_id']);
                            if (!$walletId) {
                                $this->wrong('Wallet Amount Add operation Failed!');
                            }
                        }
                        $total_amounts = $tmptotAmount - $wallet_amount;
                        $updateFields['amount'] = $total_price;
                        $updateFields['tax'] = $tax;
                        $updateFields['total_amount'] = str_replace(",", "", $total_amounts);
                        $updateFields['on_inspections'] = 'D';
                        $msg = 'Order Inspection Cost Updated!';
                    }
                    if ($action == 'ON_INSPECTION_WITH_USE_CREDITS') {
                        $requiredFields = array(
                            'Status' => (isset($requestArr['status']) && $requestArr['status'] != '') ? $requestArr['status'] : '',
                            'On Inspection Cost' => (isset($requestArr['on_inspections_cost']) && $requestArr['on_inspections_cost'] != '') ? $requestArr['on_inspections_cost'] : '',
                        );
                        $validate = $this->checkRequiredFields($requiredFields);
                        if ($validate != "") {
                            $this->wrong($validate);
                        }
                        $status = $requestArr['status'];
                        if ($status == 'Y') {
                            $updateFields['credits_applied'] = 'Y';
                            $updateFields['payment_method'] = 'CREDITS';
                            $updateFields['payment_status'] = 'PAID';
                            $updateFields['wallet_amount'] = 0.00;
                            $updateFields['amount'] = 0.00;
                            $updateFields['on_inspections_cost'] = 0.00;
                            $updateFields['tax'] = 0.00;
                            $updateFields['total_amount'] = 0.00;
                            $this->Wallets->deleteAll(['Wallets.purpose_id' => $order_id]);
                        } else {
                            $updateFields['credits_applied'] = 'N';
                            $updateFields['on_inspections_cost'] = $requestArr['on_inspections_cost'];
                            $total_price = $order['amount'] + $requestArr['on_inspections_cost'];
                            $tax = $total_price * GST_TAX / 100;
                            $updateFields['amount'] = $total_price;
                            $updateFields['tax'] = $tax;
                            $tmptotAmount = $total_price + $tax;
                            $customer_id = $order['user_id'];
                            $usertype = $this->getUserType($customer_id);
                            if ($usertype == "MEMBERSHIP") {
                                $available_walletcash = $this->walletAmount($customer_id);
                                //echo $available_walletcash; exit;
                                if ($available_walletcash != 0) {
                                    $plan_id = $this->getPlanId($customer_id);
                                    $wallet_cash_per = 0;
                                    if ($plan_id = RUBIES_PLAN_ID) {
                                        $wallet_cash_per = RUBIES_DISCOUNT_RATE;
                                    }
                                    if ($plan_id = SAPPHIRES_PLAN_ID) {
                                        $wallet_cash_per = SAPPHIRES_DISCOUNT_RATE;
                                    }
                                    //echo $wallet_cash_per; exit;
                                    if ($wallet_cash_per != 0) {
                                        $walletCash = $tmptotAmount * ($wallet_cash_per / 100);
                                        //echo $walletCash; exit;

                                        if ($available_walletcash > $walletCash) {
                                            $wallet_amount = $walletCash;
                                        } else {
                                            $wallet_amount = $available_walletcash;
                                        }
                                    }
                                }
                                $updateFields['wallet_amount'] = $wallet_amount;
                            } else {
                                $updateFields['wallet_amount'] = $wallet_amount;
                            }
                            if ($wallet_amount != 0) {
                                $vW = [];
                                $vW['amount'] = $wallet_amount;
                                $vW['wallet_type'] = 'DEBIT';
                                $vW['purpose'] = 'ORDER';
                                $vW['purpose_id'] = $order_id;
                                $walletId = $this->addWalletAmount($user_id, $vW['amount'], $vW['wallet_type'], $vW['purpose'], $vW['purpose_id']);
                                if (!$walletId) {
                                    $this->wrong('Wallet Amount Add operation Failed!');
                                }
                            }
                            $total_amounts = $tmptotAmount - $wallet_amount;
                            $updateFields['total_amount'] = str_replace(",", "", $total_amounts);
                            $updateFields['on_inspections'] = 'D';
                            $msg = 'Order Inspection Cost Updated!';
                        }
                    }
                    if ($action == 'VISITING_CHARGE') {
                        $updateFields['is_visiting_charge'] = 'Y';
                        $total_price = VISITING_CHARGE;
                        $tax = $total_price * GST_TAX / 100;
                        $updateFields['amount'] = $total_price - $tax;
                        $updateFields['tax'] = $tax;
                        $updateFields['total_amount'] = $total_price;
                        $updateFields['status'] = 'CANCELLED';
                        $updateFields['payment_status'] = 'PAID';
                        $msg = 'Order Cancelled Successfully!';
                    }
                    if ($action == 'ORDER_COMPLETED') {
                        $orderDetails = [];
                        $orderDetails['id'] = $order['id'];
                        $orderDetails['user_id'] = $order['user_id'];
                        $orderDetails['order_id'] = $order['order_id'];
                        $orderDetails['username'] = $this->getUserName($order['user_id']);
                        $orderDetails['useremail'] = $this->getEmail($order['user_id']);
                        $orderDetails['userphone'] = $this->getPhone($order['user_id']);
                        $orderDetails['user_address'] = $order['user_address'];
                        $orderDetails['category_name'] = $this->getCategoryName($order['category_id']);
                        $orderDetails['service_name'] = $this->getServiceName($order['service_id']);
                        $orderDetails['created_at'] = $order['created_at']->format('d-M-Y h:i A');
                        $orderDetails['schedule_date'] = $order['schedule_date']->format('d-M-Y');
                        $orderDetails['schedule_time'] = $order['schedule_time'];
                        $orderDetails['on_inspections'] = $order['on_inspections'];
                        $orderDetails['is_minimum_charge'] = $order['is_minimum_charge'];
                        $orderDetails['is_visiting_charge'] = $order['is_visiting_charge'];
                        $orderDetails['wallet_amount'] = number_format($order['wallet_amount'], 2);
                        $orderDetails['amount'] = number_format($order['amount'], 2);
                        $orderDetails['on_inspections_cost'] = number_format($order['on_inspections_cost'], 2);
                        $orderDetails['tax'] = number_format($order['tax'], 2);
                        $orderDetails['total_amount'] = number_format($order['total_amount'], 2);
                        $orderDetails['status'] = $order['status'];
                        $orderDetails['payment_status'] = $order['payment_status'];
                        $orderDetails['images'] = '';
                        $orderDetails['services'] = [];
                        $orderDetails['total']['amount'] = number_format($order['amount'], 2);
                        $orderDetails['total']['tax'] = number_format($order['tax'], 2);
                        $orderDetails['total']['wallet_amount'] = number_format($order['wallet_amount'], 2);
                        if (isset($orderDetails['is_minimum_charge']) && $orderDetails['is_minimum_charge'] == 'Y') {
                            $sum = $order['amount'] + $order['tax'];
                            $orderDetails['total']['bill_amount'] = number_format($sum, 2);
                        }
                        $orderDetails['total']['total_amount'] = number_format($order['total_amount'], 2);
                        $condArr = ['cart_id' => $order['cart_id']];
                        $cartOrders = $this->CartOrders->find('all')->where($condArr)->hydrate(false)->toArray();
                        $ordersItems = [];
                        $serviceArr = [];
                        foreach ($cartOrders as $orderss) {
                            $tmp = [];
                            $tmp['cart_order_id'] = $orderss['id'];
                            $tmp['category_id'] = $orderss['category_id'];
                            $tmp['category_name'] = $this->Services->getCategoryName($orderss['category_id']);
                            $tmp['service_id'] = $serviceArr[] = $orderss['service_id'];
                            $tmp['service_name'] = $this->Services->getServiceName($orderss['service_id']);
                            $tmp['banner_img'] = $this->Services->getServiceImagePAth($orderss['service_id']);
                            $orderDetails['images'] = $this->Services->getServiceImagePAth($orderss['service_id']);
                            $tmpDetails = $this->CartOrderQuestions->find('all')->where(['cart_order_id' => $orderss['id']])->hydrate(false)->toArray();
                            $serviceDesc = '';
                            foreach ($tmpDetails as $orderQues) {
                                $questArr = $this->getQuestionDetails($orderQues['question_id'], $orderQues['answer_id']);
                                if (isset($orderss['on_inspections']) && $orderss['on_inspections'] == 'N') {
                                    if ($questArr['parent_question'] != '' && $questArr['parent_answer'] != '') {
                                        $answerTitle = $this->ServiceQuestionAnswers->find('all')->where(['id' => $questArr['parent_answer']])->hydrate(false)->first();
                                        $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                                        $tmp['serviceDescription'] = trim($serviceDesc);
                                        $tmp['quantity'] = $orderQues['question_quantity'];
                                        $tmp['total_amount'] = $orderss['total_amount'];
                                    } else {
                                        $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                                        $tmp['serviceDescription'] = trim($serviceDesc);
                                        $tmp['quantity'] = $orderQues['question_quantity'];
                                    }
                                    if ($tmp['quantity'] == 0) {
                                        $tmp['amount'] = 0;
                                        $tmp['total_amount'] = $orderss['total_amount'];
                                    } else {
                                        $tmp['amount'] = $orderss['total_amount'] / $tmp['quantity'];
                                        $tmp['total_amount'] = $orderss['total_amount'];
                                    }
                                    $tmp['on_inspection'] = 'N';
                                } else {
                                    $serviceDesc .= (isset($questArr['answer']) && $questArr['answer'] != '') ? " " . $questArr['answer'] : '';
                                    $tmp['serviceDescription'] = trim($serviceDesc);
                                    $tmp['quantity'] = $orderQues['question_quantity'];
                                    $tmp['on_inspection'] = 'Y';
                                    $tmp['amount'] = 0;
                                    $tmp['total_amount'] = $orderss['total_amount'];
                                }
                            }

                            //pr($tmp); exit;
                            $ordersItems[$orderss['category_id']]['category'] = $this->Services->getCategoryName($orderss['category_id']);
                            $ordersItems[$orderss['category_id']]['services'][] = $tmp;
                            //pr($ordersItems); exit;
                        }
                        //$orderDetails['services'] = $ordersItems;
                        //pr($orderDetails); exit;
                        $finalOrderDetails = [];
                        if (!empty($ordersItems)) {
                            foreach ($ordersItems as $key => $val) {
                                $finalOrderDetails[] = $val;
                            }
                        }

                        $orderDetails['services'] = $finalOrderDetails;
                        $orderDetails['new_status'] = 'COMPLETED';
                        $this->sendOrderInvoiceEmails($orderDetails);
//                        echo 1; exit;
                        $updateFields['status'] = 'COMPLETED';
                        $updateFields['payment_status'] = 'PAID';
                    }
                    if ($action == 'ORDER_CANCELLED') {
                        $requiredFields = array(
                            'Cancellation Reason' => (isset($requestArr['order_cancellation_reason']) && $requestArr['order_cancellation_reason'] != '') ? $requestArr['order_cancellation_reason'] : '',
                        );
                        $validate = $this->checkRequiredFields($requiredFields);
                        if ($validate != "") {
                            $this->wrong($validate);
                        }
                        $updateFields['payment_method'] = '';
                        $updateFields['status'] = 'CANCELLED';
                        $updateFields['reason_order_cancelled'] = $requestArr['order_cancellation_reason'];
                        $updateFields['payment_status'] = '';
                    }
                    $order = $this->Orders->patchEntity($order, $updateFields);
                    $order->modified_by = $user_id;
                    $order->modified_at = date('Y-m-d H:i:s');
//                    pr($order);
//                    exit;
                    if ($this->Orders->save($order)) {
                        if ($action == 'USE_CREDITS') {
                            if ($status == 'Y') {
                                $this->newMsg($order['user_id'], MSG_TITLE_ORDER_UPDATED, MSG_TYPE_ORDER, 'Your order is done in your free service credits. Order Id is #' . $order_id);
                            }
                        }
                        if ($action == 'ON_INSPECTION') {
                            $this->newMsg($order['user_id'], MSG_TITLE_ORDER_UPDATED, MSG_TYPE_ORDER, 'Your order\'s inspection cost is updated. Order Id is #' . $order_id);
                        }
                        if ($action == 'ON_INSPECTION_WITH_USE_CREDITS') {
                            if ($status == 'Y') {
                                $this->newMsg($order['user_id'], MSG_TITLE_ORDER_UPDATED, MSG_TYPE_ORDER, 'Your order is completed in your free service credits. Order Id is #' . $order_id);
                            } else {
                                $this->newMsg($order['user_id'], MSG_TITLE_ORDER_UPDATED, MSG_TYPE_ORDER, 'Your order\'s inspection cost is updated. Order Id is #' . $order_id);
                            }
                        }
                        if ($action == 'ORDER_COMPLETED') {
                            $this->newMsg($order['user_id'], MSG_TITLE_ORDER_COMPLETED, MSG_TYPE_ORDER, 'Your order is completed. Order Id is #' . $order_id);
                        }
                        if ($action == 'VISITING_CHARGE' || $action == 'ORDER_CANCELLED') {
                            $this->newMsg($order['user_id'], MSG_TITLE_ORDER_CANCELLED, MSG_TYPE_ORDER, 'Your order is cancelled. Order Id is #' . $order_id);
                        }
                        $this->success($msg);
                    } else {
                        $this->wrong('Order status update failed.');
                    }
                }
            } else {
                $getOrderId = $this->PackageOrders->find('all')->select(['id'])->where(['id' => $order_id])->hydrate(false)->first();
                if (isset($getOrderId['id']) && $getOrderId['id'] != '') {
                    $id = $getOrderId['id'];
                    $order = $this->PackageOrders->get($id);
                    $updateFields = [];
                    $msg = 'Order Updated!';
                    if ($action == 'ORDER_COMPLETED') {
                        $updateFields['status'] = 'COMPLETED';
                    }
                    $order = $this->PackageOrders->patchEntity($order, $updateFields);
                    $order->modified_by = $user_id;
                    $order->modified_at = date('Y-m-d H:i:s');
                    if ($this->PackageOrders->save($order)) {
                        $this->success($msg);
                    } else {
                        $this->wrong('Order status update failed.');
                    }
                }
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorJobCounts() {
        $this->loadModel('Orders');
        $this->loadModel('PackageOrders');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $countArr = [];
            $ongoingCounter = $this->Orders->find('all')->where(['vendors_id' => $user_id, 'status' => 'PLACED', 'on_inspections' => 'Y'])->hydrate(false)->count();
            $ongoingSCounter = $this->PackageOrders->find('all')->where(['vendors_id' => $user_id, 'service_status' => 'ON_INSPECTION'])->hydrate(false)->count();
            $scheduleCounter = $this->Orders->find('all')->where(['vendors_id' => $user_id, 'status' => 'SCHEDULE'])->hydrate(false)->count();
            $scheduleSCounter = $this->PackageOrders->find('all')->where(['vendors_id' => $user_id, 'service_status' => 'SCHEDULE'])->hydrate(false)->count();
            $completedCounter = $this->Orders->find('all')->where(['vendors_id' => $user_id, 'status' => 'COMPLETED'])->hydrate(false)->count();
            $completedSCounter = $this->PackageOrders->find('all')->where(['vendors_id' => $user_id, 'service_status' => 'COMPLETED'])->hydrate(false)->count();
            $cancelledCounter = $this->Orders->find('all')->where(['vendors_id' => $user_id, 'status' => 'CANCELLED'])->hydrate(false)->count();
            $cancelledSCounter = $this->PackageOrders->find('all')->where(['vendors_id' => $user_id, 'service_status' => 'CANCELLED'])->hydrate(false)->count();
            //pr($countArr); exit;
            $tmpOngoing = $tmpSchedule = $tmpCompleted = $tmpCancelled = [];
            $tmpOngoing['name'] = 'ONGOING';
            $tmpOngoing['image'] = IMAGE_URL_PATH . 'icons/order-placed.png';
            $tmpOngoing['count'] = $ongoingCounter + $ongoingSCounter;
            $tmpSchedule['name'] = 'SCHEDULE';
            $tmpSchedule['image'] = IMAGE_URL_PATH . 'icons/order-schedule.png';
            $tmpSchedule['count'] = $scheduleCounter + $scheduleSCounter;
            $tmpCompleted['name'] = 'COMPLETED';
            $tmpCompleted['image'] = IMAGE_URL_PATH . 'icons/order-completed.png';
            $tmpCompleted['count'] = $completedCounter + $completedSCounter;
            $tmpCancelled['name'] = 'CANCELLED';
            $tmpCancelled['image'] = IMAGE_URL_PATH . 'icons/order-cancelled.png';
            $tmpCancelled['count'] = $cancelledCounter + $cancelledSCounter;
            $countArr[] = $tmpOngoing;
            $countArr[] = $tmpSchedule;
            $countArr[] = $tmpCompleted;
            $countArr[] = $tmpCancelled;

            $this->success('Job Counts!', $countArr);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorJobLists() {
        $this->loadModel('Orders');
        $this->loadModel('PackageOrders');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order List Type' => (isset($requestArr['order_type']) && $requestArr['order_type'] != '') ? $requestArr['order_type'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $orderListType = $requestArr['order_type'];
            if (isset($requestArr['page_no']) && $requestArr['page_no'] != '') {
                $page_no = $requestArr['page_no'];
            } else {
                $page_no = 1;
            }
            $condArr['vendors_id'] = $user_id;
            if ($orderListType == 'ONGOING') {
                $condArr['status'] = 'PLACED';
                $condArr['on_inspections'] = 'Y';
            } elseif ($orderListType == 'SCHEDULE') {
                $condArr['status'] = 'SCHEDULE';
            } elseif ($orderListType == 'COMPLETED') {
                $condArr['status'] = 'COMPLETED';
            } elseif ($orderListType == 'CANCELLED') {
                $condArr['status'] = 'CANCELLED';
            } else {
                $this->wrong('Invalid List type');
            }
            $condArrs['vendors_id'] = $user_id;
            if ($orderListType == 'ONGOING') {
                $condArrs['service_status'] = 'ON_INSPECTION';
            } elseif ($orderListType == 'SCHEDULE') {
                $condArrs['service_status'] = 'SCHEDULE';
            } elseif ($orderListType == 'COMPLETED') {
                $condArrs['service_status'] = 'COMPLETED';
            } elseif ($orderListType == 'CANCELLED') {
                $condArrs['service_status'] = 'CANCELLED';
            } else {
                $this->wrong('Invalid List type');
            }
            $orderLists = $this->Orders->find('all')->where($condArr)->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            $orderList = $this->PackageOrders->find('all')->where($condArrs)->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            if (isset($orderLists) && !empty($orderLists) || isset($orderList) && !empty($orderList)) {
                $orders = [];
                foreach ($orderLists as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['order_id'] = $val['order_id'];
                    $tmp['user_id'] = $val['user_id'];
                    $tmp['username'] = $this->getUserName($val['user_id']);
                    $tmp['userimage'] = $this->getUserProfilePicture($val['user_id']);
                    $tmp['status'] = ucfirst(strtolower($val['status']));
                    $tmp['created_at'] = $val['created_at']->format('d-M-Y');
                    $tmp['order_type'] = 'NORMAL';
                    $orders[] = $tmp;
                }
                foreach ($orderList as $key => $val) {
                    $tmp = [];
                    $tmp['id'] = $val['id'];
                    $tmp['order_id'] = $val['id'];
                    $tmp['user_id'] = $val['user_id'];
                    $tmp['username'] = $this->getUserName($val['user_id']);
                    $tmp['userimage'] = $this->getUserProfilePicture($val['user_id']);
                    $tmp['status'] = ucfirst(strtolower($val['service_status']));
                    $tmp['created_at'] = $val['created']->format('d-M-Y');
                    $tmp['order_type'] = 'PACKAGE';
                    $orders[] = $tmp;
                }
                $nextPageReviews = $this->Orders->find('all')->where($condArr)->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
                $nextPageReviewss = $this->PackageOrders->find('all')->where($condArrs)->order(['id' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no + 1)->hydrate(false)->toArray();
                $next_page = (!empty($nextPageReviews) || !empty($nextPageReviewss)) ? true : false;
                $resp_data = ['orders' => $orders, 'next_page' => $next_page];
                //pr($resp_data); exit;
                $this->success('Orders fetched successfully.', $resp_data);
            } else {
                $resp_data = ['orders' => $orderLists];
                $this->success('Orders fetched successfully.', $resp_data);
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorJobDetails() {
        $this->loadModel('Orders');
        $this->loadModel('PackageOrders');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Order Id' => (isset($requestArr['order_id']) && $requestArr['order_id'] != '') ? $requestArr['order_id'] : '',
                'Order Type' => (isset($requestArr['order_type']) && $requestArr['order_type'] != '') ? $requestArr['order_type'] : ''
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $order_id = $requestArr['order_id'];
            $order_type = $requestArr['order_type'];
            if ($order_type == 'NORMAL') {
                $orderDetails = $this->Orders->find('all')->where(['order_id' => $order_id])->hydrate(false)->first();
                if (is_array($orderDetails) && !empty($orderDetails)) {
                    $rslt = [];
                    $rslt['id'] = $orderDetails['id'];
                    $rslt['user_id'] = $orderDetails['user_id'];
                    $rslt['order_id'] = $orderDetails['order_id'];
                    $rslt['username'] = $this->getUserName($orderDetails['user_id']);
                    $rslt['userphone'] = $this->getPhone($orderDetails['user_id']);
                    $rslt['useraddress'] = $orderDetails['user_address'];
                    $rslt['usertype'] = $usertype = $this->getUserType($orderDetails['user_id']);
                    $rslt['on_inspections'] = $orderDetails['on_inspections'];
                    $rslt['credits_applied'] = $orderDetails['credits_applied'];
                    $rslt['available_service'] = 0;
                    $rslt['use_credits'] = 'NO';
                    if ($usertype == 'MEMBERSHIP') {
                        if (in_array($orderDetails['service_id'], ['1', '2', '3'])) {
                            $availableServices = $this->getMemberCredits($orderDetails['user_id']);
                            $rslt['available_service'] = $availableServices;
                            if ($availableServices > 0) {
                                $todayorder = $this->Orders->find('all')->where(['order_id !=' => $order_id, 'service_id' => $orderDetails['service_id'], 'payment_method' => 'CREDITS', "DATE_FORMAT(created_at,'%Y-%m-%d')" => date('Y-m-d')])->hydrate(false)->count();
                                if ($todayorder == 0) {
                                    $rslt['use_credits'] = 'YES';
                                }
                            }
                        }
                    }
                    $rslt['banner'] = $this->getServiceImagePAth($orderDetails['service_id']);
                    $rslt['schedule_date'] = $orderDetails['schedule_date']->format('d-M-Y');
                    $rslt['schedule_time'] = $orderDetails['schedule_time'];
                    $rslt['total_amount'] = $orderDetails['total_amount'];
                    $rslt['payment_method'] = $orderDetails['payment_method'];
                    $this->success('Orders Details!', $rslt);
                } else {
                    $this->wrong('Order Details Not Found!');
                }
            } else {
                $orderDetails = $this->PackageOrders->find('all')->where(['id' => $order_id])->hydrate(false)->first();
                if (is_array($orderDetails) && !empty($orderDetails)) {
                    $rslt = [];
                    $rslt['id'] = $orderDetails['id'];
                    $rslt['user_id'] = $orderDetails['user_id'];
                    $rslt['order_id'] = $orderDetails['id'];
                    $rslt['username'] = $this->getUserName($orderDetails['user_id']);
                    $rslt['userphone'] = $this->getPhone($orderDetails['user_id']);
                    $rslt['useraddress'] = $this->getAddress($orderDetails['user_id']);
                    $rslt['usertype'] = $usertype = $this->getUserType($orderDetails['user_id']);
                    $rslt['on_inspections'] = 'N';
                    $rslt['credits_applied'] = 'NO';
                    $rslt['available_service'] = 0;
                    $rslt['use_credits'] = 'NO';
                    $rslt['banner'] = $this->getServiceImagePAth($orderDetails['service_id']);
                    $rslt['schedule_date'] = $orderDetails['service_date']->format('d-M-Y');
                    $rslt['schedule_time'] = '-';
                    $rslt['total_amount'] = 0.00;
                    $rslt['payment_method'] = "FREE SERVICE";
                    //pr($rslt); exit;
                    $this->success('Orders Details!', $rslt);
                } else {
                    $this->wrong('Order Details Not Found!');
                }
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorReviewsDetails() {
        $this->loadModel('Orders');
        $this->loadModel('ServiceReviews');
        $user_id = $this->checkVerifyApiKey('VENDOR');
        if (isset($user_id) && $user_id != '') {
            $getOrdersIds = $this->Orders->find('list', ['keyField' => 'id', 'valueField' => 'order_id'])->where(['vendors_id' => $user_id])->hydrate(false)->toArray();
            if (is_array($getOrdersIds) && !empty($getOrdersIds)) {
                $getOrderReviews = $this->ServiceReviews->find('all')->where(['order_id IN' => $getOrdersIds])->hydrate(false)->toArray();
                $rslt = [];
                $totReviews = $sumReviews = $avgReviews = 0;
                if (is_array($getOrderReviews) && !empty($getOrderReviews)) {
                    foreach ($getOrderReviews as $key => $val) {
                        $tmp = [];
                        $tmp['id'] = $val['id'];
                        $tmp['user_id'] = $val['user_id'];
                        $tmp['user_name'] = $this->getUserName($val['user_id']);
                        $tmp['user_image'] = $this->getUserProfilePicture($val['user_id']);
                        $tmp['review_title'] = $val['review_title'];
                        $tmp['review_description'] = $val['review_description'];
                        $tmp['review_rates'] = $val['review_rates'];
                        $tmp['created'] = $val['created']->format('d-M-Y');
                        $totReviews = $totReviews + 1;
                        $sumReviews = $sumReviews + $val['review_rates'];
                        $rslt[] = $tmp;
                    }
                    if ($totReviews != 0) {
                        $avgReviews = $sumReviews / $totReviews;
                    } else {
                        $avgReviews = 0;
                    }
                    $data = ['reviews' => $rslt, 'avg_rating' => number_format($avgReviews, 2)];
                    $this->success('Order Reviews!', $data);
                } else {
                    $this->wrong('No Reviews!');
                }
            } else {
                $this->wrong('No Order Completed!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function vendorOrderLists() {
        $userId = $this->checkVerifyApiKey('VENDOR');
        if ($userId) {
            $this->loadModel('CartOrders');
            $this->loadModel('Orders');
            $this->loadModel('PackageOrders');
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
            $condArrp = [];
            $condArr["vendors_id"] = $userId;
            $condArrp["vendors_id"] = $userId;
            if ($filter_type == 'date') {
                $condArr["DATE_FORMAT(created_at,'%Y-%m-%d') >="] = $filter_val['from_date'];
                $condArr["DATE_FORMAT(created_at,'%Y-%m-%d') <="] = $filter_val['to_date'];
                $condArrp["DATE_FORMAT(service_date,'%Y-%m-%d') >="] = $filter_val['from_date'];
                $condArrp["DATE_FORMAT(service_date,'%Y-%m-%d') <="] = $filter_val['to_date'];
            } else if ($filter_type == 'year') {
                $condArr["DATE_FORMAT(created_at,'%Y')"] = $filter_val['year'];
                $condArrp["DATE_FORMAT(service_date,'%Y')"] = $filter_val['year'];
            }
            //pr($filter_val['order_status']); exit;
            if (isset($filter_val['order_status']) && $filter_val['order_status'] != '') {
                $condArr["status"] = $filter_val['order_status'];
                $condArrp["service_status"] = $filter_val['order_status'];
            }
            $orderLists = [];
            $orders = $this->Orders->find('all')->select(['cart_id', 'status', 'order_id', 'created_at'])->where($condArr)->order(['created_at' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            $porders = $this->PackageOrders->find('all')->select(['service_name', 'service_image', 'service_status', 'id', 'service_date'])->where($condArrp)->order(['service_date' => 'DESC'])->limit(PAGINATION_LIMIT)->page($page_no)->hydrate(false)->toArray();
            if (!empty($orders) || !empty($porders)) {
                foreach ($orders as $val) {
                    $tmp = [];
                    $serviceArr = $this->CartOrders->find('all')->select(['service_id'])->where(['cart_id' => $val['cart_id']])->hydrate(false)->first();
                    $service_id = $serviceArr['service_id'];
                    $tmp['name'] = $this->Services->getServiceName($service_id);
                    $tmp['images'] = $this->Services->getServiceImagePath($service_id);
                    $tmp['status'] = $val['status'];
                    $tmp['order_id'] = $val['order_id'];
                    $tmp['order_type'] = "NORMAL";
                    $tmp['job_type'] = "REPORT";
                    $tmp['date'] = $val['created_at']->format('d-M-Y');
                    $orderLists[] = $tmp;
                }
                foreach ($porders as $val) {
                    $tmp = [];
                    //$serviceArr = $this->CartOrders->find('all')->select(['service_id'])->where(['cart_id' => $val['cart_id']])->hydrate(false)->first();
                    $service_id = $serviceArr['id'];
                    $tmp['name'] = $val['service_name'];
                    $tmp['images'] = IMAGE_URL_PATH . 'services/square/' . $val['service_image'];
                    $tmp['status'] = $val['service_status'];
                    $tmp['order_id'] = $val['id'];
                    $tmp['order_type'] = "PACKAGE";
                    $tmp['job_type'] = "REPORT";
                    $tmp['date'] = $val['service_date']->format('d-M-Y');
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

    public function packageServiceBook() {
        $user_id = $this->checkVerifyApiKey('CUSTOMER');
        if ($user_id) {
            $this->loadModel('PackageOrders');
            $requestArr = $this->getInputArr();
            $requiredFields = array(
                'Package Service Id' => (isset($requestArr['package_service_id']) && $requestArr['package_service_id'] != '') ? $requestArr['package_service_id'] : '',
                'Service Date' => (isset($requestArr['service_date']) && $requestArr['service_date'] != '') ? $requestArr['service_date'] : '',
            );
            $validate = $this->checkRequiredFields($requiredFields);
            if ($validate != "") {
                $this->wrong($validate);
            }
            $packageService = $this->PackageOrders->get($requestArr['package_service_id']);
            if (!empty($packageService)) {
                $updateFields = [];
                $updateFields['service_status'] = 'PLACED';
                $packageService = $this->PackageOrders->patchEntity($packageService, $updateFields);
                $packageService->service_date = date('Y-m-d', strtotime($requestArr['service_date']));
                $packageService->modified_by = $user_id;
                $packageService->modified = date('Y-m-d H:i:s');
                if ($this->PackageOrders->save($packageService)) {
                    $packageOrders = $this->getPackageOrders($user_id);
                    $this->success('PackageOrder Updated!', $packageOrders);
                } else {
                    $this->wrong('PackageOrder Updated Failed!');
                }
            } else {
                $this->wrong('Service not found!');
            }
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function creditOrderHistory() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('CartOrders');
            $this->loadModel('Orders');
            $this->loadModel('Services');
            $condArr = [];
            $condArr["user_id"] = $userId;
            $condArr["payment_method"] = 'CREDITS';
            $condArr["credits_applied"] = 'Y';
            $orderLists = [];
            $orders = $this->Orders->find('all')->select(['cart_id', 'status', 'order_id', 'created_at'])->where($condArr)->order(['created_at' => 'DESC'])->hydrate(false)->toArray();
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
            $this->success('orders fetched successfully', $orderLists);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function membershipDetails() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Users');
            $user = $this->Users->get($userId);
            $rslt = [];
            $rslt['usertype'] = $usertype = $user['user_type'];
            $rslt['can_update'] = 'N';
            if ($usertype == 'MEMBERSHIP') {
                $rslt['avail_credits'] = $this->getMemberCredits($user['id']);
                $rslt['total_credits'] = $this->getMemberTotalCredits($user['id']);
                $rslt['plan_name'] = $this->getMembershipPlanname($user['plan_id']);
                $rslt['plan_image'] = $this->getMembershipPlanimage($user['plan_id']);
                if (in_array($user['plan_id'], [RUBIES_PLAN_ID, SAPPHIRES_PLAN_ID, BOOM_AC_PLAN_ID, ZOOM_RO_PLAN_ID])) {
                    $packageOrders = $this->getPackageOrders($user['id']);
                    $rslt['free_services'] = $packageOrders;
                }
                if (in_array($user['plan_id'], [RUBIES_PLAN_ID, SAPPHIRES_PLAN_ID])) {
                    $rslt['can_update'] = 'Y';
                }
            }
            $rslt['wallet_amount'] = $this->walletAmount($user['id']);
            $this->success('Info Fatched!', $rslt);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

    public function getServiceLists() {
        $this->loadModel('Services');
        $rsltArr = [];
        $tmpS = [];
        $tServices = $this->Services->find('all')->select(['id', 'service_name', 'service_description', 'service_specification', 'visit_charge', 'minimum_charge', 'square_image'])->where(['status' => 'ACTIVE'])->order(['id' => 'ASC'])->hydrate(false)->toArray();
        if (!empty($tServices)) {
            foreach ($tServices as $v) {
                $tmpS['service_id'] = $v['id'];
                $tmpS['service_name'] = $v['service_name'];
                $tmpS['banner_image'] = IMAGE_URL_PATH . 'services/square/' . $v['square_image'];
                $tmp[] = $tmpS;
            }
            $rsltArr = $tmp;
        }
        $this->success('Homepage Data Fateched!', $rsltArr);
    }

    public function sendPlanInvoiceEmails($planID, $userDetails) {
        if (isset($planID) && $planID != '') {
            $this->layout = 'ajax';
            $view_output = '';
            $mailData = [];
            $mailData['username'] = $userDetails['name'];
            $mailData['usermobile'] = $userDetails['phone_no'];
            $mailData['email'] = $userDetails['email'];
            $mailData['plain_pass'] = $userDetails['plain_pass'];
            $mailData['member_id'] = $userDetails['membership_id'];
            $mailData['plan_name'] = $this->getPlanNames($planID);
            $mailData['plan_details'] = $this->getPlanDetails($planID);
            $planRate = $this->getPlanRates($planID);
            $mailData['plan_rate'] = number_format($planRate, 2);
            $gst = $planRate * GST_TAX / 100;
            $mailData['tax'] = number_format($gst, 2);
            $tots = $planRate + $gst;
            $mailData['total'] = number_format($tots, 2);
            $emailAddress = $userDetails['email'];
            $this->set('mailData', $mailData);
            $view_output = $this->render('/Element/plan_invoice');
            //pr($view_output); exit;
            $fields = array(
                'msg' => $view_output,
                'tomail' => $emailAddress,
                //'cc_email' => $patient['email'],
                'subject' => 'Invoice for Membership Plan',
                'from_name' => 'Uncode Lab',
                'from_mail' => 'uncodelab@gmail.com',
            );
            $this->sendemails($fields);
            return;
        }
    }

}
