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
use App\Model\Validation\ServicesValidator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : ServicecategoryController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class RatecardsController extends AppController {

    public function beforeFilter(Event $event) {
        $this->Auth->allow(['updaterate', 'addnewrate','delete']);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function index($service_id) {
        $questions = [];
        $this->loadModel('ServiceRatecards');
        $ratecard = $this->ServiceRatecards->find()->where(['service_id' => $service_id])->hydrate(false)->toArray(); //LISTING RATECARDS
        //pr($ratecard); exit;
        $this->set('ratecard', $ratecard);
        $this->set('service_id', $service_id);
    }

    //***********************************************************************************************//
    // * Function     :  add
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function add($service_id) {
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $ratecard = $this->ServiceRatecards->newEntity();
        if ($this->request->is('post')) {
            $rC = $rcR = [];
            $rC['service_id'] = $service_id;
            $rC['title'] = $this->request->data['title'];
            $rC['qunatity'] = $this->request->data['quantity'];
            $rC['price'] = (isset($this->request->data['price']) && $this->request->data['price'] != '') ? $this->request->data['price'] : 0.00;
            $rC['created_by'] = $this->request->session()->read('Auth.User.id');
            $ratecard = $this->ServiceRatecards->patchEntity($ratecard, $rC);
            $ratecard->created = date("Y-m-d H:i:s");
            $rslt = $this->ServiceRatecards->save($ratecard);
            if ($rslt->id) {
                if (isset($this->request->data['rate']) && !empty($this->request->data['rate'])) {
                    foreach ($this->request->data['rate'] as $key => $val) {
                        $rate = $this->ServiceRatecardRates->newEntity();
                        $rcR['ratecards_id'] = $rslt->id;
                        $rcR['qunatity_title'] = $val['label'];
                        $rcR['rate'] = $val['price'];
                        $rcR['created_by'] = $this->request->session()->read('Auth.User.id');
                        $rate = $this->ServiceRatecardRates->patchEntity($rate, $rcR);
                        $rate->created = date("Y-m-d H:i:s");
                        if ($this->ServiceRatecardRates->save($rate)) {
                            
                        } else {
                            $this->Flash->error(Configure::read('Settings.FAIL'));
                        }
                    }
                }
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'index', $service_id]);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('ratecard', $ratecard);
        $this->set('service_id', $service_id);
    }

    //***********************************************************************************************//
    // * Function     :  edit
    // * Parameter    :  
    // * Description  :  This function used to edit Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function edit($service_id, $id) {
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $ratecards = $this->ServiceRatecards->get($id); //LISTING USERDATA
        //pr($ratecard); exit;
        if (isset($ratecards) && !empty($ratecards)) {
            $ratecard = $this->ServiceRatecards->get($id); //LISTING USERDATA
            $ratecards['rates'] = $this->ServiceRatecardRates->find()->where(['ratecards_id' => $ratecards['id']])->hydrate(false)->toArray(); //LISTING SERVICES
            if ($this->request->is(['patch', 'post', 'put'])) {
                $ratecard = $this->ServiceRatecards->patchEntity($ratecard, $this->request->data());
                $ratecard->modified_by = $this->request->session()->read('Auth.User.id');
                $ratecard->modified = date("Y-m-d H:i:s");
                //pr($ratecards); exit;
                if ($this->ServiceRatecards->save($ratecard)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index', $service_id]);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            }
            $this->set('ratecard', $ratecards);
            $this->set('service_id', $service_id);
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index', $service_id]);
        }
    }

    //***********************************************************************************************//
    // * Function     :  view
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories details
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function view($service_id, $id) {
        $ratecards = [];
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $ratecards = $this->ServiceRatecards->find()->where(['id' => $id])->hydrate(false)->first(); //LISTING SERVICES
        //pr($ratecards); exit;
        if (!empty($ratecards)) {
            if ($ratecards['qunatity'] == 'YES') {
                $ratecards['rates'] = $this->ServiceRatecardRates->find()->where(['ratecards_id' => $ratecards['id']])->hydrate(false)->toArray(); //LISTING SERVICES
            }
            $category_id = $this->getCategoryId($ratecards['service_id']);
            $ratecards['service_name'] = $this->getServiceName($ratecards['service_id']);
            $ratecards['category_name'] = $this->getCategoryName($category_id);
        }
        $this->set('service_id', $service_id);
        $this->set('ratecards', $ratecards);
    }

    //***********************************************************************************************//
    // * Function     :  delete
    // * Parameter    :  
    // * Description  :  This function used to get Services details
    // * Author       :  Chirag Manavar
    // * Date         :  26-October-2017
    //***********************************************************************************************//
    public function delete($service_id) {
        $id = $this->request->data['value'];
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        if (isset($id) && $id != '') {
            $ratecards = $this->ServiceRatecards->find()->where(['id' => $id, 'service_id' => $service_id])->hydrate(false)->first(); //LISTING SERVICES
            if (isset($ratecards) && !empty($ratecards)) {
                $ratecard = $this->ServiceRatecards->get($id); //LISTING USERDATA
                if ($this->ServiceRatecards->delete($ratecard)) {
                    if ($this->ServiceRatecardRates->deleteAll(['ServiceRatecardRates.ratecards_id' => $id])) {
                        $this->Flash->success(Configure::read('Settings.DELETE'));
                        $this->redirect(array('action' => 'index',$service_id));
                        exit;
                    }
                } else {
                    $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                    $this->redirect(array('action' => 'index'));
                    exit;
                }
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                $this->redirect(['action' => 'index', $service_id]);
                exit;
            }
        } else {
            $this->Flash->error(__('RATECARD ID IS MISSING'));
            $this->redirect(['action' => 'index', $service_id]);
            exit;
        }
    }

    //***********************************************************************************************//
    // * Function     :  getanswer
    // * Parameter    :  
    // * Description  :  This function used to get Services details
    // * Author       :  Chirag Manavar
    // * Date         :  26-October-2017
    //***********************************************************************************************//
    public function getanswers($id) {
        $this->loadModel('ServiceQuestionAnswers');
        $answersArr = $this->ServiceQuestionAnswers->find('list', ['keyField' => 'id', 'valueField' => 'label'])->where(['question_id' => $id])->toArray();
        if (isset($answersArr) && !empty($answersArr)) {
            $str = '';
            foreach ($answersArr as $key => $val) {
                $str .= '<option value="' . $key . '">' . $val . '</option>';
            }
            //$this->common->success('Answer Fatched!', $str);
            $this->data = ['status' => 'success', 'msg' => 'Answer Fetched!', 'data' => $str];
        } else {
            //$this->common->fail("Sorry, Answer not found!");
            $this->data = ['status' => 'fail', 'msg' => 'Answer not found!'];
        }
        echo json_encode($this->data);
        exit;
    }

    public function updaterate($id) {
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $rates = $this->ServiceRatecardRates->get($id);
        if (isset($rates) && !empty($rates)) {
            $ratecard = $this->ServiceRatecards->find('all')->select(['service_id'])->where(['id' => $rates->ratecards_id])->hydrate(false)->first();
            $service_id = $ratecard['service_id'];
            //echo $service_id; exit;
            $updatedArr = [];
            $updatedArr['qunatity_title'] = $_POST['title'];
            $updatedArr['rate'] = $_POST['price'];
            $rates = $this->ServiceRatecardRates->patchEntity($rates, $updatedArr);
            $rates->modified_by = $this->request->session()->read('Auth.User.id');
            $rates->modified = date("Y-m-d H:i:s");
            if ($this->ServiceRatecardRates->save($rates)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'index', $service_id, $id]);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        } else {
            $this->data = ['status' => 'fail', 'msg' => 'Answer not found!'];
        }
        echo json_encode($this->data);
        exit;
    }

    public function addnewrate($id) {
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $ratecatd = $this->ServiceRatecards->get($id);
        if (isset($ratecatd) && !empty($ratecatd)) {
            $rates = $this->ServiceRatecardRates->newEntity();
            $service_id = $ratecatd->service_id;
            $updatedArr = [];
            $updatedArr['ratecards_id'] = $id;
            $updatedArr['qunatity_title'] = $_POST['title'];
            $updatedArr['rate'] = $_POST['price'];
            $rates = $this->ServiceRatecardRates->patchEntity($rates, $updatedArr);
            $rates->created_by = $this->request->session()->read('Auth.User.id');
            $rates->created = date("Y-m-d H:i:s");
            if ($this->ServiceRatecardRates->save($rates)) {
                EXIT;
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'index', $service_id, $id]);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
                return $this->redirect(['action' => 'index', $service_id, $id]);
            }
        } else {
            $this->Flash->error('Question not found');
            return $this->redirect(['action' => 'index', $service_id, $id]);
        }
        echo json_encode($this->data);
        exit;
    }

    public function deleterates($id) {
        $this->loadModel('ServiceRatecards');
        $this->loadModel('ServiceRatecardRates');
        $ratecard = $this->ServiceRatecards->get($id);
        if (isset($ratecard) && !empty($ratecard)) {
            $service_id = $ratecard->service_id;
            if (isset($_POST['value']) && $_POST['value'] != '') {
                $rate_id = $_POST['value'];
                $rate = $this->ServiceRatecardRates->get($rate_id);
                if ($this->ServiceRatecardRates->delete($rate)) {
                    $this->Flash->error('Rate delete successfully!');
                    return $this->redirect(['action' => 'index', $service_id, $id]);
                } else {
                    $this->Flash->error('Sorry, Something wrong!');
                    return $this->redirect(['action' => 'index', $service_id, $id]);
                }
            } else {
                $this->Flash->error('Rate id is missing!');
                return $this->redirect(['action' => 'index', $service_id, $id]);
            }
        } else {
            $this->Flash->error('Ratecard not found');
            return $this->redirect(['action' => 'index', $service_id, $id]);
        }
        echo json_encode($this->data);
        exit;
    }

}
