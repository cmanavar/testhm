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

class ReportsController extends AppController {

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
        
    }

    //***********************************************************************************************//
    // * Function     :  Survey Performance Reports
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function surveyperformance() {
        $surveyData = $this->getSurveyPerformanceReports();
        $this->set('salesUser', $surveyData);
    }

    public function exportsurveyperformancereport() {
        $surveyData = $this->getSurveyPerformanceReports();
        $this->set('salesUser', $surveyData);
    }

    public function getSurveyPerformanceReports() {
        $this->loadModel('Surveys');
        $this->loadModel('Users');
        $salesUser = [];
        $salesUser = $this->Users->find('all')->select(['id', 'name'])->where(['user_type' => 'SALES'])->hydrate(false)->toArray();
        if (!empty($salesUser)) {
            foreach ($salesUser as $key => $val) {
                $conditionsDaily = $conditionsWeekly = $conditionsMonthly = $conditionsTotal = [];
                $conditionsDaily = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m-%d')" => date('Y-m-d')];
                $monday = strtotime("last monday");
                $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
                $saturday = strtotime(date("Y-m-d", $monday) . " +5 days");
                $this_week_sd = date("Y-m-d", $monday);
                $this_week_ed = date("Y-m-d", $saturday);
                $conditionsWeekly = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m-%d') >=" => $this_week_sd, "DATE_FORMAT(created,'%Y-%m-%d') <=" => $this_week_ed];
                $monthpattern = date('Y-m');
                $conditionsMonthly = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m')" => $monthpattern];
                $conditionsTotal = ['created_by' => $val['id']];
                $dailyCount = $this->Surveys->find('all')->where($conditionsDaily)->hydrate(false)->count();
                $weeklyCount = $this->Surveys->find('all')->where($conditionsWeekly)->hydrate(false)->count();
                $monthlyCount = $this->Surveys->find('all')->where($conditionsMonthly)->hydrate(false)->count();
                $totalCount = $this->Surveys->find('all')->where($conditionsTotal)->hydrate(false)->count();
                $salesUser[$key]['daily'] = $dailyCount;
                $salesUser[$key]['weekly'] = $weeklyCount;
                $salesUser[$key]['monthly'] = $monthlyCount;
                $salesUser[$key]['total'] = $totalCount;
            }
        }
        return $salesUser;
    }

    //***********************************************************************************************//
    // * Function     :  Survey Reports
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function surveys() {
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
            $this->request->session()->write('serveysFilter', $condArr); // WRITE SESSION FOR SEARCHED DATA
            //pr($condArr); exit;
        } else {
            $this->request->session()->write('serveysFilter', []); // WRITE SESSION FOR SEARCHED DATA
        }
        $serveys = $this->Surveys->find('all')->where($condArr)->order(['id' => 'DESC'])->hydrate(false)->toArray();
        foreach ($serveys as $key => $val) {
            $serveys[$key]['survey_by'] = $this->getUserName($val['created_by']);
        }
        $this->set('serveys', $serveys);
    }

    public function getSalesList() {
        $this->loadModel('Users');
        return $this->Users->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->where(['user_type' => 'SALES'])->hydrate(false)->toArray();
    }

    public function exportsurveyreports() {
        $this->loadModel('Surveys');
        $searchdatasurvey = $this->request->session()->read('serveysFilter');
        $condArr = [];
        if (isset($searchdatasurvey) && !empty($searchdatasurvey)) {
            $condArr = $searchdatasurvey;
        }
        $serveys = $this->Surveys->find('all')->where($condArr)->order(['id' => 'DESC'])->hydrate(false)->toArray();
        foreach ($serveys as $key => $val) {
            $serveys[$key]['survey_by'] = $this->getUserName($val['created_by']);
        }
        $this->set('serveys', $serveys);
    }

    public function resetsurveyfilters() {
        $this->request->session()->delete('serveysFilter');
        return $this->redirect(array('action' => 'surveys'));
    }

//    public function samplechart() {
//        //echo 1; exit;
//    }

    public function memberships() {
        $this->loadModel('Users');
        $this->set('salesLists', $this->getSalesList());
        $this->set('planLists', $this->getPlans());
        $members = [];
        $filters = $this->Users->newEntity();
        $this->set('filters', $filters);
        $condArr = [];
        if ($this->request->is('post')) {
            if (isset($this->request->data['sales_id']) && $this->request->data['sales_id'] != 0) {
                $condArr['created_by'] = $this->request->data['sales_id'];
            }
            if (isset($this->request->data['contact_number']) && $this->request->data['contact_number'] != 0) {
                $condArr['phone_no'] = $this->request->data['contact_number'];
            }
            if (isset($this->request->data['from_date']) && $this->request->data['from_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
            }
            if (isset($this->request->data['to_date']) && $this->request->data['to_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
            }
            if (isset($this->request->data['plan_id']) && $this->request->data['plan_id'] != '') {
                $condArr['plan_id'] = $this->request->data['plan_id'];
            }
            $condArr['user_type'] = 'MEMBERSHIP';
            $filter = $this->request->data;
            $this->set('filter', $filter);
            $this->request->session()->write('membersFilter', $condArr); // WRITE SESSION FOR SEARCHED DATA
        } else {
            $condArr['user_type'] = 'MEMBERSHIP';
            $this->request->session()->write('membersFilter', $condArr); // WRITE SESSION FOR SEARCHED DATA
        }
        $usersTable = TableRegistry::get('Users');
        $userDetailsTable = TableRegistry::get('UserDetails');
        $plansTable = TableRegistry::get('Plans');
        $users = $usersTable->find('all')->select(['id', 'membership_id', 'name', 'phone_no', 'email', 'address', 'city', 'active', 'plan_id', 'created_by', 'created'])->where($condArr)->order(['id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($users as $key => $val) {
            $planName = $this->getPlanName($val['plan_id']);
            $users[$key]['plan_name'] = $planName;
            $users[$key]['sales_by'] = $this->getUserName($val['created_by']);
            $userDetails = $userDetailsTable->find('all')->select(['payment_type', 'bank_name', 'cheque_no', 'cheque_date', 'transcation_id', 'other_details'])->where(['user_id' => $val['id']])->hydrate(false)->first();
            if (!empty($userDetails)) {
                foreach ($userDetails as $k => $v) {
                    $users[$key][$k] = $v;
                }
            }
        }
        $this->set('members', $users);
    }

    public function getPlanName($plan_id) {
        $plansTable = TableRegistry::get('Plans');
        $planDetails = $plansTable->find()->select(['name'])->where(['id' => $plan_id])->hydrate(false)->first();
        return (isset($planDetails['name']) && $planDetails['name'] != '') ? $planDetails['name'] : '-';
    }

    public function getPlans() {
        $this->loadModel('Plans');
        return $this->Plans->find('list', [ 'keyField' => 'id', 'valueField' => 'name'])->hydrate(false)->toArray();
    }

    public function resetmembershipfilters() {
        $this->request->session()->delete('membersFilter');
        return $this->redirect(array('action' => 'memberships'));
    }

    public function exportmembershipreports() {
        $usersTable = TableRegistry::get('Users');
        $userDetailsTable = TableRegistry::get('UserDetails');
        $searchdatasurvey = $this->request->session()->read('serveysFilter');
        $condArr = [];
        if (isset($searchdatasurvey) && !empty($searchdatasurvey)) {
            $condArr = $searchdatasurvey;
        } else {
            $condArr = ['user_type' => 'MEMBERSHIP'];
        }
        $users = $usersTable->find('all')->select(['id', 'membership_id', 'name', 'phone_no', 'email', 'address', 'city', 'active', 'plan_id', 'created_by', 'created'])->where($condArr)->order(['id' => 'ASC'])->hydrate(false)->toArray();
        foreach ($users as $key => $val) {
            $planName = $this->getPlanName($val['plan_id']);
            $users[$key]['plan_name'] = $planName;
            $users[$key]['sales_by'] = $this->getUserName($val['created_by']);
            $users[$key]['created'] = $val['created']->format('d-m-Y h:i A');
            $userDetails = $userDetailsTable->find('all')->select(['payment_type', 'bank_name', 'cheque_no', 'cheque_date', 'transcation_id', 'other_details'])->where(['user_id' => $val['id']])->hydrate(false)->first();
            if (!empty($userDetails)) {
                foreach ($userDetails as $k => $v) {
                    $users[$key][$k] = $v;
                }
            }
        }
        $this->set('members', $users);
    }

    public function salesreports() {
        $memberData = $this->getMemberPerformanceReports();
        $this->set('memberData', $memberData);
    }
    
    public function exportsalesperformancereports() {
        $memberData = $this->getMemberPerformanceReports();
        $this->set('memberData', $memberData);
    }

    public function getMemberPerformanceReports() {
        $this->loadModel('Users');
        $salesUser = [];
        $salesUser = $this->Users->find('all')->select(['id', 'name'])->where(['user_type' => 'SALES'])->hydrate(false)->toArray();
        //pr($salesUser); exit;
        if (!empty($salesUser)) {
            foreach ($salesUser as $key => $val) {
                $conditionsDaily = $conditionsWeekly = $conditionsMonthly = $conditionsTotal = [];
                $conditionsDaily = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m-%d')" => date('Y-m-d')];
                $monday = strtotime("last monday");
                $monday = date('w', $monday) == date('w') ? $monday + 7 * 86400 : $monday;
                $saturday = strtotime(date("Y-m-d", $monday) . " +5 days");
                $this_week_sd = date("Y-m-d", $monday);
                $this_week_ed = date("Y-m-d", $saturday);
                $conditionsWeekly = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m-%d') >=" => $this_week_sd, "DATE_FORMAT(created,'%Y-%m-%d') <=" => $this_week_ed];
                $monthpattern = date('Y-m');
                $conditionsMonthly = ['created_by' => $val['id'], "DATE_FORMAT(created,'%Y-%m')" => $monthpattern];
                $conditionsTotal = ['created_by' => $val['id']];
                $dailyCount = $this->Users->find('all')->where($conditionsDaily)->hydrate(false)->count();
                $weeklyCount = $this->Users->find('all')->where($conditionsWeekly)->hydrate(false)->count();
                $monthlyCount = $this->Users->find('all')->where($conditionsMonthly)->hydrate(false)->count();
                $totalCount = $this->Users->find('all')->where($conditionsTotal)->hydrate(false)->count();
                $salesUser[$key]['daily'] = $dailyCount;
                $salesUser[$key]['weekly'] = $weeklyCount;
                $salesUser[$key]['monthly'] = $monthlyCount;
                $salesUser[$key]['total'] = $totalCount;
            }
        }
        //pr($salesUser); exit;
        return $salesUser;
    }

}
