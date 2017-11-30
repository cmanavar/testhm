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

class QuestionsController extends AppController {

    public function beforeFilter(Event $event) {
        $this->Auth->allow(['updateanswer', 'addnewanswer']);
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
        $this->loadModel('ServiceQuestions');
        $questions = $this->ServiceQuestions->find()->where(['service_id' => $service_id])->hydrate(false)->toArray(); //LISTING SERVICES
        if (!empty($questions)) {
            foreach ($questions as $key => $val) {
                $questions[$key]['parent_questions'] = $this->Questions->getQuestions($val['parent_question_id']);
                $questions[$key]['parent_answers'] = $this->Questions->getAnswers($val['parent_answer_id']);
                if ($val['answer_type'] == 'rb') {
                    $questions[$key]['answer_type'] = 'Radio Button';
                }
                if ($val['answer_type'] == 't') {
                    $questions[$key]['answer_type'] = 'Text Box';
                }
            }
        }
        // pr($questions); exit;
        $this->set('questions', $questions);
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
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        $question = $this->ServiceQuestions->newEntity();
        $parent_questions = $this->ServiceQuestions->find('list', ['keyField' => 'id', 'valueField' => 'question_title'])->where(['questions_type' => 'parent', 'service_id' => $service_id])->toArray();
        if ($this->request->is('post')) {
            $serviceQuestions = $this->ServiceQuestions->newEntity();
            $sQ = $sA = [];
            $category_id = $this->Questions->getCategoryId($service_id);
//            pr($this->request->data); exit;
            $sQ['category_id'] = $category_id;
            $sQ['service_id'] = $service_id;
            $sQ['questions_type'] = $this->request->data['questions_type'];
            $sQ['parent_question_id'] = $this->request->data['parent_questions'];
            $sQ['parent_answer_id'] = $this->request->data['parent_questions_answer'];
            $sQ['question_title'] = $this->request->data['question_title'];
            $sQ['answer_type'] = $this->request->data['answer_type'];
            $sQ['created_by'] = $this->request->session()->read('Auth.User.id');
            $serviceQuestions = $this->ServiceQuestions->patchEntity($serviceQuestions, $sQ);
            $serviceQuestions->created = date("Y-m-d H:i:s");
            $rslt = $this->ServiceQuestions->save($serviceQuestions);
            if ($rslt->id) {
                if (isset($this->request->data['answers']) && !empty($this->request->data['answers'])) {
                    foreach ($this->request->data['answers'] as $key => $val) {
                        $serviceQuestionAnswers = $this->ServiceQuestionAnswers->newEntity();
                        $sA['question_id'] = $rslt->id;
                        $sA['label'] = $val['label'];
                        $sA['quantity'] = $val['quantity'];
                        $sA['price'] = $val['price'];
                        $sA['created_by'] = $this->request->session()->read('Auth.User.id');
                        $serviceQuestionAnswers = $this->ServiceQuestionAnswers->patchEntity($serviceQuestionAnswers, $sA);
                        $serviceQuestionAnswers->created = date("Y-m-d H:i:s");
                        if ($this->ServiceQuestionAnswers->save($serviceQuestionAnswers)) {
                            
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
        $this->set('question', $question);
        $this->set('service_id', $service_id);
        $this->set('parent_questions', $parent_questions);
    }

    //***********************************************************************************************//
    // * Function     :  edit
    // * Parameter    :  
    // * Description  :  This function used to edit Services Categories data
    // * Author       :  Chirag Manavar
    // * Date         :  24-October-2017
    //***********************************************************************************************//

    public function edit($service_id, $id) {
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestions');
        $questions = $this->ServiceQuestions->find()->where(['id' => $id, 'service_id' => $service_id])->hydrate(false)->first(); //LISTING SERVICES
        if (isset($questions) && !empty($questions)) {
            $question = $this->ServiceQuestions->get($id); //LISTING USERDATA
            $question['answers'] = $this->Questions->getAnswersByQuesId($question['id']);
            if ($this->request->is(['patch', 'post', 'put'])) {
                //pr($this->request->data()); exit;
                $question = $this->ServiceQuestions->patchEntity($question, $this->request->data());
                $question->modified_by = $this->request->session()->read('Auth.User.id');
                $question->modified = date("Y-m-d H:i:s");
                if ($this->ServiceQuestions->save($question)) {
                    $this->Flash->success(Configure::read('Settings.SAVE'));
                    return $this->redirect(['action' => 'index', $service_id]);
                } else {
                    $this->Flash->error(Configure::read('Settings.FAIL'));
                }
            }
            $question['parent_questions'] = $this->Questions->getQuestions($question['parent_question_id']);
            $question['parent_answers'] = $this->Questions->getAnswers($question['parent_answer_id']);
            $this->set('question', $question);
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
        $question = [];
        $this->loadModel('ServiceQuestions');
        $question = $this->ServiceQuestions->find()->where(['id' => $id])->hydrate(false)->first(); //LISTING SERVICES
        if (!empty($question)) {
            $question['parent_questions'] = $this->Questions->getQuestions($question['parent_question_id']);
            $question['parent_answers'] = $this->Questions->getAnswers($question['parent_answer_id']);
            if ($question['answer_type'] == 'rb') {
                $question['answer_type'] = 'Radio Button';
            }
            if ($question['answer_type'] == 't') {
                $question['answer_type'] = 'Text Box';
            }
            $question['category_name'] = $this->Questions->getCategoryName($question['category_id']);
            $question['service_name'] = $this->Questions->getServiceName($question['service_id']);
            $question['answers'] = $this->Questions->getAnswersByQuesId($question['id']);
        }
//         pr($question); exit;
        $this->set('service_id', $service_id);
        $this->set('question', $question);
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
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        if (isset($id) && $id != '') {
            $questions = $this->ServiceQuestions->find()->where(['id' => $id, 'service_id' => $service_id])->hydrate(false)->first(); //LISTING SERVICES
            if (isset($questions) && !empty($questions)) {
                $question = $this->ServiceQuestions->get($id); //LISTING USERDATA
                if ($this->ServiceQuestions->delete($question)) {
                    if ($this->ServiceQuestionAnswers->deleteAll(['ServiceQuestionAnswers.question_id' => $id])) {
                        $this->Flash->success(Configure::read('Settings.DELETE'));
                        $this->redirect(array('action' => 'index'));
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
            $this->Flash->error(__('QUESTION ID IS MISSING'));
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

    public function updateanswer($id) {
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        $answer = $this->ServiceQuestionAnswers->get($id);
        if (isset($answer) && !empty($answer)) {
            $questionArr = $this->ServiceQuestions->find('all')->select(['service_id'])->where(['id' => $answer->question_id])->hydrate(false)->first();
            $service_id = $questionArr['service_id'];
            $updatedArr = [];
            $updatedArr['label'] = $_POST['title'];
            $updatedArr['quantity'] = $_POST['quantity'];
            $updatedArr['price'] = $_POST['price'];
            $answer = $this->ServiceQuestionAnswers->patchEntity($answer, $updatedArr);
            $answer->modified_by = $this->request->session()->read('Auth.User.id');
            $answer->modified = date("Y-m-d H:i:s");
            if ($this->ServiceQuestionAnswers->save($answer)) {
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

    public function addnewanswer($id) {
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        $question = $this->ServiceQuestions->get($id);
        if (isset($question) && !empty($question)) {
            $answer = $this->ServiceQuestionAnswers->newEntity();
            $service_id = $question->service_id;
            $updatedArr = [];
            $updatedArr['question_id'] = $id;
            $updatedArr['label'] = $_POST['title'];
            $updatedArr['quantity'] = $_POST['quantity'];
            $updatedArr['price'] = $_POST['price'];
            $answer = $this->ServiceQuestionAnswers->patchEntity($answer, $updatedArr);
            $answer->created_by = $this->request->session()->read('Auth.User.id');
            $answer->created = date("Y-m-d H:i:s");
            if ($this->ServiceQuestionAnswers->save($answer)) {
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

    public function deleteanswer($id) {
        $this->loadModel('ServiceQuestions');
        $this->loadModel('ServiceQuestionAnswers');
        $question = $this->ServiceQuestions->get($id);
        if (isset($question) && !empty($question)) {
            $service_id = $question->service_id;
            if (isset($_POST['value']) && $_POST['value'] != '') {
                $ans_id = $_POST['value'];
                $answer = $this->ServiceQuestionAnswers->get($ans_id);
                if ($this->ServiceQuestionAnswers->delete($answer)) {
                    $this->Flash->error('Answer delete successfully!');
                    return $this->redirect(['action' => 'index', $service_id, $id]);
                } else {
                    $this->Flash->error('Sorry, Something wrong!');
                    return $this->redirect(['action' => 'index', $service_id, $id]);
                }
            } else {
                $this->Flash->error('Answer id is missing!');
                return $this->redirect(['action' => 'index', $service_id, $id]);
            }
        } else {
            $this->Flash->error('Question not found');
            return $this->redirect(['action' => 'index', $service_id, $id]);
        }
        echo json_encode($this->data);
        exit;
    }

}
