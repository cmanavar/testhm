<?php

namespace App\Model\Table;

use Cake\ORM\Rule\IsUnique;
use Cake\ORM\Table;
use App\Model\Entity\Payment;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;

class QuestionsTable extends Table {

    public function initialize(array $config) {
        
    }

    public function beforeSave($event, $entity, $options = array()) {
        
    }

    //*******************************************************************************//
    // * Function          :  getQuestions
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getQuestions($id) {
        $serviceQuestionsTable = TableRegistry::get('ServiceQuestions');
        $categories = $serviceQuestionsTable->find()->select(['question_title'])->where(['id' => $id])->hydrate(false)->first();
        return $categories['question_title'];
    }

    //*******************************************************************************//
    // * Function          :  getAnswers
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR LISTING
    // * Author            :  Chirag Manavar
    // * Date              :  24-October-2017
    //******************************************************************************//
    public function getAnswers($id) {
        $serviceQuestionAnswersTable = TableRegistry::get('ServiceQuestionAnswers');
        $categories = $serviceQuestionAnswersTable->find()->select(['label'])->where(['id' => $id])->hydrate(false)->first();
        return $categories['label'];
    }

    //*******************************************************************************//
    // * Function          :  getCategoryName
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function getCategoryName($category_id) {
        $serviceCategoryTable = TableRegistry::get('ServiceCategory');
        $categories = $serviceCategoryTable->find()->select(['name'])->where(['id' => $category_id])->hydrate(false)->first();
        return (isset($categories['name']) && $categories['name'] != '') ? $categories['name'] : '-';
    }

    //*******************************************************************************//
    // * Function          :  getServiceName
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function getServiceName($service_id) {
        
        $serviceTable = TableRegistry::get('Services');
        $categories = $serviceTable->find()->select(['service_name'])->where(['id' => $service_id])->hydrate(false)->first();
        return (isset($categories['service_name']) && $categories['service_name'] != '') ? $categories['service_name'] : '-';
    }

    //*******************************************************************************//
    // * Function          :  getAnswersByQuesId
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function getAnswersByQuesId($questions_id) {
        $serviceQuestionAnswersTable = TableRegistry::get('ServiceQuestionAnswers');
        $answerArr = $serviceQuestionAnswersTable->find('all')->select(['id', 'label', 'quantity', 'price'])->where(['question_id' => $questions_id])->hydrate(false)->toArray();
        return $answerArr;
    }

    //*******************************************************************************//
    // * Function          :  getCategoryId
    // * Parameter         :  
    // * Controller Action :  @ For Index action @
    // * Description       :  FUNCTION FOR CHECK CATEGORIES HAS SERVICES OR NOT
    // * Author            :  Chirag Manavar
    // * Date              :  26-October-2017
    //******************************************************************************//
    public function getCategoryId($service_id) {
        $serviceTable = TableRegistry::get('Services');
        $categories = $serviceTable->find()->select(['category_id'])->where(['id' => $service_id])->hydrate(false)->first();
        return $categories['category_id'];
    }

}

?>