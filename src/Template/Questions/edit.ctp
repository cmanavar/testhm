<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php //pr($question); exit; ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
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
                    EDIT QUESTION
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
                                        <?= strtoupper($question['questions_type']) ?>
                                    </div>  
                                </div>
                            </div>
                            <?php if (strtolower($question['questions_type']) != 'parent') { ?>
                                <div class="form-group parents-info" >
                                    <label class="col-sm-3 control-label">PARENTS QUESTIONS <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input tel">
                                            <?= strtoupper($question['parent_questions']) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group parents-info" >
                                    <label class="col-sm-3 control-label">PARENTS QUESTIONS'S ANSWER <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input tel">
                                            <?= strtoupper($question['parent_answers']) ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <!--                            <div class="form-group parents-info" >
                                                            <label class="col-sm-3 control-label">QUESTION TYPE <span class="text-danger">*</span></label>
                                                            <div class="col-sm-6">
                                                                <div class="input tel">
                            <?php //echo $this->Form->input('que_type', ['label' => false, 'type' => 'select', 'options' => ['QUANTITY' => 'QUANTITY', 'QUESTION' => 'QUESTION'], 'empty' => 'SELECT QUESTION TYPE', 'id' => 'question_type', 'class' => ' demo-default', 'placeholder' => 'ENTER QUESTION TYPE']); ?>
                                                                </div>
                                                            </div>
                                                        </div>-->
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
                                        <?php echo $this->Form->input('answer_type', ['label' => false, 'type' => 'select', 'options' => ['t' => 'Text', 'rb' => 'RADIO BUTTON'], 'empty' => 'SELECT ANSWER TYPE', 'id' => 'answer-type', 'class' => ' demo-default', 'placeholder' => 'ENTER ANSWER TYPE', 'disabled']); ?>
                                    </div>
                                </div>
                                <input type="button" class="btn btn-primary" id="more_fields" value="+" data-answer-count="<?php echo count($question['answers']); ?>" />
                            </div>
                            <?php // pr($question);  exit;?>
                            <input type="hidden" id="updateanswer" value='<?php echo $this->Url->build(["controller" => "Questions", "action" => "updateanswer"], true); ?>'>
                            <input type="hidden" id="addnewanswer" value='<?php echo $this->Url->build(["controller" => "Questions", "action" => "addnewanswer", $question['id']], true); ?>'>
                            <?php if (isset($question['answers']) && !empty($question['answers'])) { ?>
                                <?php foreach ($question['answers'] as $key => $val) { ?>
                                    <?php //pr($val); exit; ?>
                                    <div class="form-group">
                                        <label class="col-sm-3 control-label">ANSWER DETAILS <?php echo $key + 1 ?><span class="text-danger">*</span></label>
                                        <div class="col-sm-2">
                                            <?php echo $this->Form->input('label', ['label' => false, 'class' => 'form-control required normal-font input-label-' . $val['id'], 'disabled' => 'disabled', 'placeholder' => 'ENTER QUESTION TITLE', 'maxlength' => 255, 'value' => $val['label']]); ?>
                                            <label id="question-title-error-<?= $val['id']; ?>" class="error" style="display:none;" for="question-title">THIS FIELD IS REQUIRED.</label>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="col-sm-2 pull-left">
                                                <?php echo $this->Form->input('icon', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview input-icon-' . $val['id'], 'disabled' => 'disabled', 'id' => 'iconlogo' . $val['id']]); ?>
                                            </div>
                                            <div class="imageblock pull-right">
                                                <div class="form-group hover-element scanimgblock">
                                                    <div class="col-sm-1">
                                                        <?php if (isset($val['icon_img']) && $val['icon_img'] != '') { ?>
                                                            <?php echo $this->Html->image(QUETIONS_ICON_PATH.$val['icon_img'], ['class' => 'icon_upload', 'alt' => 'Your image', 'id' => 'iconlogo' . $val['id'] . '_upload_preview', 'height' => '50']); ?>
                                                        <?php } else { ?> 
                                                            <?php echo $this->Html->image('upload_image.png', ['class' => 'icon_upload', 'alt' => 'Your image', 'id' => 'iconlogo' . $val['id'] . '_upload_preview', 'height' => '50']); ?>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="col-md-1">
                                            <?php $quen = $val['quantity']; ?>
                                            <select class="answer_quantity input-quantity-<?= $val['id']; ?>" name="quantity" id="answer_quantity" disabled="disabled">
                                                <option value="YES" <?= $quen == 'YES' ? ' selected="selected"' : ''; ?>>YES</option>
                                                <option value="NO" <?= $quen == 'NO' ? ' selected="selected"' : ''; ?>>NO</option>
                                                <option value="ON_INSPECTION" <?= $quen == 'ON_INSPECTION' ? ' selected="selected"' : ''; ?>>INSPECTION</option>
                                            </select>
                                            <label id="question-quantity-error-<?= $val['id']; ?>" class="error" style="display:none;" for="question-quantity">THIS FIELD IS REQUIRED.</label>
                                        </div>
                                        <div class="col-md-1">
                                            <?php echo $this->Form->input('price', ['label' => false, 'class' => 'form-control required normal-font input-price-' . $val['id'], 'disabled' => 'disabled', 'placeholder' => 'ENTER QUESTION PRICE', 'maxlength' => 255, 'value' => $val['price']]); ?>
                                            <label id="question-price-error-<?= $val['id']; ?>" class="error" style="display:none;" for="question-price">THIS FIELD IS REQUIRED.</label>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="div-edit-<?= $val['id']; ?>">
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-warning btn-que-edit fa fa-pencil', 'data-id' => $val['id'], 'escape' => false, 'title' => 'EDIT']); ?>
                                                <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Questions', 'action' => 'deleteanswer', $question['id']]) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o btn-que-delete delete"></a>
                                            </div>
                                            <div class="div-save-<?= $val['id']; ?>" style="display:none;">
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-success btn-que-save fa fa-save', 'data-id' => $val['id'], 'escape' => false, 'title' => 'SAVE']); ?>
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-danger btn-que-cancel fa fa-times', 'data-id' => $val['id'], 'escape' => false, 'title' => 'CANCEL']); ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php } ?>
                            <?php } ?>
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