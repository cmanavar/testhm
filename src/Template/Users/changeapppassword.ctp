<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
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
                    CHANGE PASSWORD
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create('', ['class' => 'form-horizontal validate']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NEW PASSWORD<span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('password', ['label' => false, 'type' => 'password', 'id' => 'pwd', 'class' => 'form-control required  ', 'placeholder' => 'ENTER NEW PASSWORD']); ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CONFIRM PASSWORD<span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('confirm_password', ['label' => false, 'type' => 'password', 'equalTo' => '#pwd', 'class' => 'form-control required', 'placeholder' => 'ENTER CONFIRM PASSWORD']); ?> 
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">    
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button class="btn btn-success" name="submit">SAVE PASSWORD</button>
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Users', 'action' => 'appuser'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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

