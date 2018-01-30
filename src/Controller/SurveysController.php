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

class SurveysController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function index() {
        $serveys = [];
        $this->loadModel('Surveys');
        $this->set('salesLists', $this->getSalesList());
        $filters = $this->Surveys->newEntity();
        $this->set('filters', $filters);
        $condArr = [];
        if ($this->request->is('post')) {
            //pr($this->request->data); //exit;
            if (isset($this->request->data['sales_id']) && $this->request->data['sales_id'] != 0) {
                $condArr['created_by'] = $this->request->data['sales_id'];
            }
            if (isset($this->request->data['contact_number']) && $this->request->data['contact_number'] != 0) {
                $condArr['contact_number'] = $this->request->data['contact_number'];
            }
            if (isset($this->request->data['from_date']) && $this->request->data['from_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
            }
            if (isset($this->request->data['to_date']) && $this->request->data['to_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
            }
            if (isset($this->request->data['area_type']) && $this->request->data['area_type'] != '') {
                $condArr['user_type'] = $this->request->data['area_type'];
            }
            $filter = $this->request->data;
            $this->set('filter', $filter);
            //pr($condArr); exit;
        }
        $serveys = $this->Surveys->find('all')->where($condArr)->order(['id' => 'DESC'])->hydrate(false)->toArray();
        foreach ($serveys as $key => $val) {
            $serveys[$key]['survey_by'] = $this->getUserName($val['created_by']);
        }
        $this->set('serveys', $serveys);
    }

    public function view($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Surveys');
            $serveys = $this->Surveys->find('all')->where(['id' => $id])->order(['id' => 'DESC'])->hydrate(false)->first();
            if (isset($serveys) && !empty($serveys)) {
                $serveys['survey_by'] = $this->getUserName($serveys['created_by']);
                if (isset($serveys['what_service_or_repair_work_usually_you_perform_at_your_place']) && $serveys['what_service_or_repair_work_usually_you_perform_at_your_place'] != '') {
                    $servicesArr = [];
                    $servicesArr = explode(",", $serveys['what_service_or_repair_work_usually_you_perform_at_your_place']);
                    if (!empty($servicesArr)) {
                        foreach ($servicesArr as $val) {
                            $tmpArr[] = str_replace("_", " ", $val);
                        }
                    } else {
                        $tmpArr = ['-'];
                    }
                    $serveys['services_name'] = implode(", ",$tmpArr);
                }
                $this->set('servey', $serveys);
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function edit($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Surveys');
            $serveys = $this->Surveys->get($id);
            if (!is_array($serveys) && !empty($serveys)) {
                if (isset($serveys['what_service_or_repair_work_usually_you_perform_at_your_place']) && $serveys['what_service_or_repair_work_usually_you_perform_at_your_place'] != '') {
                    $servicesArr = explode(",", $serveys['what_service_or_repair_work_usually_you_perform_at_your_place']);
                    $serveys->services_name = $this->getServicesName($servicesArr);
                }
                if ($this->request->is(['patch', 'post', 'put'])) {
                    $appoinment_date = $this->request->data['appoinment_date'];
                    unset($this->request->data['appoinment_date']);
                    $serveys = $this->Surveys->patchEntity($serveys, $this->request->data);
                    $serveys->appoinment_date = date('Y-m-d', strtotime($appoinment_date));
                    $serveys->modified = date("Y-m-d H:i:s");
                    $serveys->modified_by = $this->request->session()->read('Auth.User.id');
                    if ($this->Surveys->save($serveys)) {
                        $this->Flash->success(Configure::read('Settings.SAVE'));
                        return $this->redirect(['action' => 'index']);
                    } else {
                        $this->Flash->error(Configure::read('Settings.FAIL'));
                    }
                }
                
                $this->set('serveys', $serveys);
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

    public function getUserName($userId) {
        $userTable = TableRegistry::get('Users');
        $user = $userTable->find()->select(['name'])->where(['id' => $userId])->hydrate(false)->first();
        return $user['name'];
    }

    public function getSalesList() {
        $this->loadModel('Users');
        return $this->Users->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->where(['user_type' => 'SALES'])->hydrate(false)->toArray();
    }

}
