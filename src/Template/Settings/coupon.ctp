<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php echo $this->Html->script(array('custom/customedatepicker.js', 'maskedinput.js'), ['block' => 'scriptBottom']); ?>
<?php echo $this->Html->css('jquery-ui.css'); ?>
<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<?php echo $this->element('js/datatable-delete'); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-tag fa-fw"></i> COUPON
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo ($this->Flash->render()); ?>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <?php if ($this->request->action == 'coupon') { ?>ADD NEW<?php } else { ?>EDIT<?php } ?> COUPON 
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php echo $this->Form->create($coupon, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">COUPON CODE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('code', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER COUPON CODE', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>            
                            <div class="form-group">
                                <label class="col-sm-3 control-label">DISCOUNT TYPE <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php $options = ['PERCENTAGE' => 'PERCENTAGE', 'PRICE' => 'PRICE']; ?>
                                        <?php echo $this->Form->input('user_type', ['label' => false, 'type' => 'select', 'options' => $options, 'id' => 'qauntity', 'class' => 'demo-default upper required', 'placeholder' => 'Enter DISCOUNT TYPE']); ?> 
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">CASHBACK <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="custom-radio radio ">
                                        <?php $valsArr['val'] = (isset($coupon['user_type']) && $coupon['user_type'] == 'YES') ? $coupon['user_type'] : 'NO'; ?>


                                        <?php echo $this->Form->radio('user_type', [['value' => 'YES', 'text' => 'YES'], ['value' => 'NO', 'text' => 'NO']], $valsArr); ?>
                                    </div>  
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">AMOUNT <span class="text-danger">*</span></label>
                                <div class="col-sm-6">
                                    <div class="input text">
                                        <?php echo $this->Form->input('amount', ['label' => false, 'class' => 'form-control required normal-font', 'placeholder' => 'ENTER AMOUNT', 'maxlength' => 255]); ?>
                                    </div>
                                </div>
                            </div>
                            <?php if (isset($coupon['valid_to']) && !empty($coupon['valid_to'])) { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">VALID TO <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('valid_to', ['label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control required dateField', 'placeholder' => 'VALID TO', 'value' => $coupon['valid_to']->format('d-m-Y')]); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">VALID TO <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('valid_to', ['label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control required dateField', 'placeholder' => 'VALID TO']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <?php if (isset($coupon['valid_from']) && !empty($coupon['valid_from'])) { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">VALID FROM <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('valid_from', ['label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control required dateField', 'placeholder' => 'VALID FROM', 'value' => $coupon['valid_from']->format('d-m-Y')]); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">VALID FROM <span class="text-danger">*</span></label>
                                    <div class="col-sm-6">
                                        <div class="input text">
                                            <?php echo $this->Form->input('valid_from', ['label' => false, 'div' => false, 'type' => 'text', 'class' => 'form-control required dateField', 'placeholder' => 'VALID FROM']); ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <label class="col-sm-3  control-label"></label>
                        <div class="col-sm-6">
                            <button type="submit" class="btn btn-primary">SAVE COUPON</button>                                    
                            <?php echo $this->Html->link('CANCEL', array('controller' => 'Settings', 'action' => 'coupon'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
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
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    LIST FAQ
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <div class="table-responsive">
                            <div id="dataTables-responsive_wrapper" class="dataTables_wrapper form-inline dt-bootstrap no-footer">
                                <div class="row">
                                    <div class="col-sm-12 col-md-12 col-lg-12">
                                        <div class="" id="dataTables-responsive_length">
                                            <table class="table table-striped table-bordered table-hover" id="dataTables-responsive" >
                                                <thead>
                                                    <tr>
                                                        <th width="2%">Sr.no</th>
                                                        <th>COUPON CODE</th>
                                                        <th>DISCOUNT</th>
                                                        <th>VALID DATE</th>
                                                        <th width="12%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($coupons)) {
                                                        foreach ($coupons as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['code']) ?></td>    
                                                                <td><?php echo stripslashes($val['amount']) ?><?php echo ($val['discount_type'] == 'PERCENTAGE') ? ' (%)' : ' (<i class="fa fa-inr"></i>)'; ?></td>
                                                                <td><?php echo $val['valid_to']->format('d-m-Y') . " TO " . $val['valid_from']->format('d-m-Y'); ?></td>
                                                                <td>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Settings', 'action' => 'couponedit', $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Settings', 'action' => 'coupondelete']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
                                                                </td>     
                                                            </tr>

                                                            <?php
                                                        }
                                                    } else {
                                                        echo '<tr><td colspan="6" style="text-align:center;"><b>No Records found </b></td></tr>';
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.table-responsive -->
                        <!--paginations-->

                    </div>
                    <!-- /.panel-body -->
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.row -->
        </div>
    </div>
</div>