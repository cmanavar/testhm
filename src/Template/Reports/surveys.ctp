<!--
 * Template : index
 *
 * Function : Display list of Users
 *
 * $Author: Uncode Chirag Manavar
 * $URL: /users
-->
<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>


<div id="page-wrapper">
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-book fa-fw"></i>SURVEY REPORTS
                <input type="hidden" class="excelurl" value="<?php echo $this->Url->build(["controller" => "Reports", "action" => "exportsurveyreports"]); ?>">
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

    <div class="col-md-12">
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    SEARCH FILTERS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <?php echo $this->Form->create($filters, ['class' => 'form-horizontal validate capitalize', 'enctype' => 'multipart/form-data']); ?>
                        <div class="col-md-2">
                            <?php echo $this->Form->input('sales_id', ['label' => false, 'type' => 'select', 'options' => $salesLists, 'empty' => 'SELECT SALES PERSON', 'id' => 'select-type', 'class' => ' demo-default', 'placeholder' => 'SELECT SALES PERSON']); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->input('from_date', ['label' => false, 'type' => 'text', 'class' => 'form-control datepicker normal-font', 'placeholder' => 'SELECT SERVEY FROM DATE', 'maxlength' => 255]); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->input('to_date', ['label' => false, 'type' => 'text', 'class' => 'form-control datepicker normal-font', 'placeholder' => 'SELECT SERVEY TO DATE', 'maxlength' => 255]); ?>
                        </div>
                        <div class="col-md-2">
                            <?php echo $this->Form->input('contact_number', ['label' => false, 'type' => 'text', 'class' => 'form-control normal-font', 'placeholder' => 'ENTER CONTACT NO', 'maxlength' => 255]); ?>
                        </div>
                        <div class="col-md-2">
                            <div class="custom-radio radio-parallel radio ">
                                <?php echo $this->Form->radio('area_type', [['value' => 'RESIDENTIAL', 'text' => 'RESIDENTIAL'], ['value' => 'COMMERCIAL', 'text' => 'COMMERCIAL']], ['value' => 'Y']); ?>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">SEARCH</button>                                    
                            <?php echo $this->Html->link('RESET FILTER', array('controller' => 'Reports', 'action' => 'resetsurveyfilters'), array('class' => 'removeimage btn btn-warning', 'escape' => false)); ?>
                        </div>
                        <?php echo $this->Form->end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-12 sp-list">        
        <div class="row" id="scroll">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    LIST SURVEYS
                </div>
                <!-- /.panel-heading -->
                <div class="panel-body">
                    <div class="scroll">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="dataTables-responsive" >
                                <thead>
                                    <tr>
                                        <th width="2%">Sr.no</th>
                                        <th>Survey Id</th> 
                                        <th>Name</th> 
                                        <th>Area Type</th> 
                                        <th>Email id</th>
                                        <th>Phone no</th>
                                        <th>Appoinment Date</th>
                                        <th>Survey by</th>
                                        <th>Survey Time</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                    <?php
                                    if (!empty($serveys)) {
                                        foreach ($serveys as $key => $val) {
                                            ?>
                                            <tr>
                                                <td><?php echo $key + 1; ?></td>
                                                <td><?php echo stripslashes($val['survey_id']) ?></td>
                                                <td><?php echo stripslashes($val['person_name']) ?></td>
                                                <td><?php echo stripslashes($val['user_type']) ?></td>
                                                <td><?php echo stripslashes($val['email']) ?></td>
                                                <td><?php echo $val['contact_number']; ?></td>
                                                <td><?php echo $val['appoinment_date']->format('d-m-Y') . " " . date('h:i A', strtotime($val['appoinment_time'])); ?></td>
                                                <td><?php echo $val['survey_by']; ?></td>
                                                <td><?php echo $val['created']->format('d-m-Y h:i A'); ?></td>  
                                            </tr>
                                            <?php
                                        }
                                    } else {
                                        echo '<tr><td colspan="10" style="text-align:center;"><b>No Records found </b></td></tr>';
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
<?php echo $this->element('js/datatable-delete'); ?>