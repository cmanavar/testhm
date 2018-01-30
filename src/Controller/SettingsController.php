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

class SettingsController extends AppController {

    public function beforeFilter(Event $event) {
        if (in_array($this->request->session()->read('Auth.User.user_type'), ['ADMIN', 'OPERATION_MANAGER', 'TELLY_CALLER'])) {
            AppController::checkNormalAccess();
        }
    }

    public function banner() {
        $this->loadModel('Banners');
        $banner = $this->Banners->newEntity();
        $banners = [];
        $banners = $this->Banners->find('all')->hydrate(false)->toArray(); //LISTING SERVICES
        if ($this->request->is('post')) {
            $banner = $this->Banners->patchEntity($banner, $this->request->data);
            if (isset($this->request->data['banner_images']['name']) && $this->request->data['banner_images']['name'] != '') {
                $file = $this->request->data['banner_images']['name'];
                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                move_uploaded_file($this->request->data['banner_images']['tmp_name'], WWW_ROOT . 'img/' . BANNER_IMAGE_PATH . $filename);
                $banner['banner_images'] = $filename;
            }
            $banner->created_at = date("Y-m-d H:i:s");
            $banner->modified_at = date("Y-m-d H:i:s");
            $banner->created_by = $this->request->session()->read('Auth.User.id');
            $banner->modified_by = $this->request->session()->read('Auth.User.id');  
            if ($this->Banners->save($banner)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'banner']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('banners', $banners);
        $this->set('banner', $banner);
    }

    function banneredit($id) {
        $this->loadModel('Banners');
        $banners = $this->Banners->find('all')->hydrate(false)->toArray(); //LISTING BANNER
        $banner = $this->Banners->get($id); //LISTING USERDATA
        if (empty($banner)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'banner']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $banner = $this->Banners->patchEntity($banner, $this->request->data);
            if (isset($this->request->data['banner_images']['name']) && $this->request->data['banner_images']['name'] != '') {
                $file = $this->request->data['banner_images']['name'];
                $filename = pathinfo($file, PATHINFO_FILENAME); //find file name
                $ext = pathinfo($file, PATHINFO_EXTENSION); //find extension						
                $filename = date('YmdHis') . substr(uniqid(), 0, 5) . "." . $ext;
                move_uploaded_file($this->request->data['banner_images']['tmp_name'], WWW_ROOT . 'img/' . BANNER_IMAGE_PATH . $filename);
                $banner['banner_images'] = $filename;
            }
            $banner->modified = date("Y-m-d H:i:s");
            $banner->modified_by = $this->request->session()->read('Auth.User.id');
            if ($this->Banners->save($banner)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'banner']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('banner', $banner);
        $this->set('banners', $banners);
    }

    //***********************************************************************************************//
    // * Function     :  deleteimage
    // * Parameter    :  
    // * Description  :  This function used to deleteimage of Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function bannerdeleteimage($fields, $photo = NULL) {
        $this->loadModel('Banners');
        $banner = $this->Banners->find('all')->where([$fields => $photo])->hydrate(false)->first();
        $banner_id = $banner['id'];
        $banner = $this->Banners->get($banner_id);

        if ($fields == 'banner_images') {
            $fpath = WWW_ROOT . 'img/' . BANNER_IMAGE_PATH . $photo;
        }
        if (file_exists($fpath)) {
            unlink($fpath);
        }
        $banner->$fields = "";
        $banner->modified = date("Y-m-d H:i:s");
        $banner->modified_by = $this->request->session()->read('Auth.User.id');
        if ($this->Banners->save($banner)) {
            $this->Flash->success(Configure::read('Settings.DELETE'));
            return $this->redirect(['action' => 'banneredit', $banner_id]);
        } else {
            $this->Flash->success(Configure::read('Settings.DELETEFAIL'));
            return $this->redirect(['action' => 'banneredit', $banner_id]);
        }
    }

    public function bannerdelete() {
        $this->loadModel('Banners');
        $id = $this->request->data['value'];
        if (isset($id) && $id != '') {
            $banner_data = $this->Banners->get($id); //LISTING CATEGORY
            if (empty($banner_data)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'banner']);
            }
            // Delete Banner
            if (isset($banner_data->banner_images) && $banner_data->banner_images != '') {
                $fipath = WWW_ROOT . 'img/' . BANNER_IMAGE_PATH . $banner_data->banner_images;
                if (file_exists($fipath)) {
                    unlink($fipath);
                }
            }
            if ($this->Banners->delete($banner_data)) {
                $this->Flash->success(Configure::read('Settings.DELETE'));
                $this->redirect(array('action' => 'banner'));
                exit;
            } else {
                $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                $this->redirect(array('action' => 'banner'));
                exit;
            }
            //$user = $this->ServiceCategory->getuserId($this->request->data['value']); // GET USER DATA FROM ID
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            $this->redirect(array('action' => 'banner'));
            exit;
        }
    }

    public function faq() {
        $this->loadModel('Faqs');
        $faq = $this->Faqs->newEntity();
        $faqs = [];
        $faqs = $this->Faqs->find('all')->hydrate(false)->toArray(); //LISTING SERVICES
        if ($this->request->is('post')) {
            $faq = $this->Faqs->patchEntity($faq, $this->request->data);
            $faq->created_at = date("Y-m-d H:i:s");
            $faq->modified_at = date("Y-m-d H:i:s");
            $faq->created_by = $this->request->session()->read('Auth.User.id');
            $faq->modified_by = $this->request->session()->read('Auth.User.id');
            if ($this->Faqs->save($faq)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'faq']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('faqs', $faqs);
        $this->set('faq', $faq);
    }

    function faqedit($id) {
        $this->loadModel('Faqs');
        $faqs = $this->Faqs->find('all')->hydrate(false)->toArray(); //LISTING BANNER
        $faq = $this->Faqs->get($id); //LISTING USERDATA
        if (empty($faq)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'faq']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $faq = $this->Faqs->patchEntity($faq, $this->request->data);
            $faq->modified = date("Y-m-d H:i:s");
            $faq->modified_by = $this->request->session()->read('Auth.User.id');
            if ($this->Faqs->save($faq)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'faq']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('faq', $faq);
        $this->set('faqs', $faqs);
    }

    public function faqdelete() {
        $this->loadModel('Faqs');
        $id = $this->request->data['value'];
        if (isset($id) && $id != '') {
            $faq = $this->Faqs->get($id); //LISTING CATEGORY
            if (empty($faq)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'faq']);
            }
            if ($this->Faqs->delete($faq)) {
                $this->Flash->success(Configure::read('Settings.DELETE'));
                $this->redirect(array('action' => 'faq'));
                exit;
            } else {
                $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                $this->redirect(array('action' => 'faq'));
                exit;
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            $this->redirect(array('action' => 'faq'));
            exit;
        }
    }
    
    public function coupon() {
        $this->loadModel('Coupons');
        $coupon = $this->Coupons->newEntity();
        $coupons = [];
        $coupons = $this->Coupons->find('all')->hydrate(false)->toArray(); //LISTING SERVICES
        if ($this->request->is('post')) {
            $valid_to = date('Y-m-d', strtotime($this->request->data['valid_to']));
            $valid_from = date('Y-m-d', strtotime($this->request->data['valid_from']));
            unset($this->request->data['valid_to']);
            unset($this->request->data['valid_from']);
            $coupon = $this->Coupons->patchEntity($coupon, $this->request->data);
            $coupon->valid_to = $valid_to;
            $coupon->valid_from = $valid_from;
            $coupon->created = date("Y-m-d H:i:s");
            $coupon->created_by = $this->request->session()->read('Auth.User.id');
            if ($this->Coupons->save($coupon)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'coupon']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('coupons', $coupons);
        $this->set('coupon', $coupon);
    }

    function couponedit($id) {
        $this->loadModel('Coupons');
        $coupons = $this->Coupons->find('all')->hydrate(false)->toArray(); //LISTING SERVICES
        $coupon = $this->Coupons->get($id); //LISTING USERDATA
        if (empty($coupon)) {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            return $this->redirect(['action' => 'coupon']);
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $valid_to = date('Y-m-d', strtotime($this->request->data['valid_to']));
            $valid_from = date('Y-m-d', strtotime($this->request->data['valid_from']));
            unset($this->request->data['valid_to']);
            unset($this->request->data['valid_from']);
            $coupon = $this->Coupons->patchEntity($coupon, $this->request->data);
            $coupon->valid_to = $valid_to;
            $coupon->valid_from = $valid_from;
            $coupon->modified = date("Y-m-d H:i:s");
            $coupon->modified_by = $this->request->session()->read('Auth.User.id');
            if ($this->Coupons->save($coupon)) {
                $this->Flash->success(Configure::read('Settings.SAVE'));
                return $this->redirect(['action' => 'coupon']);
            } else {
                $this->Flash->error(Configure::read('Settings.FAIL'));
            }
        }
        $this->set('coupon', $coupon);
        $this->set('coupons', $coupons);
    }

    public function coupondelete() {
        $this->loadModel('Coupons');
        $id = $this->request->data['value'];
        if (isset($id) && $id != '') {
            $coupon = $this->Coupons->get($id); //LISTING CATEGORY
            if (empty($coupon)) {
                $this->Flash->error(__('RECORD DOES NOT EXIST'));
                return $this->redirect(['action' => 'coupon']);
            }
            if ($this->Coupons->delete($coupon)) {
                $this->Flash->success(Configure::read('Settings.DELETE'));
                $this->redirect(array('action' => 'coupon'));
                exit;
            } else {
                $this->Flash->error(Configure::read('Settings.DELETEFAIL'));
                $this->redirect(array('action' => 'coupon'));
                exit;
            }
        } else {
            $this->Flash->error(__('RECORD DOES NOT EXIST'));
            $this->redirect(array('action' => 'coupon'));
            exit;
        }
    }

}
