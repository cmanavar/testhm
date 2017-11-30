<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<?php echo $this->Html->css('jquery-ui.css'); ?>
<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('maskedinput.js', ['block' => 'scriptBottom']); ?>
<?php echo $this->Html->script('custom/upload_image.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('custom/dashboard.js', ['block' => 'scriptBottom']); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                QUESTIONS
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12">
        <div class="row">
            <?php echo ($this->Flash->render()); ?>
            <?php
            // show error messages
            if (!empty($errors)) {
                echo '<div class="cake-error alert alert-danger"> <button data-dismiss="alert" class="close close-sm" type="button">
                                    <i class="fa fa-times"></i>
                                    </button><ul>';
                foreach ($errors as $e) {
                    echo '<li>' . reset($e) . '</li>';
                }
                echo '</ul></div>';
            }
            ?>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> QUESTION
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($question, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">QUESTION TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php if (empty($parent_questions)) { ?>
                                            <?= 'PARENT' ?>
                                            <input type="hidden" name="questions_type" value='parent'>
                                        <?php } else { ?>
                                            <?php echo $this->Form->radio('questions_type', [['value' => 'parent', 'text' => 'PARENT'], ['value' => 'child', 'text' => 'CHILD']], ['value' => 'parent', 'class' => 'questions_type']); ?>
                                        <?php } ?>
                                    </div>  
                                </div>
                            </div>
                            <input type="hidden" id="ajaxUrlforAnswers" value='<?php echo $this->Url->build(["controller" => "Questions", "action" => "getanswers"], true); ?>'>
                            <div class="form-group parents-info" style="display:none;">
                                <label class="col-sm-3 control-label">PARENTS QUESTIONS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('parent_questions', ['label' => false, 'type' => 'select', 'options' => $parent_questions, 'empty' => 'SELECT PARENTS QUESTIONS', 'id' => 'parent_questions', 'class' => ' demo-default', 'placeholder' => 'SELECT PARENTS QUESTIONS']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group parents-info" style="display:none;">
                                <label class="col-sm-3 control-label">PARENTS QUESTIONS'S ANSWER <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('parent_questions_answer', ['label' => false, 'type' => 'select', 'options' => [], 'empty' => 'SELECT PARENTS QUESTIONS ANSWER', 'id' => 'parent_questions_answer1', 'class' => ' demo-default', 'placeholder' => 'SELECT PARENTS QUESTIONS ANSWER']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">QUESTION TITLE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('question_title', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER QUESTION TITLE', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ANSWER TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('answer_type', ['label' => false, 'type' => 'select', 'options' => ['t' => 'Text', 'rb' => 'RADIO BUTTON'], 'empty' => 'SELECT ANSWER TYPE', 'id' => 'answer-type', 'class' => ' demo-default', 'placeholder' => 'ENTER ANSWER TYPE']); ?>
<!--                                        <input type="number" name="order_id" class="form-control  required number" placeholder="Enter Category order id" id="order_id" aria-required="true">-->
                                    </div>
                                </div>
                            </div>
                            <div id="text_section" ></div>
                            <div id="answer_val_section"></div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE QUESTION</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Questions', 'action' => 'index', $service_id), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                        </div>
                    </div>
                    <?php echo $this->Form->end(); ?>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>