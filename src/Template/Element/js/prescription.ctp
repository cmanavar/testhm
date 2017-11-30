<?php echo $this->Html->script('selectize.js', array('block' => 'scriptBottom'));?>

<?php echo $this->Html->css('selectize.css');?>
<?php echo $this->Html->css('jquery-ui.css');?>
<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->script(array('custom/customedatepicker.js','maskedinput.js'),['block' => 'scriptBottom']); ?>

<?php echo $this->Html->script(array('datatables/media/js/jquery.dataTables.min.js', 'datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js'), array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css'); ?>
<?php echo $this->Html->css('bower_components/datatables-responsive/css/dataTables.responsive.css'); ?>
<?php echo $this->Html->css('bower_components/bootstrap-social/bootstrap-social.css'); ?>