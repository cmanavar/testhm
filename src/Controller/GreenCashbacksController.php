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

/**
 * Static content controller
 *
 * This controller will render views from Template/Pages/
 *
 * @link http://book.cakephp.org/3.0/en/controllers/pages-controller.html
 */
class GreenCashbacksController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN'])) {
            AppController::checkNormalAccess();
        }
        //$this->Auth->allow(['delete']);
    }

    /**
     * Displays a view
     *
     * @return void|\Cake\Network\Response
     * @throws \Cake\Network\Exception\NotFoundException When the view file could not
     *   be found or \Cake\View\Exception\MissingTemplateException in debug mode.
     */
    public function index() {
        $this->loadModel('Users');
        $this->loadModel('GreenCashbacks');
        $filters = $this->GreenCashbacks->newEntity();
        $this->set('filters', $filters);
        if ($this->request->is('post')) {
            if (isset($this->request->data['from_date']) && $this->request->data['from_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') >="] = date('Y-m-d', strtotime($this->request->data['from_date']));
            }
            if (isset($this->request->data['to_date']) && $this->request->data['to_date'] != 0) {
                $condArr["DATE_FORMAT(created,'%Y-%m-%d') <="] = date('Y-m-d', strtotime($this->request->data['to_date']));
            }
            $filter = $this->request->data;
            $this->set('filter', $filter);
        }
        $rslt = [];
        $members = $this->Users->find('all')->select(['id', 'name'])->where(['user_type' => 'MEMBERSHIP'])->order(['id' => 'DESC'])->hydrate(false)->toArray();
        if (!empty($members)) {
            foreach ($members as $key => $val) {
                $tmp = [];
                $condArr['user_id'] = $val['id'];
                $tmp['id'] = $val['id'];
                $tmp['name'] = $val['name'];
                $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where($condArr)->hydrate(false)->first();
                $totGreenCash = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                $tmp['totalCash'] = $totGreenCash;
                $rslt[] = $tmp;
            }
        }
        $this->set('greencash', $rslt);
    }

    public function view($id) {
        if (isset($id) && $id != '') {
            $this->loadModel('Users');
            $this->loadModel('GreenCashbacks');
            $rslt = [];
            $users = $this->Users->find('all')->select(['id', 'name'])->where(['id' => $id])->hydrate(false)->first();
            if (isset($users) && !empty($users)) {
                $rslt['id'] = $users['id'];
                $rslt['name'] = $users['name'];
                $greenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where(['user_id' => $id])->hydrate(false)->first();
                $rslt['totalCash'] = (isset($greenCash['tot']) && $greenCash['tot'] != '') ? $greenCash['tot'] : 0;
                $paidgreenCash = $this->GreenCashbacks->find('all')->select(['tot' => 'SUM(GreenCashbacks.amount)'])->where(['user_id' => $id, 'status' => 'CLEAR'])->hydrate(false)->first();
                $rslt['totalpaidCash'] = (isset($paidgreenCash['tot']) && $paidgreenCash['tot'] != '') ? $paidgreenCash['tot'] : 0;
                $this->set('greencash', $rslt);
            } else {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'index']);
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
    }

}
