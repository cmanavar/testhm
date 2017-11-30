<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                RATECARDS
                <a href="<?php echo $this->Url->build(["controller" => "Ratecards", "action" => "add", $service_id]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW RATE</button></a>
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
                    LIST OF RATECARDS
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
                                                        <th width="2%">Sr.No</th>
                                                        <th>Title</th> 
                                                        <th>Qunatity</th>
                                                        <th>Price</th>
                                                        <th width="11%">Actions</th>
                                                    </tr>   
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    if (!empty($ratecard)) {
                                                        foreach ($ratecard as $key => $val) {
                                                            ?>
                                                            <tr>
                                                                <td><?php echo $key + 1; ?></td>
                                                                <td><?php echo stripslashes($val['title']) ?></td>
                                                                <td><?php echo stripslashes(ucfirst(str_replace('_', ' ', $val['qunatity']))) ?></td>
                                                                <td><?php echo stripslashes($val['price']) ?></td>
                                                                <td>
        <!--                                                                    <a class='btn btn-info btn-sm' href='#'><i class='fa fa-lg fa-eye'></i></a>-->
                                                                    <?php echo $this->Html->link('', ['controller' => 'Ratecards', 'action' => 'view', $service_id, $val['id']], ['class' => 'btn btn-info fa fa-eye', 'escape' => false, 'title' => 'VIEW']); ?>
                                                                    <?php echo $this->Html->link('', ['controller' => 'Ratecards', 'action' => 'edit', $service_id, $val['id']], ['class' => 'btn btn-warning fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
                                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Ratecards', 'action' => 'delete', $service_id]) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>
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