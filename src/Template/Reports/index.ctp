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
        <div class="col-md-12">
            <h1 class="page-header">
                <i class="fa fa-book fa-fw"></i>REPORTS
                
            </h1>
        </div>        
        <!-- /.col-lg-12 -->
    </div>
    <div class="row">
        <div class="col-md-12">
            <?php echo ($this->Flash->render()); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3">
            <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "surveyperformance"]); ?>" >
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <div class="col-md-9 ">
                                <div class="huge"> 
                                    <h3 class="patient "><i>Survey </i></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "surveyperformance"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left"><b class="">View Survey Performance Report</b> </span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "surveys"]); ?>" >
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <i class="fa fa-book fa-5x"></i>
                            </div>
                            <div class="col-md-9 ">
                                <div class="huge"> 
                                    <h3 class="patient "><i>Survey </i></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "surveys"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left"><b class="">View Survey Reports</b> </span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "salesreports"]); ?>" >
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-md-9 ">
                                <div class="huge"> 
                                    <h3 class="patient "><i>Membership</i></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "salesreports"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left"><b class="">View Sales Performance Reports</b> </span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </a>
        </div>
        <div class="col-md-3">
            <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "memberships"]); ?>" >
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-md-9 ">
                                <div class="huge"> 
                                    <h3 class="patient "><i>Membership</i></h3>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Reports", "action" => "memberships"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left"><b class="">View Membership Signup Reports</b> </span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </a>
        </div>
    </div>
</div>