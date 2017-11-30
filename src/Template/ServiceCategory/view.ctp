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
                <i class="fa fa-tags fa-fw"></i>SERVICE CATEGORIES
                <a href="<?php echo $this->Url->build(["controller" => "ServiceCategory", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
                    VIEW SERVICE CATEGORY DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php //echo "<pre>"; print_r($category_detail); exit; ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <?php // foreach ($category as $key => $val) { ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Name</label></td>
                                    <td><?= $category['name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Order ID</label></td>
                                    <td><?= $category['order_id']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Icon Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_CATEGORY_ICON_PATH . $category['icon_image'], ['height' => 80, 'width' => 80]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Banner Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_CATEGORY_BANNER_PATH . $category['banner_image'], ['height' => 65, 'width' => 125]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Square Image Preview</label></td>
                                    <td><?= $this->Html->image(SERVICE_CATEGORY_SQUARE_BANNER_PATH . $category['square_image'], ['height' => 65, 'width' => 65]) . "<br/>" ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= $category['status']; ?></td>
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