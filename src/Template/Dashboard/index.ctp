<?php
/* * ****************************************************
  @@ Template     :   index
  @@ Description  :   DISPLAY ALL COUNTERS
  @@ Author       :   Chirag Manavar
  @@ Date         :   24-October-2017

 * **************************************************** */
?>

<style>
    .panel-primary a {color: #428bca;}
</style>

<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('selectize.css'); ?>
<?php echo $this->Html->css('jquery-ui.css'); ?>
<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script('maskedinput.js', ['block' => 'scriptBottom']); ?>
<?php echo $this->Html->script('custom/dashboard.js', ['block' => 'scriptBottom']); ?>

<div id="wrapper">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-md-12">
                <h1 class="page-header"><i class="fa fa-home fa-fw"></i>DASHBOARD </h1>
                <?php echo ($this->Flash->render()); ?>
            </div>
            <!-- /.col-md-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-green">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-asterisk fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $counters['services'] ?></div>
                                <div>Services</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Services", "action" => "index"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">View Services</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-yellow">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-users fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $counters['vendor'] ?></div>
                                <div>Vendors</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Vendors", "action" => "index"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">View Vendors</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-red">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-user fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $counters['members']; ?></div>
                                <div>Users</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Members", "action" => "index"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">View Membership Users</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-xs-3">
                                <i class="fa fa-shopping-cart fa-5x"></i>
                            </div>
                            <div class="col-xs-9 text-right">
                                <div class="huge"><?= $counters['orders']; ?></div>
                                <div>Orders</div>
                            </div>
                        </div>
                    </div>
                    <a href="<?php echo $this->Url->build(["controller" => "Orders", "action" => "index"]); ?>">
                        <div class="panel-footer">
                            <span class="pull-left">View Orders</span>
                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                            <div class="clearfix"></div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!-- /#page-wrapper -->
</div>


