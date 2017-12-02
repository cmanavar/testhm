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
        $this->Auth->allow(['homepage', 'categoryDetails', 'categoryList', 'serviceDetails', 'getServicesSubQuestions', 'helpDetails',
            'createCart', 'addCartProduct', 'cartDetails', 'cartClear', 'removeCartProduct', 'counteunreadmsg', 'msgList', 'msgView',
            'cartOrderPlaced', 'forgorPassword', 'changePassword', 'applyCouponCode', 'walletDetails']);
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
            $this->loadModel('Messages');
            $unseen_count = 0;
            $msgList = $this->Messages->find('all')->select(['id', 'user_id', 'message_title', 'type', 'message_detail', 'seen', 'created_at', 'modified_at'])->where(['user_id' => $user_id, 'seen' => 'N'])->hydrate(false)->toArray();
            if (isset($msgList) && !empty($msgList)) {
                $msg = [];
                foreach ($msgList as $key => $message) {
                    $tmp = [];
                    $tmp['id'] = $message['id'];
                    $tmp['user_id'] = $message['user_id'];
                    $tmp['message_title'] = $message['message_title'];
                    $tmp['type'] = $message['type'];
                    $tmp['message_detail'] = $message['message_detail'];
                    $tmp['seen'] = $message['seen'];
                    if ($tmp['seen'] == 'N') {
                        $unseen_count++;
                    }
                    $msg[] = $tmp;
                }
                $resp_data = ['unseen_count' => $unseen_count, 'messages' => $msg];
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
        $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'icon_image'])->where(['status' => 'ACTIVE'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
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
        $category = $this->ServiceCategory->find('all')->select(['id', 'name', 'banner_image'])->where(['status' => 'ACTIVE'])->order(['order_id' => 'ASC'])->hydrate(false)->toArray();
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
                $rslt['service_details'] = $sDetails['service_description'];
                $rslt['visit_charge'] = $sDetails['visit_charge'];
                $rslt['minimum_charge'] = $sDetails['minimum_charge'];
                $rslt['banner_image'] = IMAGE_URL_PATH . 'services/banner/' . $sDetails['banner_image'];
                // Questions - Start
                $questionArr = [];
                $quesArr = $this->ServiceQuestions->find('all')->where(['category_id' => $sDetails['category_id'], 'service_id' => $sDetails['id'], 'questions_type' => 'parent'])->hydrate(false)->toArray();
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
                                $tmpA['quantity'] = $v['quantity'];
                                $tmpA['price'] = $v['price'];
                                $tmpA['child_questions'] = ($this->checkChildQuestionsExist($val['id'], $v['id'])) ? 'Yes' : 'No';
                                $answerArrs[] = $tmpA;
                            }
                        }
                        $tmp['answers'] = $answerArrs;
                        $questionArr[] = $tmp;
                    }
                }
                $rslt['questions'] = $questionArr;
                // Questions - End
                // Review - Start
                $reviewsArr = [];
                $reviews = $this->ServiceReviews->find('all')->where(['service_id' => $sDetails['id']])->hydrate(false)->toArray();
                foreach ($reviews as $review) {
                    $tmpArr = $userData = [];
                    $tmpArr['review_title'] = $review['review_title'];
                    $tmpArr['review_description'] = $review['review_description'];
                    $tmpArr['review_rates'] = $review['review_rates'];
                    $userData = $this->Users->getuserId($review['user_id'])->toArray();
                    $tmpArr['service_name'] = $this->Services->getServiceName($val['service_id']);
                    $tmpArr['user_name'] = $userData['name'];
                    $tmpArr['user_pic'] = ($userData['profile_pic'] != '') ? IMAGE_URL_PATH . 'users/' . $userData['profile_pic'] : '';
                    $reviewsArr[] = $tmpArr;
                }
                $rslt['service_reveiws'] = $reviewsArr;
                // Review - End
                // Ratecard - Start
                $rateArr = [];
                $rateCards = $this->ServiceRatecards->find('all')->where(['service_id' => $sDetails['id']])->hydrate(false)->toArray();
                if (!empty($rateCards)) {
                    $tmpCount = 0;
                    foreach ($rateCards as $ratecard) {
                        //pr($ratecard); exit;
                        if ($ratecard['qunatity'] == 'YES') {
                            $rates = $this->ServiceRatecardRates->find('all')->where(['ratecards_id' => $ratecard['id']])->hydrate(false)->toArray();
                            if (!empty($rates)) {
                                foreach ($rates as $rate) {
                                    $tmpCount = $tmpCount + 1;
                                    $tmp = [];
                                    $tmp['id'] = $tmpCount;
                                    $tmp['service_id'] = $ratecard['service_id'];
                                    $tmp['title'] = $ratecard['title'];
                                    $tmp['title2'] = $rate['qunatity_title'];
                                    //$tmp['qunatity'] = ucwords(strtolower(str_replace('_', ' ', $ratecard['qunatity'])));
                                    $tmp['qunatity'] = $ratecard['qunatity'];
                                    $tmp['price'] = number_format($rate['rate'], 2);
                                    $rateArr[] = $tmp;
                                }
                            }
                        } else {
                            $tmpCount = $tmpCount + 1;
                            $tmp = [];
                            $tmp['id'] = $tmpCount;
                            $tmp['service_id'] = $ratecard['service_id'];
                            $tmp['title'] = $ratecard['title'];
                            $tmp['title2'] = '';
                            //$tmp['qunatity'] = ucwords(strtolower(str_replace('_', ' ', $ratecard['qunatity'])));
                            $tmp['qunatity'] = $ratecard['qunatity'];
                            $tmp['price'] = number_format($ratecard['price'], 2);
                            $rateArr[] = $tmp;
                        }
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

    function checkChildQuestionsExist($question_id, $answer_id) {
        $cond_arr = ['parent_question_id' => $question_id, 'parent_answer_id' => $answer_id, 'questions_type' => 'child'];
        $counts = $this->ServiceQuestions->find('all')->where($cond_arr)->hydrate(false)->count();
        if (isset($counts) && ($counts != 0)) {
            return true;
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
                            $tmpA['quantity'] = $v['quantity'];
                            $tmpA['price'] = $v['price'];
                            $tmpA['child_questions'] = ($this->checkChildQuestionsExist($val['id'], $v['id'])) ? 'Yes' : 'No';
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
                echo json_encode(['status' => 'fail', 'msg' => 'Sorry, Your cart is already in process, please cancelled it.', 'data' => ['cart_id' => $checkArrs['id'], 'service_name' => $serviceName]]);
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
                // Check Cart is exist or not
                $checkCart = $this->Carts->find('all')->where(['user_id' => $user_id, 'id' => $cartId, 'service_id' => $serviceId, 'status' => 'PROCESS'])->hydrate(false)->first();
                if ($checkCart) {
                    $serviceDetails = $this->Services->find('all')->where(['id' => $serviceId])->hydrate(false)->first();
                    if (!isset($serviceDetails) || empty($serviceDetails)) {
                        $this->wrong("Sorry, Service Details not found!");
                    }
                    // Check Cart is already Exist or not
                    $checkCart = $this->Carts->find('all')->where(['id' => $requestArr['cart_id'], 'status' => 'PROCESS'])->hydrate(false)->first();
                    if (!empty($checkCart)) {
                        $questionsData = isset($requestArr['questions_data']) ? $requestArr['questions_data'] : array();
                        if (empty($questionsData)) {
                            $this->wrong("Sorry, Questions data is not found!");
                        } else {
                            foreach ($questionsData as $questions) {
                                $questionDetails = $questionStoreDetails = [];
                                $question_id = $questions->question_id;
                                $answer_id = $questions->answer_id;
                                $quantity = isset($questions->question_quantity) ? $questions->question_quantity : "";
                                $answer_text = isset($questions->question_text_ans) ? $questions->question_text_ans : "";
                                $questionDetails = $this->getQuestionDetails($question_id, $answer_id);
                                //pr($questionDetails); exit;
                                if (!empty($questionDetails)) {
                                    if (isset($questionDetails['quantity']) && ($questionDetails['quantity'] != '')) {
                                        //print_r($questionDetails['quantity']); exit;
                                        if ($questionDetails['quantity'] == 'YES') {
                                            $on_inspection = 'N';
                                            //print_r($questionDetails['price']); exit;
                                            if ($questionDetails['price'] != '0') {

                                                if (strpos($questionDetails['answer'], '-') !== false) {
                                                    $explodeArr = explode('-', $questionDetails['answer']);
                                                    $min_quantity = $explodeArr[0];
                                                    $max_quantity = $explodeArr[1];
                                                    if ($quantity >= $min_quantity && $quantity <= $max_quantity) {
                                                        $total_price = $questionDetails['price'] * $quantity;
                                                    } else {
                                                        $this->wrong('Sorry, Quantity values is out of range ' . $min_quantity . ' to ' . $max_quantity);
                                                    }
                                                } else if (strpos($questionDetails['answer'], '+') !== false) {
                                                    $explodeArr = explode('+', $questionDetails['answer']);
                                                    $min_quantity = $explodeArr[0];
                                                    if ($min_quantity <= $quantity) {
                                                        $total_price = $questionDetails['price'] * $quantity;
                                                    } else {
                                                        $msg = 'Sorry, Quantity must be grater than ' . $min_quantity . '.because you select ' . $questionDetails['answer'];
                                                        $this->wrong($msg);
                                                    }
                                                } else {
                                                    $total_price = $questionDetails['price'] * $quantity;
                                                }
                                            }
                                        } else if ($questionDetails['quantity'] == 'NO') {
                                            $on_inspection = 'N';
                                            if ($questionDetails['price'] != '0') {
                                                $total_price = $questionDetails['price'];
                                            } else {
                                                $total_price = '0';
                                            }
                                        } else {
                                            $on_inspection = 'Y';
                                            $total_price = '0';
                                        }
                                    } else {
                                        $this->wrong("Sorry, Questions quantity is not found!.");
                                    }
                                    //print_r($questionDetails);
                                } else {
                                    $this->wrong("Sorry, Questions data is not found!.");
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
                        $cartOrders->created_at = date('Y-m-d H:i:s');
                        $cartOrderSave = $this->CartOrders->save($cartOrders);
                        if ($cartOrderSave) {
                            $cartOrderId = $cartOrderSave['id'];
                            if (!empty($questionsData)) {
                                //print_R($queData); exit;
                                $flag = false;
                                foreach ($questionsData as $queData) {
                                    $queAnsData = $this->CartOrderQuestions->newEntity();
                                    $question_id = $queData->question_id;
                                    $answer_id = $queData->answer_id;
                                    $question_quantity = isset($queData->question_quantity) ? $queData->question_quantity : '';
                                    $question_text_ans = isset($queData->question_text_ans) ? $queData->question_text_ans : '';
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
            if (isset($checkCart) && !empty($checkCart)) {
                $cartPriceDetails = $this->totalCartPrice($cartId);
                $this->success("Cart Order Details Fetched!", $cartPriceDetails);
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
            foreach ($cartOrders as $order) {
                $tmp = [];
                $tmp['cart_order_id'] = $order['id'];
                $tmp['category_name'] = $this->Services->getCategoryName($order['category_id']);
                $tmp['service_name'] = $this->Services->getServiceName($order['service_id']);
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
                $ordersDetails[] = $tmp;
            }
            $total['on_inspection'] = 'N';
            $total['order_amount'] = 0.00;
            $total['tax'] = 0.00;
            $total['total_amount'] = 0.00;
            $order_amount = 0.00;
            foreach ($ordersDetails as $od) {
                if (isset($od['on_inspection']) && $od['on_inspection'] == 'Y') {
                    $total['on_inspection'] = 'Y';
                }
                $order_amount += $od['total_amount'];
            }
            $total['order_amount'] = $order_amount;
            $total['tax'] = $order_amount * GST_TAX / 100;
            $total['total_amount'] = $total['order_amount'] + $total['tax'];
            return ['services' => $ordersDetails, 'total' => $total];
        } else {
            $this->wrong('Cart Id is missing!');
        }
    }

    function getQuestionDetails($question_id, $answer_id) {
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
                $orderData['category_id'] = $cartExist['category_id'];
                $orderData['service_id'] = $cartExist['service_id'];
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
                $orderData['amount'] = $cartDetails['total']['order_amount'];
                $orderData['tax'] = $cartDetails['total']['tax'];
                $orderData['total_amount'] = $cartDetails['total']['total_amount'];
                $orderData['discount'] = 0.00;
                $orderData['payable_amount'] = 0.00;
                $orderData['payment_method'] = '';
                $orderData['status'] = 'PENDING';
                $orderData['reason_order_cancelled'] = '';
                $orderData['payment_status'] = 'PENDING';
                $orderData['cart_product'] = json_encode($cartDetails);
                $orderData['created_by'] = $orderData['modified_by'] = $user_id;
                //pr($cartDetails); //exit;
                // Discount Coupon applied or not
                if ($coupon_code != '') {
                    $orderData['on_inspections'] = (isset($cartDetails['total']['on_inspection']) && $cartDetails['total']['on_inspection'] == 'Y') ? $cartDetails['total']['on_inspection'] : 'N';
                    $couponCodeDetails = $this->Coupons->find('all')->where(['code' => $coupon_code])->hydrate(false)->first();
                    if (isset($couponCodeDetails) && !empty($couponCodeDetails)) {
                        $todayDate = date('Y-m-d');
                        $validTo = $couponCodeDetails['valid_to']->format('Y-m-d');
                        $validFrom = $couponCodeDetails['valid_from']->format('Y-m-d');
                        $curDate = strtotime($todayDate);
                        if ($curDate > strtotime($validTo) && $curDate < strtotime($validFrom)) {
                            $orderData['is_coupon_applied'] = 'Y';
                            $orderData['coupon_code'] = $coupon_code;
                            $discount = 0.00;
                            if (isset($couponCodeDetails['discount_type']) && $couponCodeDetails['discount_type'] == 'PRICE') {
                                $discount = $couponCodeDetails['amount'];
                                $orderData['discount'] = number_format($discount, 2);
                                $orderData["payable_amount"] = number_format($cartDetails["total"]["total_amount"], 2);
                                //$orderData["payable_amount"] = number_format($cartDetails["total"]["total_amount"] - $discount, 2);
                            } else {
                                $discountPercentage = $couponCodeDetails['amount'];
                                if ($cartDetails["total"]["total_amount"] != 0.00) {
                                    $discount = ($couponCodeDetails['amount'] * $cartDetails["total"]["total_amount"]) / 100;
                                    $orderData['discount'] = number_format($discount, 2);
                                    $orderData["payable_amount"] = number_format($cartDetails["total"]["total_amount"], 2);
                                    //$orderData["payable_amount"] = number_format($cartDetails["total"]["total_amount"] - $discount, 2);
                                } else {
                                    $discount = $couponCodeDetails['amount'] . "%";
                                    $orderData['discount'] = $discount;
                                    $orderData["payable_amount"] = number_format($cartDetails["total"]["total_amount"], 2);
                                }
                            }
                        }
                    }
                }
                // Discount Coupon applied or not
                // Wallet Amount Apply
                if ($wallet_amount != '') {
                    $orderData['wallet_amount'] = $wallet_amount;
                    if ($cartDetails["total"]["total_amount"] != 0.00) {
                        $orderData["payable_amount"] = number_format($orderData["payable_amount"] - $wallet_amount, 2);
                    } else {
                        $orderData["payable_amount"] = number_format($orderData["payable_amount"], 2);
                    }
                }
                // Wallet Amount Apply
                $order = $this->Orders->patchEntity($order, $orderData);
                $order->schedule_date = date('Y-m-d', strtotime($requestArr['schedule_date']));
                $order->created = date('Y-m-d H:i:s');
                $order->modified = date('Y-m-d H:i:s');
                if ($this->Orders->save($order)) {
                    $cartArr = $this->Carts->get($cart_id);
                    $cartUpdate['status'] = 'PLACED';
                    $cartArr = $this->Carts->patchEntity($cartArr, $cartUpdate);
                    if ($this->Carts->save($cartArr)) {
                        $this->success('Order Placed Successfully!');
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

    public function walletDetails() {
        $userId = $this->checkVerifyApiKey('CUSTOMER');
        if ($userId) {
            $this->loadModel('Wallets');
            $walletHistory = $this->Wallets->find('all')->where(['user_id' => $userId])->order(['id' => 'DESC'])->hydrate(false)->toArray();
            //pr($walletHistory);
            //exit;
            $rslt = [];
            if (isset($walletHistory) && !empty($walletHistory)) {
                foreach ($walletHistory as $history) {
                    $tmp = [];
                    $tmp['id'] = $history['id'];
                    $tmp['user_id'] = $history['user_id'];
                    $tmp['amount'] = $history['amount'];
                    $tmp['wallet_type'] = $history['wallet_type'];
                    $tmp['created'] = $history['created']->format('d-M-Y');
                    if ($history['purpose'] == 'REFERRAL') {
                        $userName = $this->getUserName($history['purpose_id']);
                        $tmp['details'] = 'Received Rewarded for refer to ' . $userName;
                    } else if ($history['purpose'] == 'ORDER') {
                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Paid for Order. id #' . $orderId;
                    } else if ($history['purpose'] == 'CASHBACK') {
                        $orderId = $this->getOrderId($history['purpose_id']);
                        $tmp['details'] = 'Received Cashback for Order. id #' . $orderId;
                    }
                    $rslt[] = $tmp;
                }
            }
            $this->success('Wallet Data Fatched!', $rslt);
        } else {
            $this->wrong('Invalid API key.');
        }
    }

}
