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
                <i class="fa fa-user fa-fw"></i>SURVEYS
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
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> SURVEYS 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($serveys, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">AREA TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input select">
                                        <?php echo $this->Form->input('user_type', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER USERTYPE', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NAME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('person_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PERSON NAME', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($serveys['user_type'] == 'COMMERCIAL') { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">COMPANY NAME <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('company_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER COMPANY NAME', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ADDRESS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('address', ['rows' => '3', 'cols' => '90', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER ADDRESS', 'readonly' => 'readonly'], ['type' => 'textarea']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CONTACT NUMBER <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('contact_number', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER CONTACT NUMBER', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">EMAIL ID <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER COMPANY NAME', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">APPOINMENT DATE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('appoinment_date', ['label' => false, 'type' => 'text', 'class' => 'form-control dateField required normal-font', 'placeholder' => 'ENTER APPOINMENT DATE', 'maxlength' => 255, 'value' => $serveys['appoinment_date']->format('d-m-Y')]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">APPOINMENT TIME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('appoinment_time', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER COMPANY NAME', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">WHAT SERVICE OR REPAIR WORK USUALLY YOU PERFORM AT YOUR PLACE? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('services_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER SERVICES NAME', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">WHO PERFORMS THE SERVICE OR REPAIR WORK AT YOUR PLACE? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('who_performs_the_service_or_repair_work_at_your_place', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER SERVICES NAME', 'maxlength' => 255, 'value' => $serveys['who_performs_the_service_or_repair_work_at_your_place'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">HOW WOULD YOU RATE YOUR CURRENT SERVICE PROVIDER'S SATISFACTION WITH US? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">QUALITY OF SERVICE <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_quality_of_service', ['label' => false,  'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER QUALITY OF SERVICE', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">PRICE RANGE <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_price_range', ['label' => false,  'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PRICE RANGE', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">PUNCTUALITY <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_punctuality', ['label' => false,  'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PUNCTUALITY', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">CLEANLINESS <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_cleanliness', ['label' => false,  'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER CLEANLINESS', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">PROFESSIONAL BEHAVIOR <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_professional_behavior', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PROFESSIONAL BEHAVIOR', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">PERIODIC CHECKUPS <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_periodic_checkups', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PERIODIC CHECKUPS', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <label class="control-label">OVER ALL RATING <span class="text-danger">*</span></label>
                                        <?php echo $this->Form->input('rating_over_all', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER PERIODIC CHECKUPS', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">HOW OFTEN DO YOU TYPICALLY USE REPAIR AND SERVICE WORK? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('who_performs_the_service_or_repair_work_at_your_place', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER HOW OFTEN DO YOU TYPICALLY USE REPAIR AND SERVICE WORK', 'maxlength' => 255, 'value' => str_replace("_", " ", $serveys['how_often_do_you_typically_use_repair_and_service_work']), 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">HOW MUCH USUALLY THEY CHARGE FOR REPAIR AND SERVICE WORK? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('who_performs_the_service_or_repair_work_at_your_place', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER HOW MUCH USUALLY THEY CHARGE FOR REPAIR AND SERVICE WORK', 'maxlength' => 255, 'value' => str_replace("_", " ", $serveys['how_much_usually_they_charge_for_repair_and_service_work']), 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">HOW LONG DO YOU HAVE WAIT TO AVAIL THEIR SERVICE? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('how_long_do_you_have_wait_to_avail_their_service', ['label' => false, 'type'=>'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER HOW LONG DO YOU HAVE WAIT TO AVAIL THEIR SERVICE', 'maxlength' => 255, 'value' => str_replace("_", " ", $serveys['how_long_do_you_have_wait_to_avail_their_service']), 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ACCORDING TO YOU WHO IS IDEAL SERVICE PROVIDER? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('according_to_you_who_is_Ideal_service_provider', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER HOW LONG DO YOU HAVE WAIT TO AVAIL THEIR SERVICE', 'maxlength' => 255, 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">IF WE COME UP WITH THE SERVICE PROVIDER TO YOUR SATISFACTION WILL YOU LISTEN TO US? <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('if_we_come_up_with_the_service_provider_to_your_satisfaction_wil', ['label' => false, 'type'=>'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER HOW LONG DO YOU HAVE WAIT TO AVAIL THEIR SERVICE', 'maxlength' => 255, 'value' => $serveys['if_we_come_up_with_the_service_provider_to_your_satisfaction_wil'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE SURVEYS</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Surveys', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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