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
                RATECARD
                <a href="<?php echo $this->Url->build(["controller" => "Ratecards", "action" => "index", $service_id]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
                    VIEW RATECARDS DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <?php
                    //echo "<pre>";
                    //print_r($question); //exit;  
                    ?>
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <?php // foreach ($category as $key => $val) {    ?>
                                <tr>
                                    <td width="30%"><label class="control-label">Category Name</label></td>
                                    <td><?= $ratecards['category_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Service Name</label></td>
                                    <td><?= $ratecards['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Title</label></td>
                                    <td><?= $ratecards['title']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Qunatity</label></td>
                                    <td><?= $ratecards['qunatity']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Rates (<i class="fa fa-inr"></i>)</label></td>
                                    <?php if ($ratecards['qunatity'] == 'YES') { ?>
                                        <td>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>Label</td>
                                                        <td>Price (<i class="fa fa-inr"></i>)</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach ($ratecards['rates'] as $key => $val) { ?>
                                                        <tr>
                                                            <td><?= $val['qunatity_title']; ?></td>
                                                            <td><?= number_format($val['rate'], 2); ?></td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </td>
                                    <?php } else { ?>
                                        <td><?= number_format($ratecards['price'], 2); ?></td>
                                    <?php } ?>
                                </tr>

                                <?php // }    ?>
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