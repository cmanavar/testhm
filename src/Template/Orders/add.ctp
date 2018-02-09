<!--
 * Template : index
 *
 * Function : Display list of Orders
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /orders
-->
<?php echo $this->Html->script('custom/custom_order.js', array('block' => 'scriptBottom')); ?>
<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-shopping-cart fa-fw"></i> ORDERS
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="col-lg-12">
        <div class="row">
            <div class="col-md-12">

            </div>
        </div>
    </div>
    <div class="col-lg-12 sp-list">  
        <!-- /.row -->
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    ADD ORDERS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($order); //exit; ?>
                            <?php echo $this->Form->create($order, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                            <input type="hidden" id="ajaxUrlforGetServices" value='<?php echo $this->Url->build(["controller" => "Orders", "action" => "getServicesList"], true); ?>'>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SERVICE CATEGORY <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('category_id', ['label' => false, 'type' => 'select', 'options' => $serviceCategory, 'empty' => 'SELECT SERVICE CATEGORY', 'id' => 'serviceCategory', 'class' => ' demo-default select-category required', 'placeholder' => 'SELECT SERVICE CATEGORY']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">SERVICES <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php echo $this->Form->input('service_id', ['label' => false, 'type' => 'select', 'options' => [], 'empty' => 'SELECT SERVICES', 'id' => 'serviceList', 'class' => ' demo-default select-category required', 'placeholder' => 'SELECT SERVICES']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">ASSIGN VENDOR <span class="text-danger">*</span></label>
                                <div class="col-sm-5">
                                    <div class="input text">
                                        <?php //echo $this->Form->input('vendor_id', ['label' => false, 'type' => 'select', 'options' => $vendors[$v['service_id']], 'empty' => 'SELECT MEMBERSHIP PLAN', 'id' => '', 'class' => ' demo-default select-category required', 'placeholder' => 'ASSIGN VENDOR']); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-3"></label>
                                <div class="col-sm-5">
                                    <button type="submit" class="btn btn-primary">ORDER SCHEDULED</button>                                    
                                    <?php echo $this->Html->link('CANCEL', array('controller' => 'Members', 'action' => 'index'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>                        
                                </div>
                            </div>
                            <?php echo $this->Form->end(); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>