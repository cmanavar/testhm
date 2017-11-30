<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-asterisk fa-fw"></i>SERVICE
                <a href="<?php echo $this->Url->build(["controller" => "Services", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW SERVICE DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php //echo "<pre>"; print_r($category_detail); exit; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <?php // foreach ($category as $key => $val) { ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Name</label></td>
                                    <td><?= $services['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Description</label></td>
                                    <td><?= $services['service_description']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Category</label></td>
                                    <td><?= $services['category_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Visit Charge</label></td>
                                    <td><?= $services['visit_charge']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Minimum Charge	</label></td>
                                    <td><?= $services['minimum_charge']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Banner Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_BANNER_PATH . $services['banner_image'], ['height' => 65, 'width' => 150]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Square Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_SQUARE_BANNER_PATH . $services['square_image'], ['height' => 65, 'width' => 65]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Popular</label></td>
                                    <td><?= $services['popular']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= $services['status']; ?></td>
                                </tr>
                                <?php // } ?>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.panel-body -->
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.row -->
    </div>
</div>