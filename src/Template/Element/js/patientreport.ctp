<?php echo $this->Html->script('jquery-ui.js', array('block' => 'scriptBottom')); ?>
<?php echo $this->Html->css('jquery-ui.css');?>
<?php echo $this->Html->script(array('plugins/ckeditor/ckeditor.js','custom/customedatepicker.js','maskedinput.js','custom/customreporttemplate.js'),['block' => 'scriptBottom']); ?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']);echo "CKEDITOR.replace( 'editor1')";$this->Html->scriptEnd();?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']);echo "CKEDITOR.replace( 'editor2')";$this->Html->scriptEnd();?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']);echo "CKEDITOR.replace( 'editor3')";$this->Html->scriptEnd();?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']);echo "CKEDITOR.replace( 'editor4')";$this->Html->scriptEnd();?>
<?php $this->Html->scriptStart(['block' => 'scriptBottom']);echo "CKEDITOR.replace( 'editor5')";$this->Html->scriptEnd();?>

