<?php  $msg = $this->Session->flash();if($msg != ''): ?>
<div class="alert alert-success alert-dismissable">
  <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
  <?php echo $msg; ?></div>
<?php endif;?>
