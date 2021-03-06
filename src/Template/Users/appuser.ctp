<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>

<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-user fa-fw"></i>APP USERS
                <a href="<?php echo $this->Url->build(["controller" => "Users", "action" => "addappuser"]); ?>"><button class="btn btn-primary btn-sm pull-right" >ADD NEW APP USER</button></a>
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
                    LIST APP USERS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-center">NO</th>
                                        <th class="text-center">NAME</th>
                                        <th class="text-center">EMAIL</th>
                                        <th class="text-center">PHONE</th>
                                        <th class="text-center">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($users)) {
                                        foreach ($users as $key => $val):
                                            ?>
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $key + 1; ?></td>
                                                <td class="text-center"><?php echo $val->name; ?></td>
                                                <td class="text-center"><?php echo $val->email; ?></td>
                                                <td class="text-center"><?php echo $val->phone_no; ?></td>
                                                <td class="text-center">
                                                    <?php echo $this->Html->link('', ['controller' => 'Users', 'action' => 'changeapppassword', $val['id']], ['class' => 'btn btn-primary fa fa-key', 'escape' => false, 'title' => 'CHANGE PASSWORD']); ?>
                                                    <?php echo $this->Html->link('', ['controller' => 'Users', 'action' => 'editappuser', $val['id']], ['class' => 'btn btn-success fa fa-pencil', 'escape' => false, 'title' => 'EDIT']); ?>
<!--                                                    <a data-toggle="modal" title = 'DELETE' url=<?php echo $this->Url->build(['controller' => 'Users', 'action' => 'deleteappuser']) ?> data-value="<?php echo $val['id']; ?>" data-target="#delete" href="#" class="btn btn-danger fa fa-trash-o delete"></a>-->
                                                </td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    }
                                    ?>  
                                </tbody>
                            </table>
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