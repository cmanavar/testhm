<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->

<?php echo $this->element('js/datatable-delete'); ?>
<?php echo $this->Html->script('patient-dropdown.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>

<div id="page-wrapper">
    <div class="row">
        <input type="hidden" class="excelurl" value="<?php echo $this->Url->build(["controller" => "Reports", "action" => "exportsalesperformancereports"]); ?>">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-book fa-fw"></i>SALES PERFORMANCE REPORTS
                <a href="#" class="btn btn-social btn-google-plus btn_excel pull-right"><i class="fa  fa-download"></i>EXCEL</a>
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
                    LIST SURVEY PERFORMANCE
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
                                        <th class="text-center">Daily</th>
                                        <th class="text-center">Weekly</th>
                                        <th class="text-center">Monthly</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($memberData)) {
                                        foreach ($memberData as $key => $val):
                                            ?>
                                            <tr class="odd gradeX">
                                                <td class="text-center"><?php echo $key + 1; ?></td>
                                                <td class="text-center"><?php echo $val['name']; ?></td>
                                                <td class="text-center"><?php echo $val['daily'] ?></td>
                                                <td class="text-center"><?php echo $val['weekly'] ?></td>
                                                <td class="text-center"><?php echo $val['monthly'] ?></td>
                                                <td class="text-center"><?php echo $val['total'] ?></td>
                                            </tr>
                                            <?php
                                        endforeach;
                                    }
                                    ?>  
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>