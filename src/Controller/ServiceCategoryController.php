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
use App\Model\Validation\ServiceCategoryValidator;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//
//
//@  File name    : ServicecategoryController.php
//@  Author       : Chirag Manavar
//@  Date         : 24-October-2017
//
//@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@//

class ServiceCategoryController extends AppController {

    public function beforeFilter(Event $event) {
        //$this->Auth->allow(['delete']);
    }

    //***********************************************************************************************//
    // * Function     :  index
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories list
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function index() {
        $categories = [];
        $categories = $this->ServiceCategory->getcategorylisting()->hydrate(false)->toArray(); //LISTING CATEGORY
        foreach ($categories as $key => $val) {
            $categories[$key]['service_count'] = $this->ServiceCategory->servicesCount($val['id']);
        }
        $this->set('categories', $categories);
    }

    //***********************************************************************************************//
    // * Function     :  add
    // * Parameter    :  
    // * Description  :  This function used to add Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function add() {
        $category = $this->ServiceCategory->newEntity();
        if ($this->request->is('post')) {
            $validator = new ServiceCategoryValidator();
            $errors = $validator->errors($this->request->data());
            $order_id = $this->request->data()['order_id'];
            $isExist = $this->ServiceCategory->checkorderidExist($order_id);
            if ($isExist) {
                $errors['order_id']['_unique'] = 'Order id is already assignd';
            } else {
                unset($errors['order_id']['_unique']);
            }
            if (empty($errors)) {
                $category = $this->ServiceCategory->patchEntity($category, $this->request->data);
                if (isset($this->request->data['icon']['name']) && $this->request->data['icon']['name'] != '') {
                    $file = $this->request->data['icon']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH)) {
                        mkdir(SERVICE_CATEGORY_ICON_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['icon']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $filename);
                    $category['icon_image'] = $filename;
                }
                if (isset($this->request->data['banner']['name']) && $this->request->data['banner']['name'] != '') {
                    $file = $this->request->data['banner']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH)) {
                        mkdir(SERVICE_CATEGORY_BANNER_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['banner']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $filename);
                    $category['banner_image'] = $filename;
                }
                if (isset($this->request->data['square']['name']) && $this->request->data['square']['name'] != '') {
                    $file = $this->request->data['square']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    if (!file_exists(WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH)) {
                        mkdir(SERVICE_CATEGORY_SQUARE_BANNER_PATH, 0777, true);
                    }
                    move_uploaded_file($this->request->data['square']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $filename);
                    $category['square_image'] = $filename;
                }
                $category->created = date("Y-m-d H:i:s");
                $category->created_by = $this->request->session()->read('Auth.User.id');
                if ($this->ServiceCategory->save($category)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $this->set('category', $category);
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
        $query = $this->ServiceCategory->getcategoryvalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $category = $this->ServiceCategory->getcategoryId($id); //LISTING CATEGORYDATA
        if ($this->request->is(['patch', 'post', 'put'])) {
            $validator = new ServiceCategoryValidator();
            $errors = $validator->errors($this->request->data());
            $order_id = $this->request->data()['order_id'];
            $isExist = $this->ServiceCategory->find('all')->where(['id !=' => $id, 'order_id' => $order_id])->hydrate(false)->first();
            if ($isExist) {
                $errors['order_id']['_unique'] = 'Order id is already assignd';
            } else {
                unset($errors['order_id']['_unique']);
            }
            if (empty($errors)) {
                $category = $this->ServiceCategory->patchEntity($category, $this->request->data);
                if (isset($this->request->data['icon']['name']) && $this->request->data['icon']['name'] != '') {
                    $file = $this->request->data['icon']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['icon']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $filename);
                    $category['icon_image'] = $filename;
                }
                if (isset($this->request->data['banner']['name']) && $this->request->data['banner']['name'] != '') {
                    $file = $this->request->data['banner']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['banner']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $filename);
                    $category['banner_image'] = $filename;
                }
                if (isset($this->request->data['square']['name']) && $this->request->data['square']['name'] != '') {
                    $file = $this->request->data['square']['name'];
                    $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                    $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                    $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                    move_uploaded_file($this->request->data['square']['tmp_name'], WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $filename);
                    $category['square_image'] = $filename;
                }
                $category->modified = date("Y-m-d H:i:s");
                $category->modified_by = $this->request->session()->read('Auth.User.id');
                if ($this->ServiceCategory->save($category)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index']);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            } else {
                $this->set('errors', $errors);
            }
        }
        $category = $this->ServiceCategory->getcategoryId($id); //LISTING CATEGORYDATA
        $this->set('category', $category);
    }

    //***********************************************************************************************//
    // * Function     :  deleteimage
    // * Parameter    :  
    // * Description  :  This function used to deleteimage of Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function deleteimage($fields, $photo = NULL) {
        $category = $this->ServiceCategory->find('all')->where([$fields => $photo])->hydrate(false)->first();
        $category_id = $category['id'];
        $category = $this->ServiceCategory->get($category_id);

        if ($fields == 'icon_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $photo;
        }
        if ($fields == 'banner_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $photo;
        }
        if ($fields == 'square_image') {
            $fpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $photo;
        }
        if (file_exists($fpath)) {
            unlink($fpath);
        }
        $category->$fields = "";
        if ($this->ServiceCategory->save($category)) {
            $this->Flash->success(Configure::read('Settings.DELETE'));
            return $this->redirect(['action' => 'edit', $category_id]);
        } else {
            $this->Flash->success(Configure::read('Settings.DELETEFAIL'));
            return $this->redirect(['action' => 'edit', $category_id]);
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
        $query = $this->ServiceCategory->getcategoryvalidationID($id); //LISTING USERDATA
        if ($query->isEmpty()) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $category = $this->ServiceCategory->getcategoryId($id)->toArray(); //LISTING CATEGORYDATA
        if (empty($category)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'index']);
        }
        $this->set('category', $category);
    }

    //***********************************************************************************************//
    // * Function     :  delete
    // * Parameter    :  
    // * Description  :  This function used to get Services Categories details
    // * Author       :  Chirag Manavar
    // * Date         :  26-October-2017
    //***********************************************************************************************//
    public function delete() {
        $id = $this->request->data['value'];
        if (isset($id) && $id != '') {
            $hasServices = $this->ServiceCategory->hasServices($id);
            if ($hasServices) {
                $category_data = $this->ServiceCategory->getcategoryId($id); //LISTING CATEGORY
                // Delete Icon
                if (isset($category_data->icon_image) && $category_data->icon_image != '') {
                    $fipath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_ICON_PATH . $category_data->icon_image;
                    if (file_exists($fipath)) {
                        unlink($fipath);
                    }
                }
                // Delete Banner
                if (isset($category_data->banner_image) && $category_data->banner_image != '') {
                    $fbpath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_BANNER_PATH . $category_data->banner_image;
                    if (file_exists($fbpath)) {
                        unlink($fbpath);
                    }
                }
                // Delete Icon
                if (isset($category_data->square_image) && $category_data->square_image != '') {
                    $fspath = WWW_ROOT . 'img/' . SERVICE_CATEGORY_SQUARE_BANNER_PATH . $category_data->square_image;
                    if (file_exists($fspath)) {
                        unlink($fspath);
                    }
                }
                if ($this->ServiceCategory->delete($category_data)) {
                    $this->Flash->success(Configure::read('Settings.DELETE'));
                    $this->redirect(array('action' => 'index'));
                    exit;
                } else {
                    $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                    $this->redirect(array('action' => 'index'));
                    exit;
                }
            } else {
                $this->Flash->error(__("YOU CAN'T DELETE THIS CATEGORY BECAUSE IT HAVE SERVICES!"));
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

}
