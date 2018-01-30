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
<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('custom/dashboard.js', ['block' => 'scriptBottom']); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-user fa-fw"></i>USERS
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
                    <?php if ($this->request->action == 'adduser') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> USER
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($user, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NAME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'ENTER NAME']); ?> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">EMAIL <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'ENTER EMAIL']); ?> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PHONE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('phone_no', ['label' => false, 'class' => 'form-control required', 'placeholder' => 'ENTER PHONE', 'maxlength' => "13", 'minlength' => "10"]); ?> 
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">USER TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php $options = ['ADMIN' => 'ADMIN', 'OPERATION_MANAGER' => 'OPERATION MANAGER', 'TELLY_CALLER' => 'TELLY CALLER']; ?>
                                        <?php echo $this->Form->input('user_type', ['label' => false, 'type' => 'select', 'options' => $options, 'id' => 'select-type', 'class' => 'demo-default upper required', 'empty' => '--SELECT USER TYPE--', 'placeholder' => 'Enter User Type']); ?> 
                                    </div>
                                </div>
                            </div>
                            <?php if ($this->request->action == 'adduser') { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">PASSWORD<span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('password', ['label' => false, 'type' => 'password', 'id' => 'pwd', 'class' => 'form-control required  ', 'placeholder' => 'ENTER PASSWORD']); ?> 
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">CONFIRM PASSWORD<span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <?php echo $this->Form->input('confirm_password', ['label' => false, 'type' => 'password', 'equalTo' => '#pwd', 'class' => 'form-control required', 'placeholder' => 'ENTER CONFIRM PASSWORD']); ?> 
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE USER</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Users', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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