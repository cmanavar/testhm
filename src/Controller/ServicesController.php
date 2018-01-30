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

class ServicesController extends AppController {

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
        $services = [];
        $services = $this->Services->getserviceslisting()->toArray(); //LISTING SERVICES
        if (!empty($services)) {
            foreach ($services as $key => $service) {
                $services[$key]['category_name'] = $this->Services->getCategoryName($service['category_id']);
            }
        }
        $this->set('services', $services);
    }

    //***********************************************************************************************//
    // * Function     :  add
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function add() {
        $this->loadModel('ServiceCategory');
        $serviceCategories = $this->ServiceCategory->find('list')->toArray();
        $this->set('serviceCategories', $serviceCategories);
        $service = $this->Services->newEntity();
        if ($this->request->is('post')) {
            $validator = new ServicesValidator();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $service = $this->Services->patchEntity($service, $this->request->data);
                if (isset($this->request->data['icon_1']['name']) && $this->request->data['icon_1']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_1']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_1']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_1'] = $filename;
                }
                if (isset($this->request->data['icon_2']['name']) && $this->request->data['icon_2']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_2']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_2']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_2'] = $filename;
                }
                if (isset($this->request->data['icon_3']['name']) && $this->request->data['icon_3']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_3']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_3']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_3'] = $filename;
                }
                if (isset($this->request->data['icon_4']['name']) && $this->request->data['icon_4']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_4']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_4']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_4'] = $filename;
                }
                if (isset($this->request->data['icon_5']['name']) && $this->request->data['icon_5']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_5']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_5']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_5'] = $filename;
                }
                if (isset($this->request->data['icon_6']['name']) && $this->request->data['icon_6']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_6']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_6']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_6'] = $filename;
                }
                if (isset($this->request->data['banner']['name']) && $this->request->data['banner']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['banner']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['banner']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['banner_image'] = $filename;
                }
                if (isset($this->request->data['square']['name']) && $this->request->data['square']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['square']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['square']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['square_image'] = $filename;
                }
                $service->created = date("Y-m-d H:i:s");
                $service->created_by = $this->request->session()->read('Auth.User.id');
                if ($this->Services->save($service)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $this->set('service', $service);
    }

    //***********************************************************************************************//
    // * Function     :  edit
    // * Parameter    :  
    // * Description  :  This function used to edit Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function edit($id) {
        //CHECK IF THE RECORD EXISTS OR NOT 
        $query = $this->Services->getservicevalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $this->loadModel('ServiceCategory');
        $serviceCategories = $this->ServiceCategory->find('list')->toArray();
        $this->set('serviceCategories', $serviceCategories);
        $service = $this->Services->getservicesId($id); //LISTING CATEGORYDATA
        $service['service_description'] = html_entity_decode($service['service_description']);
        //pr($service); exit;
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new ServicesValidator();
            $errors = $validator->errors($this->request->data());
            if (empty($errors)) {
                $this->request->data['service_description'] = htmlentities($this->request->data['service_description']);
                $service = $this->Services->patchEntity($service, $this->request->data);
                //pr($this->request->data); exit;
                if (isset($this->request->data['icon_1']['name']) && $this->request->data['icon_1']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_1']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_1']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_1'] = $filename;
                }
                if (isset($this->request->data['icon_2']['name']) && $this->request->data['icon_2']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_2']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_2']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_2'] = $filename;
                }
                if (isset($this->request->data['icon_3']['name']) && $this->request->data['icon_3']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_3']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_3']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_3'] = $filename;
                }
                if (isset($this->request->data['icon_4']['name']) && $this->request->data['icon_4']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_4']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_4']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_4'] = $filename;
                }
                if (isset($this->request->data['icon_5']['name']) && $this->request->data['icon_5']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_5']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_5']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_5'] = $filename;
                }
                if (isset($this->request->data['icon_6']['name']) && $this->request->data['icon_6']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['icon_6']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_ICON_PATH)) {
                        mkdir('img/' . SERVICE_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon_6']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_ICON_PATH . $filename);
                    $service['icon_6'] = $filename;
                }
                if (isset($this->request->data['banner']['name']) && $this->request->data['banner']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['banner']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_BANNER_PATH)) {
                        mkdir('img/' . SERVICE_BANNER_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['banner']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_BANNER_PATH . $filename);
                    $service['banner_image'] = $filename;
                }
                if (isset($this->request->data['square']['name']) && $this->request->data['square']['name'] != '') {
                    $file = $filename = '';
                    $file = $this->request->data['square']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_SQUARE_BANNER_PATH)) {
                        mkdir('img/' . SERVICE_SQUARE_BANNER_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['square']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_SQUARE_BANNER_PATH . $filename);
                    $service['square_image'] = $filename;
                }
                $service->modified = date("Y-m-d H:i:s");
                $service->modified_by = $this->request->session()->read('Auth.User.id');
                if ($this->Services->save($service)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            }
        }
        $this->set('service', $service);
    }

    //***********************************************************************************************//
    // * Function     :  deleteimage
    // * Parameter    :  
    // * Description  :  This function used to deleteimage of Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function deleteimage($fields, $photo = NULL) {
        $service = $this->Services->find('all')->where([$fields => $photo])->hydrate(false)->first();
        $service_id = $service['id'];
        $service = $this->Services->get($service_id);
        if ($fields == 'banner_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_BANNER_PATH . $photo;
        }
        if ($fields == 'square_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_SQUARE_BANNER_PATH . $photo;
        }
        if (file_exists($fpath)) {
            unlink($fpath);
        }
        $service->$fields = "";
        if ($this->Services->save($service)) {
            $this->Flash->success(Configure::read('Settings.DELETE'));
            return $this->redirect(['action' => 'edit', $service_id]);
        } else {
            $this->Flash->success(Configure::read('Settings.DELETEFAIL'));
            return $this->redirect(['action' => 'edit', $service_id]);
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
        $category = [];
        //CHECK IF THE RECORD EXISTS OR NOT 
        $query = $this->Services->getservicevalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $services = $this->Services->getservicesId($id)->toArray(); //LISTING CATEGORYDATA
        if (empty($services)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $services['category_name'] = $this->Services->getCategoryName($services['category_id']);
        $this->set('services', $services);
    }

    //***********************************************************************************************//
    // * Function     :  delete
    // * Parameter    :  
    // * Description  :  This function used to get Services details
    // * Author       :  Chirag Manavar
    // * Date         :  26-October-2017
    //***********************************************************************************************//
    public function delete() {
        $id = $this->request->data['value'];
        if (isset($id) && $id != '') {
            $hasOrders = $this->Services->hasOrders($id);
            if ($hasOrders) {
                $service_data = $this->Services->getservicesId($id); //LISTING SERVICES
                // Delete Banner
                if (isset($service_data->banner_image) && $service_data->banner_image != '') {
                    $fbpath = WWW_ROOT . 'img/' . SERVICE_BANNER_PATH . $service_data->banner_image;
                    if (file_exists($fbpath)) {
                        unlink($fbpath);
                    }
                }
                // Delete Icon
                if (isset($service_data->square_image) && $service_data->square_image != '') {
                    $fspath = WWW_ROOT . 'img/' . SERVICE_SQUARE_BANNER_PATH . $service_data->square_image;
                    if (file_exists($fspath)) {
                        unlink($fspath);
                    }
                }
                if ($this->Services->delete($service_data)) {
                    $this->Flash->success(Configure::read('Settings.DELETE'));
                    $this->redirect(array('action' => 'index'));
                    exit;
                } else {
                    $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                    $this->redirect(array('action' => 'index'));
                    exit;
                }
            } else {
                $this->Flash->error(__("YOU CAN'T DELETE THIS SERVICES BECAUSE IT HAVE ORDERS IN PAST!"));
                $this->redirect(array('action' => 'index'));
                exit;
            }
            //$user = $this->ServiceCategory->getuserId($this->request->data['value']); // GET USER DATA FROM ID
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            $this->redirect(array('action' => 'index'));
            exit;
        }
    }

    public function ratecard($service_id) {
        $this->loadModel('ServiceRatecards');
        $ratecard = $this->ServiceRatecards->newEntity();

        $this->set('ratecard', $ratecard);
        $this->set('service_id', $service_id);
    }

}
