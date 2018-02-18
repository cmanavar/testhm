<!--
 * Template : index
 *
 * Function : Display list of Orders
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /orders
-->

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-usd fa-fw"></i> ORDERS
            </h1>
        </div>
    </div>
    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    VIEW ORDERS DETAILS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php //pr($orders); exit; ?>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <td>Name</td>
                                        <td><?= $greencash['name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Total Cash</td>
                                        <td><?= $greencash['totalCash']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Paid Cash</td>
                                        <td><?= $greencash['totalpaidCash']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Due Cash</td>
                                        <td><?= $greencash['totalCash'] - $greencash['totalpaidCash']; ?></td>
                                    </tr>
                                </table>
                            </div>
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