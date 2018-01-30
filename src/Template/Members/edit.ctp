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
                <i class="fa fa-user fa-fw"></i>MEMBERS
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
                    <?php if ($this->request->action == 'add') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> MEMBERS 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create('', ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> MEMBERSHIP PLAN DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">MEMBERSHIP PLAN <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input select">
                                        <?php echo $this->Form->input('plan_id', ['label' => false, 'type' => 'select', 'options' => $planLists, 'empty' => 'SELECT MEMBERSHIP PLAN', 'id' => 'select-category', 'class' => ' demo-default', 'placeholder' => 'SELECT MEMBERSHIP PLAN', 'value' => $user['plan_id'], 'disabled' => 'disabled']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> PERSONAL DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">NAME <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER NAME', 'maxlength' => 255, 'value' => $user['name'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ADDRESS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('address', ['rows' => '3', 'cols' => '90', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER ADDRESS', 'value' => $user['address']], ['type' => 'textarea']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CITY <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('city', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER CITY NAME', 'maxlength' => 100, 'value' => $user['city'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PHONE NUMBER <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('phone_no', ['label' => false, 'class' => 'form-control required normal-font number', 'placeholder' => 'ENTER PHONE NUMBER', 'maxlength' => 10, 'value' => $user['phone_no'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Email ID <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('email', ['label' => false, 'class' => 'form-control required normal-font email', 'placeholder' => 'ENTER EMAIL', 'maxlength' => 255, 'value' => $user['email'], 'readonly' => 'readonly']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">BIRTH DATE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <?php echo $this->Form->input('birthdate', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font date', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate']->format('d-m-Y')]); ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ANNIVERSARY DATE </label>
                                <div class="col-sm-6">
                                    <?php if (isset($user['aniversary_date']) && !empty($user['aniversary_date'])) { ?> 
                                        <?php echo $this->Form->input('aniversary_date', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER ANNIVERSARY DATE', 'maxlength' => 255, 'value' => $user['aniversary_date']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('aniversary_date', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER ANNIVERSARY DATE', 'maxlength' => 255]); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> FAMILY PERSON DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label family-headings">PERSON 1 </label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('person_1', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER NAME', 'value' => $user['person_1']]); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if (isset($user['birthdate_1']) && !empty($user['birthdate_1'])) { ?> 
                                        <?php echo $this->Form->input('birthdate_1', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate_1']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('birthdate_1', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER BIRTHDATE']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label family-headings">PERSON 2 </label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('person_2', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER NAME', 'value' => $user['person_2']]); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if (isset($user['birthdate_2']) && !empty($user['birthdate_2'])) { ?> 
                                        <?php echo $this->Form->input('birthdate_2', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate_2']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('birthdate_2', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER BIRTHDATE']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label family-headings">PERSON 3 </label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('person_3', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER NAME', 'value' => $user['person_3']]); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if (isset($user['birthdate_3']) && !empty($user['birthdate_3'])) { ?> 
                                        <?php echo $this->Form->input('birthdate_3', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate_3']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('birthdate_3', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER BIRTHDATE']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label family-headings">PERSON 4 </label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('person_4', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER NAME', 'value' => $user['person_4']]); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if (isset($user['birthdate_4']) && !empty($user['birthdate_4'])) { ?> 
                                        <?php echo $this->Form->input('birthdate_4', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate_4']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('birthdate_4', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER BIRTHDATE']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label family-headings">PERSON 5 </label>
                                <div class="col-sm-4">
                                    <?php echo $this->Form->input('person_5', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER NAME', 'value' => $user['person_5']]); ?>
                                </div>
                                <div class="col-sm-2">
                                    <?php if (isset($user['birthdate_5']) && !empty($user['birthdate_5'])) { ?> 
                                        <?php echo $this->Form->input('birthdate_5', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate required normal-font', 'placeholder' => 'ENTER BIRTHDATE', 'maxlength' => 255, 'value' => $user['birthdate_5']->format('d-m-Y')]); ?>
                                    <?php } else { ?>
                                        <?php echo $this->Form->input('birthdate_5', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font date', 'placeholder' => 'ENTER BIRTHDATE']); ?>
                                    <?php } ?>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> EMPLOYMENT DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">OCCUPATION <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('occupation', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER OCCUPATION', 'maxlength' => 255, 'value' => $user['occupation']]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">COMPANY NAME </label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('company_name', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER COMPANY NAME', 'maxlength' => 255, 'value' => $user['company_name']]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">COMPANY WEBSITE </label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('company_website', ['label' => false, 'class' => 'form-control normal-font', 'placeholder' => 'ENTER COMPANY WEBSITE', 'maxlength' => 255, 'value' => $user['company_website']]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> PAYMENT DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">PAYMENT TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('payment_type', ['label' => false, 'type' => 'select', 'options' => ['CHEQUE' => 'CHEQUE', 'UPI' => 'UPI', 'OTHER' => 'OTHER'], 'empty' => 'SELECT PAYMENT TYPE', 'id' => 'payment-type', 'class' => ' demo-default', 'placeholder' => 'SELECT PAYMENT TYPE', 'value' => $user['payment_type']]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="chequeDetail" id="chequeDetails" style="<?php echo ($user['payment_type'] == 'CHEQUE') ? 'display:block' : 'display:none'; ?>">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">BANK NAME <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('bank_name', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER BANK NAME', 'maxlength' => 255, 'value' => $user['bank_name']]); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">CHEQUE NUMBER <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('cheque_no', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER CHEQUE NUMBER', 'maxlength' => 255, 'value' => $user['cheque_no']]); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">CHEQUE DATE <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php if (isset($user['cheque_date']) && !empty($user['cheque_date'])) { ?> 
                                                <?php echo $this->Form->input('cheque_date', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font', 'placeholder' => 'ENTER CHEQUE DATE', 'maxlength' => 255, 'value' => $user['cheque_date']->format('d-m-Y')]); ?>
                                            <?php } else { ?>
                                                <?php echo $this->Form->input('cheque_date', ['label' => false, 'type' => 'text', 'class' => 'form-control pastDate normal-font', 'placeholder' => 'ENTER CHEQUE DATE']); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="transcationDetail" id="transcationDetails" style="<?php echo ($user['payment_type'] == 'UPI') ? 'display:block' : 'display:none'; ?>">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">TRANSACTION ID <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php if (isset($user['transaction_id']) && $user['transaction_id'] != '') { ?>
                                                <?php echo $this->Form->input('transaction_id', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER TRANSACTION ID', 'maxlength' => 255]); ?>
                                            <?php } else { ?>
                                                <?php echo $this->Form->input('transaction_id', ['label' => false, 'type' => 'text', 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER TRANSACTION ID', 'maxlength' => 255, 'value' => $user['transcation_id']]); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="otherDetail" id="otherDetails" style="<?php echo ($user['payment_type'] == 'OTHER') ? 'display:block' : 'display:none'; ?>">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">OTHERS <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php if (isset($user['other_details']) && $user['other_details'] != '') { ?>
                                                <?php echo $this->Form->input('other_details', ['rows' => '3', 'cols' => '90', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER OTHER PAYMENT DETAILS', 'value' => $user['other_details']], ['type' => 'textarea']); ?>
                                            <?php } else { ?>
                                                <?php echo $this->Form->input('other_details', ['rows' => '3', 'cols' => '90', 'label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER OTHER PAYMENT DETAILS'], ['type' => 'textarea']); ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> REFERENCE DETAILS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">REFERENCE USER </label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('refer_id', ['label' => false, 'type' => 'select', 'options' => $referLists, 'empty' => 'SELECT REFERENCE USER', 'id' => 'select-type', 'class' => ' demo-default', 'placeholder' => 'SELECT REFERENCE USER', 'value' => $user['referral_id']]); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h4 class="black-header">
                                        <i class="fa fa-user fa-fw"></i> ACCOUNT STATUS
                                    </h4>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">STATUS <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php echo $this->Form->radio('active', [['value' => 'Y', 'text' => 'ACTIVE'], ['value' => 'N', 'text' => 'INACTIVE']], ['value' => $user['active'] == 'Y' ? 'Y' : 'N']); ?>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE MEMBER</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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