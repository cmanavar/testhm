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
                <i class="fa fa-user fa-fw"></i>VENDORS
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
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> VENDOR 
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
                                        <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER NAME', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SERVICE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input tel">
                                        <?php echo $this->Form->input('service_id', ['label' => false, 'type' => 'select', 'options' => $services, 'empty' => 'SELECT CATEGORY', 'id' => 'select-category', 'class' => ' demo-default', 'placeholder' => 'ENTER CATEGORY NAME']); ?>
<!--                                        <input type="number" name="order_id" class="form-control  required number" placeholder="Enter Category order id" id="order_id" aria-required="true">-->
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email ID <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control required normal-font email', 'placeholder' => 'ENTER EMAIL', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PHONE NUMBER 1 <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('phone_no', ['label' => false, 'class' => 'form-control required normal-font number', 'placeholder' => 'ENTER PHONE NUMBER 1', 'maxlength' => 10]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PHONE NUMBER 2 <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('phone_number_2', ['label' => false, 'class' => 'form-control required normal-font number', 'placeholder' => 'ENTER PHONE NUMBER 2', 'maxlength' => 10]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label padng_rgtrmv">PROFILE PHOTO <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php
                                    if (isset($user['profile_picture']) && $user['profile_picture'] != '') {
                                        echo $this->Html->image(SERVICE_CATEGORY_SQUARE_BANNER_PATH . $user['profile_picture'], ['height' => 100, 'width' => 100]) . "<br/>";
                                        ?>
                                        <br/>
                                        <a data-toggle="modal" url=<?php echo $this->Url->build(['controller' => 'Vendors', 'action' => 'deleteimage', 'profile_picture', $user['profile_picture']]) ?> data-value="<?php echo $user['profile_picture']; ?>" data-target="#delete" href="#"  class="btn btn-danger delete ">REMOVE IMAGE</a>
                                        <?php
                                    } else {
                                        echo $this->Form->input('profile_picture', ['label' => false, 'type' => 'file', 'class' => 'required imgpreview', 'id' => 'squarebanner']);
                                        ?>
                                        <br/>
                                        <div class="imageblock">
                                            <div class="form-group hover-element scanimgblock">
                                                <div class="col-sm-8 td-inputbox">
                                                    <?php echo $this->Html->image('upload_image.png', ['class' => 'square_upload', 'alt' => 'Your image', 'id' => 'squarebanner_upload_preview', 'height' => '75']); ?>
                                                </div>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                    ?> 
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">AGREEMENTS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('agreement', ['label' => false, 'type' => 'file', 'class' => 'required', 'id' => 'agrements']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ID PROOF <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('id_proof', ['label' => false, 'type' => 'file', 'class' => 'required', 'id' => 'idproof']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SHIFT TIMING <span class="text-danger">*</span></label>
                                <div class="col-sm-2">
                                    <div class="input text">
                                        <?php echo $this->Form->input('shift_start', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'SHIFT START TIME', 'id' => 'asdad', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                                <div class="col-sm-2">
                                    <div class="input text">
                                        <?php echo $this->Form->input('shift_end', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'SHIFT END TIME', 'id' => 'asdasdfa', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('status', [['value' => 'ACTIVE', 'text' => 'ACTIVE'], ['value' => 'INACTIVE', 'text' => 'INACTIVE']], ['value' => 'ACTIVE']); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE VENDOR</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Vendors', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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