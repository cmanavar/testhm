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
                RATECARD
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
                    EDIT RATE
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($ratecard, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">TITLE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('title', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'ENTER RATECARD TITLE', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">QUANTITY <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('qunatity', ['label' => false, 'type' => 'select', 'options' => ['YES' => 'YES', 'NO' => 'NO', 'ON_INSPECTION' => 'ON INSPECTION'], 'empty' => 'SELECT ANSWER TYPE', 'id' => 'answer-type', 'class' => ' demo-default', 'placeholder' => 'ENTER ANSWER TYPE', 'disabled']); ?>
                                    </div>
                                </div>
                                <?php if ($ratecard['qunatity'] == 'YES') { ?>
                                    <input type="button" class="btn btn-primary" id="more_rates" value="+" data-rate-count="<?php echo count($ratecard['rates']); ?>" />
                                <?php } ?>
                            </div>
                            <?php if ($ratecard['qunatity'] == 'YES') { ?>
                                <input type="hidden" id="updaterate" value='<?php echo $this->Url->build(["controller" => "Ratecards", "action" => "updaterate"], true); ?>'>
                                <input type="hidden" id="addnewrate" value='<?php echo $this->Url->build(["controller" => "Ratecards", "action" => "addnewrate", $ratecard['id']], true); ?>'>
                                <?php if (isset($ratecard['rates']) && !empty($ratecard['rates'])) { ?>
                                    <?php foreach ($ratecard['rates'] as $key => $val) { ?>
                                        <?php //pr($val); exit; ?>
                                        <div class="form-group">
                                            <label class="col-sm-3 control-label">RATE <?php echo $key + 1 ?><span class="text-danger">*</span></label>
                                            <div class="col-sm-4">
                                                <?php echo $this->Form->input('qunatity_title', ['label' => false, 'class' => 'form-control required input-qunatity-title-' . $val['id'], 'disabled' => 'disabled', 'placeholder' => 'ENTER QUANTITY', 'maxlength' => 255, 'value' => $val['qunatity_title']]); ?>
                                                <label id="question-title-error-<?= $val['id']; ?>" class="error" style="display:none;" for="question-title">THIS FIELD IS REQUIRED.</label>
                                            </div>
                                            <div class="col-md-2">
                                                <?php echo $this->Form->input('rate', ['label' => false, 'class' => 'form-control required normal-font input-rate-' . $val['id'], 'disabled' => 'disabled', 'placeholder' => 'ENTER PRICE', 'maxlength' => 255, 'value' => $val['rate']]); ?>
                                                <label id="question-price-error-<?= $val['id']; ?>" class="error" style="display:none;" for="question-price">THIS FIELD IS REQUIRED.</label>
                                            </div>
                                            <div class="div-edit-<?= $val['id']; ?>">
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-warning btn-rate-edit fa fa-pencil', 'data-id' => $val['id'], 'escape' => false, 'title' => 'EDIT']); ?>
                                                <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Ratecards', 'action' => 'deleterates', $ratecard['id']]) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o btn-rate-delete delete"></a>
                                            </div>
                                            <div class="div-save-<?= $val['id']; ?>" style="display:none;">
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-success btn-rate-save fa fa-save', 'data-id' => $val['id'], 'escape' => false, 'title' => 'SAVE']); ?>
                                                <?php echo $this->Html->link('', 'javascript:void(0)', ['class' => 'btn btn-danger  btn-rate-cancel fa fa-times', 'data-id' => $val['id'], 'escape' => false, 'title' => 'CANCEL']); ?>
                                            </div>
                                        </div>
                                    <?php } ?>
                                <?php } ?>
                                <div id="text_section" ></div>
                                <div id="answer_val_section"></div>
                            <?php } elseif ($ratecard['qunatity'] == 'NO') { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">RATE <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('price', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'ENTER PRICE', 'maxlength' => 255]); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">

                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE RATE</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Ratecards', 'action' => 'index', $service_id), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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