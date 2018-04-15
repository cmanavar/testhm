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
                <i class="fa fa-user fa-fw"></i>SALES PERSON
                <a href="<?php echo $this->Url->build(["controller" => "Sales", "action" => "index"]); ?>"><button class="btn btn-warning btn-sm pull-right" >BACK</button></a>
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
                    VIEW SALES PERSON DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-bordered">
                                <tr>
                                    <td width="30%"><label class="control-label">Name</label></td>
                                    <td><?= $vendor['name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Email</label></td>
                                    <td><?= $vendor['email']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Phone number</label></td>
                                    <td><?= $vendor['phone_no']; ?></td>
                                </tr>
                                <tr>
                                    <td width="30%"><label class="control-label">Status</label></td>
                                    <td><?= (isset($vendor['active']) && $vendor['active'] == 'Y') ? 'ACTIVE' : 'INACTIVE'; ?></td>
                                </tr>
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